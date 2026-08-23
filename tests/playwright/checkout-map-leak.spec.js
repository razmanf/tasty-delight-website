// @ts-check
/**
 * Playwright QA Test — Checkout Leaflet Map Cleanup
 *
 * The checkout page (resources/views/livewire/user/user-checkout.blade.php)
 * initializes a Leaflet map (`checkoutState().initMap()`) shortly after mount.
 * If you navigate away from checkout via wire:navigate (e.g. clicking "Cart"
 * in the navbar) without completing payment, the map instance, its tile
 * layer, and its Alpine $watch subscriptions must all be torn down —
 * otherwise they stay alive in memory indefinitely (same failure class as
 * the dashboard carousel interval leak).
 *
 * This test only drives the app through its normal add-to-cart -> checkout
 * -> navigate-away flow; it does not modify any cart/stock/order logic.
 */

import { test, expect } from '@playwright/test';

const BASE_URL = 'http://127.0.0.1:8000';
const EMAIL    = 'testuser@example.com';
const PASSWORD = 'password';

test('checkout Leaflet map must be destroyed after navigating away without completing payment', async ({ page }) => {
  test.setTimeout(60_000);

  // Log in
  await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
  await page.fill('input[type="email"], input[name="email"]', EMAIL);
  await page.fill('input[type="password"], input[name="password"]', PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL((url) => url.pathname.includes('dashboard') || url.pathname === '/', { timeout: 15_000 });
  await page.waitForLoadState('networkidle');

  // Go to Menu and add the first available product to cart.
  await page.goto(`${BASE_URL}/user/menu`, { waitUntil: 'networkidle' });
  const addToCartButton = page.locator('button[wire\\:click^="addToCart("]').first();
  await expect(addToCartButton).toBeVisible({ timeout: 10_000 });
  await addToCartButton.click();
  await page.waitForTimeout(1_000); // let the Livewire cart update settle

  // Go to Cart, then Proceed to Checkout (a real full page load, per data-navigate-ignore).
  await page.goto(`${BASE_URL}/user/cart`, { waitUntil: 'networkidle' });
  const proceedLink = page.locator('a[href*="user/checkout"]').first();
  await expect(proceedLink).toBeVisible({ timeout: 10_000 });
  await proceedLink.click();
  await page.waitForURL((url) => url.pathname.includes('checkout'), { timeout: 15_000 });
  await page.waitForLoadState('networkidle');

  // Give initMap()'s deferred setTimeout(100ms) time to run and instantiate the Leaflet map.
  const mapExistsOnCheckout = await page.evaluate(async () => {
    await new Promise((r) => setTimeout(r, 2000));
    const mapEl = document.getElementById('checkout-map');
    // Leaflet stamps the container with _leaflet_id once a map is bound to it.
    return !!mapEl && !!mapEl._leaflet_id;
  });
  expect(mapExistsOnCheckout, 'Expected the Leaflet map to be initialized on the checkout page').toBe(true);

  // Navigate away via the navbar Cart link (wire:navigate SPA transition).
  const cartNavLink = page.locator('a[href*="user/cart"]').first();
  await cartNavLink.click();
  await page.waitForLoadState('networkidle');
  await page.waitForURL((url) => url.pathname.includes('cart'), { timeout: 15_000 });

  // The checkout-map element should be gone from the DOM entirely after the morph.
  const mapElementStillInDom = await page.evaluate(() => !!document.getElementById('checkout-map'));
  expect(mapElementStillInDom, 'checkout-map element should no longer exist in the DOM after navigating away').toBe(false);
});