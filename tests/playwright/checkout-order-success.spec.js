// @ts-check
/**
 * Playwright QA Test — Full Checkout Order Placement + Success Message
 *
 * Verifies the fix for an intermittent bug: after placing an order,
 * UserCheckout::processOrder() used session()->flash('success', ...) followed
 * by $this->redirectRoute('user.orders', navigate: true). Because navigate:true
 * is a Livewire SPA-style redirect (fetch + morph, not a real HTTP redirect),
 * an intervening Livewire AJAX request (e.g. cart-count-badge re-rendering)
 * could age out the flash data before the orders page ever read it, causing
 * the "Order placed successfully!" message to intermittently not appear.
 *
 * Fix: session()->put('success', ...) + session()->pull('success') in the
 * layout, which survives any number of intervening requests and is only
 * consumed once actually displayed.
 *
 * This drives three real order placements through the actual app (pickup,
 * delivery+cash, delivery+card via Stripe test mode) — it creates real
 * Order rows and decrements real product stock. It does not modify any
 * cart/stock/order logic.
 */

import { test, expect } from '@playwright/test';

const BASE_URL = 'http://127.0.0.1:8000';
const EMAIL    = 'testuser@example.com';
const PASSWORD = 'password';

async function login(page) {
  await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
  await page.fill('input[type="email"], input[name="email"]', EMAIL);
  await page.fill('input[type="password"], input[name="password"]', PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL((url) => url.pathname.includes('dashboard') || url.pathname === '/', { timeout: 15_000 });
  await page.waitForLoadState('networkidle');
}

async function addProductToCartAndGoToCheckout(page) {
  await page.goto(`${BASE_URL}/user/menu`, { waitUntil: 'networkidle' });
  const addToCartButton = page.locator('button[wire\\:click^="addToCart("]').first();
  await expect(addToCartButton).toBeVisible({ timeout: 10_000 });
  await addToCartButton.click();
  await page.waitForTimeout(1_000);

  await page.goto(`${BASE_URL}/user/cart`, { waitUntil: 'networkidle' });
  const proceedLink = page.locator('a[href*="user/checkout"]').first();
  await expect(proceedLink).toBeVisible({ timeout: 10_000 });
  await proceedLink.click();
  await page.waitForURL((url) => url.pathname.includes('checkout'), { timeout: 15_000 });
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(1_500); // let Alpine boot() + Stripe PaymentIntent settle
}

async function expectSuccessMessageOnOrders(page) {
  await page.waitForURL((url) => url.pathname.includes('orders'), { timeout: 20_000 });
  await page.waitForLoadState('networkidle');
  const successBanner = page.locator('text=Order placed successfully! Check your email for the receipt.');
  await expect(successBanner).toBeVisible({ timeout: 5_000 });
}

test.describe('Checkout order placement — success message reliability', () => {
  test('pickup order shows the success message on My Orders', async ({ page }) => {
    test.setTimeout(60_000);
    await login(page);
    await addProductToCartAndGoToCheckout(page);

    // Switch to Pickup (default order_type is 'delivery').
    await page.click('button:has-text("Pickup")');
    await page.waitForTimeout(500);

    await page.click('button:has-text("Place Order Now")');
    await expectSuccessMessageOnOrders(page);
  });

  test('delivery + cash order shows the success message on My Orders', async ({ page }) => {
    test.setTimeout(60_000);
    await login(page);
    await addProductToCartAndGoToCheckout(page);

    // Delivery is the default order_type; click the map to set a delivery address/coords.
    const mapEl = page.locator('#checkout-map');
    await expect(mapEl).toBeVisible({ timeout: 10_000 });
    await mapEl.click({ position: { x: 60, y: 60 } });
    await page.waitForTimeout(1_500); // reverse-geocode fetch to set delivery_address

    // Payment method already defaults to 'cash'.
    await page.click('button:has-text("Place Order Now")');
    await expectSuccessMessageOnOrders(page);
  });

  test('delivery + card order shows the success message on My Orders', async ({ page }) => {
    test.setTimeout(90_000);
    await login(page);
    await addProductToCartAndGoToCheckout(page);

    const mapEl = page.locator('#checkout-map');
    await expect(mapEl).toBeVisible({ timeout: 10_000 });
    await mapEl.click({ position: { x: 60, y: 60 } });
    await page.waitForTimeout(1_500);

    // Switch payment method to Card.
    await page.click('button:has-text("Cash on Delivery")');
    await page.click('button:has-text("Pay by Card")');
    await page.waitForTimeout(300);

    // confirmOrder() advances to step 3 (Stripe payment form) for delivery+card.
    await page.click('button:has-text("Place Order Now")');
    await page.waitForTimeout(2_000);

    // Fill Stripe's test card into its iframe.
    const stripeFrame = page.frameLocator('iframe[title="Secure payment input frame"]').first();
    await stripeFrame.locator('input[name="number"]').fill('4242424242424242');
    await stripeFrame.locator('input[name="expiry"]').fill('12/34');
    await stripeFrame.locator('input[name="cvc"]').fill('123');

    const submitButton = page.locator('#submit');
    await expect(submitButton).toBeVisible({ timeout: 10_000 });
    await submitButton.click();

    await expectSuccessMessageOnOrders(page);
  });
});