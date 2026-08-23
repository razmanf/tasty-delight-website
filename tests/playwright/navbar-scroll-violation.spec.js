// @ts-check
/**
 * Playwright QA Test — Navbar Rapid Navigation Errors
 *
 * Reproduces the console errors seen when rapidly moving along the
 * navbar navigation links in the user panel:
 *
 *   1. [Violation] Added non-passive event listener to a scroll-blocking 'touchmove' event.
 *   2. [Violation] Added non-passive event listener to a scroll-blocking 'touchstart' event.
 *   3. Alpine Expression Error: expanded is not defined
 *      (Livewire morph clobbering Alpine x-data scope during rapid wire:navigate transitions)
 *
 * Test steps:
 *   1. Navigate to the app and log in as the regular user.
 *   2. Collect all console errors/violations.
 *   3. Rapidly hover over each desktop navbar link (simulating fast mouse movement).
 *   4. Click each link in sequence with very short delays (simulating rapid navigation).
 *   5. Assert that no violations or Alpine scope errors appeared.
 */

import { test, expect } from '@playwright/test';

// ── Config ──────────────────────────────────────────────────────────────────
const BASE_URL  = 'http://127.0.0.1:8000';
const EMAIL     = 'testuser@example.com';
const PASSWORD  = 'password';

/** Delay (ms) between simulated rapid navigation actions */
const RAPID_DELAY_MS = 80;

/** Nav links visible in the user desktop navbar (in DOM order) */
const NAV_LINKS = [
  { label: 'Dashboard',  selector: 'a[href*="user/dashboard"], a[href$="/dashboard"]' },
  { label: 'Menu',       selector: 'a[href*="user/menu"]'       },
  { label: 'My Orders',  selector: 'a[href*="user/orders"]'     },
  { label: 'Favorites',  selector: 'a[href*="user/favorites"]'  },
  { label: 'Reviews',    selector: 'a[href*="user/reviews"]'    },
];

// ── Helpers ──────────────────────────────────────────────────────────────────
/** Sleep for `ms` milliseconds. @param {number} ms */
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

// ── Test ──────────────────────────────────────────────────────────────────────
test.describe('User Navbar — Rapid Navigation Scroll-Violation QA', () => {

  test('should NOT emit console violations or Alpine scope errors during rapid navbar navigation', async ({ page }) => {

    // ── 1. Collect all console violations & Alpine errors ───────────────────
    /** @type {string[]} */
    const violations = [];

    page.on('console', (msg) => {
      const text = msg.text();

      // Pattern 1: Chrome [Violation] non-passive touchmove/touchstart
      if (
        msg.type() === 'warning' &&
        text.includes('[Violation]') &&
        (text.includes('touchmove') || text.includes('touchstart')) &&
        text.includes('non-passive')
      ) {
        violations.push(`[touchmove-violation] ${text}`);
      }

      // Pattern 2: Alpine Expression Error — expanded is not defined
      // Caused by Livewire morph clobbering x-data scope during rapid navigation
      if (
        (msg.type() === 'error' || msg.type() === 'warning') &&
        text.includes('Alpine') &&
        text.includes('expanded') &&
        text.includes('not defined')
      ) {
        violations.push(`[alpine-scope-error] ${text}`);
      }
    });

    // Also catch uncaught ReferenceErrors surfaced as page errors
    page.on('pageerror', (err) => {
      const msg = err.message;
      if (msg.includes('non-passive') || (msg.includes('expanded') && msg.includes('not defined'))) {
        violations.push(`[pageerror] ${msg}`);
      }
    });

    // ── 2. Log in ────────────────────────────────────────────────────────────
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });

    // Fill email
    await page.fill('input[type="email"], input[name="email"]', EMAIL);
    // Fill password
    await page.fill('input[type="password"], input[name="password"]', PASSWORD);
    // Submit
    await page.click('button[type="submit"]');

    // Wait for redirect to user dashboard
    await page.waitForURL((url) =>
      url.pathname.includes('dashboard') || url.pathname === '/',
      { timeout: 15_000 }
    );

    // Give Livewire / Alpine a moment to fully boot
    await page.waitForLoadState('networkidle');
    await sleep(500);

    console.log(`Logged in. Current URL: ${page.url()}`);

    // ── 3. Verify we are in the user layout (desktop navbar visible) ──────────
    const navbar = page.locator('nav.td-navbar');
    await expect(navbar).toBeVisible({ timeout: 10_000 });

    // ── 4. Rapid hover simulation over each nav link ─────────────────────────
    console.log('Starting rapid hover simulation over navbar links...');

    for (const nav of NAV_LINKS) {
      const link = navbar.locator(nav.selector).first();
      const isVisible = await link.isVisible().catch(() => false);

      if (!isVisible) {
        console.warn(`Nav link "${nav.label}" not found / not visible — skipping hover.`);
        continue;
      }

      await link.hover({ force: true });
      await sleep(RAPID_DELAY_MS);
    }

    // ── 5. Rapid click navigation (back-and-forth) ────────────────────────────
    console.log('Starting rapid click navigation across navbar links...');

    // We do two full passes to simulate the "rapid moving" the user describes
    for (let pass = 0; pass < 2; pass++) {
      const order = pass % 2 === 0 ? NAV_LINKS : [...NAV_LINKS].reverse();

      for (const nav of order) {
        // Re-locate after each navigation (Livewire may re-render the navbar)
        const navbarFresh = page.locator('nav.td-navbar');
        const link = navbarFresh.locator(nav.selector).first();
        const isVisible = await link.isVisible().catch(() => false);

        if (!isVisible) {
          console.warn(`Nav link "${nav.label}" not visible on pass ${pass + 1} — skipping click.`);
          continue;
        }

        console.log(`Clicking "${nav.label}" (pass ${pass + 1})...`);
        await link.click({ force: true });

        // Very short delay to simulate rapid user behaviour
        await sleep(RAPID_DELAY_MS);

        // Wait briefly for any in-flight Livewire request to settle
        await page.waitForLoadState('domcontentloaded').catch(() => {});
      }
    }

    // Give enough time for any deferred violation messages to surface
    await sleep(1_000);

    // ── 6. Assert ─────────────────────────────────────────────────────────────
    if (violations.length > 0) {
      console.error('FAIL — Non-passive scroll-blocking violations detected:');
      violations.forEach((v, i) => console.error(`  [${i + 1}] ${v}`));
    } else {
      console.log('PASS — No non-passive touchmove/touchstart violations detected.');
    }

    const failMessage = violations.length > 0
      ? [
          `${violations.length} non-passive scroll-blocking violation(s) emitted during rapid navbar navigation.`,
          'Violations:',
          ...violations.map((v, i) => `  [${i + 1}] ${v}`),
          '',
          'Fix: Add { passive: true } to all touchmove/touchstart event listeners,',
          'or apply CSS "touch-action: pan-y" on the relevant elements.',
        ].join('\n')
      : '';

    expect(failMessage, failMessage).toBe('');
  });

});
