// @ts-check
import { test, expect } from '@playwright/test';

const BASE_URL  = 'http://127.0.0.1:8000';
const EMAIL     = 'testuser@example.com';
const PASSWORD  = 'password';

test.describe('Header Visibility Check', () => {
  test('Main header should be visible after login', async ({ page }) => {
    // 1. Navigate to login
    console.log('Navigating to login page...');
    await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle' });

    // 2. Fill credentials
    console.log('Filling credentials...');
    await page.fill('input[type="email"], input[name="email"]', EMAIL);
    await page.fill('input[type="password"], input[name="password"]', PASSWORD);

    // 3. Submit
    console.log('Submitting login form...');
    await page.click('button[type="submit"], input[type="submit"]');

    // Wait for redirect to dashboard
    console.log('Waiting for redirect to complete...');
    await page.waitForURL('**/user/dashboard', { timeout: 10000 });
    console.log('Redirected to:', page.url());

    // 4. Verify main header is visible
    // Based on standard Tasty Delight user layout, the header is typically a <header> element or has a specific id/class.
    // We'll check for a general header element or navigation block.
    console.log('Checking main header visibility...');
    
    // Check multiple common selectors for the header in case one isn't found
    const headerSelectors = [
      'header',
      'nav',
      '.td-navbar',
      '[data-navbar]'
    ];
    
    let isVisible = false;
    let foundSelector = null;
    
    for (const selector of headerSelectors) {
        try {
            const locator = page.locator(selector).first();
            if (await locator.count() > 0 && await locator.isVisible()) {
                isVisible = true;
                foundSelector = selector;
                break;
            }
        } catch (e) {
            // Ignore timeout/not found for individual selectors
        }
    }

    if (isVisible) {
        console.log(`✅ Main header IS visible (matched selector: "${foundSelector}").`);
    } else {
        console.log(`❌ Main header IS NOT visible (checked selectors: ${headerSelectors.join(', ')}).`);
    }
    
    expect(isVisible).toBe(true);
  });
});
