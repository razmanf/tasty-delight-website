// @ts-check
import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests/playwright',
  fullyParallel: false,
  retries: 0,
  workers: 1,
  reporter: [['list'], ['html', { open: 'never' }]],
  use: {
    baseURL: 'http://127.0.0.1:8000',
    trace: 'on', // Record traces for all tests so UI mode always has visual data
    screenshot: 'only-on-failure', // Automatically take screenshot if a test fails
    video: 'retain-on-failure',    // Automatically record video if a test fails
    // Chromium-based browser so that [Violation] console warnings surface
    channel: 'chrome',
    viewport: { width: 1280, height: 800 },
  },
  projects: [
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
  ],
});
