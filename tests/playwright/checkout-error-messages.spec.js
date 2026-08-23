// @ts-check
/**
 * Playwright QA Test — Checkout Error Flash Messages
 *
 * UserCheckout::validateStock() flashes session('error', ...) and redirects
 * with navigate:false (a real HTTP redirect) when a cart item's stock drops
 * below the requested quantity between reaching checkout and submitting.
 * Unlike the 'success' flash fixed for the order-placed race, this path was
 * never changed — it stays a plain flash() since navigate:false redirects
 * don't race with intervening Livewire AJAX requests. This test only
 * confirms that untouched path still works.
 *
 * The race is simulated with a direct, temporary DB stock update (not by
 * exercising any cart/stock-decrement application code), and the product's
 * original stock is restored immediately after the assertion regardless of
 * outcome.
 */

import { test, expect } from '@playwright/test';
import { execFileSync } from 'node:child_process';
import path from 'node:path';

const BASE_URL = 'http://127.0.0.1:8000';
const EMAIL    = 'testuser@example.com';
const PASSWORD = 'password';
const PROJECT_ROOT = 'c:/xampp/htdocs/tasty-delight-website';
const STOCK_SCRIPT = path.join(PROJECT_ROOT, 'tests/playwright/fixtures/set-product-stock.php');

/** @param {string} productId */
function getProductStock(productId) {
  const out = execFileSync('C:/xampp/php/php.exe', [STOCK_SCRIPT, 'get', String(productId)], { cwd: PROJECT_ROOT });
  return parseInt(out.toString().trim(), 10);
}

/**
 * @param {string} productId
 * @param {number} stock
 */
function setProductStock(productId, stock) {
  execFileSync('C:/xampp/php/php.exe', [STOCK_SCRIPT, 'set', String(productId), String(stock)], { cwd: PROJECT_ROOT });
}

/** @param {import('@playwright/test').Page} page */
async function login(page) {
  await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
  await page.fill('input[type="email"], input[name="email"]', EMAIL);
  await page.fill('input[type="password"], input[name="password"]', PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL((url) => url.pathname.includes('dashboard') || url.pathname === '/', { timeout: 15_000 });
  await page.waitForLoadState('networkidle');
}

test('checkout stock-race error message still displays correctly', async ({ page }) => {
  test.setTimeout(60_000);

  await login(page);

  // Add a product to cart and note which product it was.
  await page.goto(`${BASE_URL}/user/menu`, { waitUntil: 'networkidle' });
  const addToCartButton = page.locator('button[wire\\:click^="addToCart("]').first();
  await expect(addToCartButton).toBeVisible({ timeout: 10_000 });
  const wireClickAttr = await addToCartButton.getAttribute('wire:click');
  const match = wireClickAttr?.match(/addToCart\((\d+)\)/);
  if (!match) throw new Error('Could not find a product ID in the Add to Cart button\'s wire:click attribute');
  const productId = match[1];
  await addToCartButton.click();
  await page.waitForTimeout(1_000);

  // Read and remember the product's current stock so it can be restored.
  const originalStock = getProductStock(productId);
  expect(Number.isFinite(originalStock)).toBe(true);

  await page.goto(`${BASE_URL}/user/cart`, { waitUntil: 'networkidle' });
  const proceedLink = page.locator('a[href*="user/checkout"]').first();
  await expect(proceedLink).toBeVisible({ timeout: 10_000 });
  await proceedLink.click();
  await page.waitForURL((url) => url.pathname.includes('checkout'), { timeout: 15_000 });
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(1_500);

  try {
    // Simulate a concurrent buyer depleting stock while this user is on the checkout page.
    setProductStock(productId, 0);

    // Pickup is the simplest path to processOrder() without extra form filling.
    await page.click('button:has-text("Pickup")');
    await page.waitForTimeout(500);
    await page.click('button:has-text("Place Order Now")');

    // validateStock() failure redirects (navigate:false) back to the cart page.
    await page.waitForURL((url) => url.pathname.includes('cart'), { timeout: 15_000 });
    await page.waitForLoadState('networkidle');

    const errorBanner = page.locator('text=no longer available in the requested quantity');
    await expect(errorBanner).toBeVisible({ timeout: 5_000 });
  } finally {
    // Always restore the product's original stock, even if an assertion above failed.
    setProductStock(productId, originalStock);
  }
});