// @ts-check
/**
 * Playwright QA Test — Same-Page Success Flash Messages
 *
 * Confirms that changing layouts.user's success-flash block from
 * @if(session('success')) to @if($__successMessage = session()->pull('success'))
 * (fixing the checkout order-placed race) did not regress the other
 * session()->flash('success', ...) call sites that stay on the same page
 * (no redirect): UserSettings::saveProfile and UserSettings::savePassword.
 *
 * (HasCart::addToCart() previously also flashed a 'success' message, but it
 * was dead code — wire:click AJAX requests never re-render the outer
 * layouts.user.blade.php where the flash banner lives, so it could never
 * have displayed even before this fix. That flash call was removed.)
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

test('profile update shows the "Profile updated successfully!" success message', async ({ page }) => {
  test.setTimeout(30_000);
  await login(page);

  await page.goto(`${BASE_URL}/user/settings`, { waitUntil: 'networkidle' });
  const nameInput = page.locator('input[wire\\:model="name"]');
  await expect(nameInput).toBeVisible({ timeout: 10_000 });

  const currentName = await nameInput.inputValue();
  // Round-trip the same value so the user's actual data isn't changed.
  await nameInput.fill(currentName);
  await page.click('button:has-text("Save Profile")');

  const successBanner = page.locator('text=Profile updated successfully!');
  await expect(successBanner).toBeVisible({ timeout: 5_000 });
});

test('password change shows the "Password changed successfully!" success message', async ({ page }) => {
  test.setTimeout(30_000);
  await login(page);

  const TEMP_PASSWORD = 'TempPass!2026Xy';

  await page.goto(`${BASE_URL}/user/settings`, { waitUntil: 'networkidle' });
  await page.click('button:has-text("Security")');

  const currentPasswordInput = page.locator('input[wire\\:model="currentPassword"]');
  const newPasswordInput = page.locator('input[wire\\:model="newPassword"]');
  const confirmPasswordInput = page.locator('input[wire\\:model="newPasswordConfirmation"]');
  await expect(currentPasswordInput).toBeVisible({ timeout: 10_000 });

  try {
    await currentPasswordInput.fill(PASSWORD);
    await newPasswordInput.fill(TEMP_PASSWORD);
    await confirmPasswordInput.fill(TEMP_PASSWORD);
    await page.click('button:has-text("Update Password")');

    const successBanner = page.locator('text=Password changed successfully!');
    await expect(successBanner).toBeVisible({ timeout: 5_000 });
  } finally {
    // Always revert to the original password, even if the assertion above failed.
    await page.goto(`${BASE_URL}/user/settings`, { waitUntil: 'networkidle' });
    await page.click('button:has-text("Security")');
    await page.locator('input[wire\\:model="currentPassword"]').fill(TEMP_PASSWORD);
    await page.locator('input[wire\\:model="newPassword"]').fill(PASSWORD);
    await page.locator('input[wire\\:model="newPasswordConfirmation"]').fill(PASSWORD);
    await page.click('button:has-text("Update Password")');
    await expect(page.locator('text=Password changed successfully!')).toBeVisible({ timeout: 5_000 });
  }
});