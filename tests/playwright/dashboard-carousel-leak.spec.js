// @ts-check
/**
 * Playwright QA Test — Dashboard Carousel Interval Leak
 *
 * The hero banner carousel on the user dashboard (resources/views/livewire/user-dashboard.blade.php)
 * auto-advances via setInterval every 7s. This verifies that interval is cleared
 * when navigating away from the dashboard via wire:navigate, instead of continuing
 * to run (and tick) on other pages.
 *
 * Detection method: wrap window.setInterval/clearInterval before any page script
 * runs, and track how many intervals created on the dashboard are still alive
 * after navigating away. This does not depend on any app-side debug logging.
 */

import { test, expect } from '@playwright/test';

const BASE_URL = 'http://127.0.0.1:8000';
const EMAIL    = 'testuser@example.com';
const PASSWORD = 'password';

const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

test('carousel interval must be cleared after navigating away from dashboard', async ({ page }) => {
  test.setTimeout(60_000);

  // Track every setInterval call with a >=1000ms delay (the carousel uses 7000ms)
  // and whether it was later cleared, without touching app code.
  await page.addInitScript(() => {
    // @ts-ignore
    window.__intervals = new Map();
    const realSetInterval = window.setInterval;
    const realClearInterval = window.clearInterval;

    // @ts-ignore
    window.setInterval = function (fn, delay, ...args) {
      const id = realSetInterval(fn, delay, ...args);
      if (delay === 7000) {
        // @ts-ignore
        window.__intervals.set(id, { delay, cleared: false });
      }
      return id;
    };

    // @ts-ignore
    window.clearInterval = function (id) {
      // @ts-ignore
      if (window.__intervals.has(id)) {
        // @ts-ignore
        window.__intervals.get(id).cleared = true;
      }
      return realClearInterval(id);
    };
  });

  // Log in
  await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });
  await page.fill('input[type="email"], input[name="email"]', EMAIL);
  await page.fill('input[type="password"], input[name="password"]', PASSWORD);
  await page.click('button[type="submit"]');
  await page.waitForURL((url) => url.pathname.includes('dashboard') || url.pathname === '/', { timeout: 15_000 });
  await page.waitForLoadState('networkidle');

  // Give the carousel time to finish Livewire's hydration cycle and start its real interval.
  await sleep(3_000);

  const activeOnDashboard = await page.evaluate(() => {
    // @ts-ignore
    return [...window.__intervals.values()].filter((i) => !i.cleared).length;
  });
  expect(activeOnDashboard, 'Expected the 7s carousel interval to be active while on the dashboard').toBeGreaterThan(0);

  // Navigate to Menu via navbar (wire:navigate SPA transition)
  const menuLink = page.locator('a[href*="user/menu"]').first();
  await menuLink.click();
  await page.waitForLoadState('networkidle');

  // Give any leaked interval a couple of ticks worth of time to prove it's still alive.
  await sleep(15_000);

  const stillActiveAfterLeaving = await page.evaluate(() => {
    // @ts-ignore
    return [...window.__intervals.entries()].filter(([, i]) => !i.cleared);
  });

  expect(
    stillActiveAfterLeaving.length,
    `Carousel interval(s) leaked past navigation: ${JSON.stringify(stillActiveAfterLeaving)}`
  ).toBe(0);
});