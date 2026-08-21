/**
 * Frontend configuration for the security awareness demo.
 *
 * The API base URL is configurable so the same static HTML files work
 * in both local development and production deployments.
 *
 * Local development:  https://vdgvasygd.nexxora-ai.com (Laravel via `php artisan serve`)
 * Production:         Set to your deployed Laravel backend HTTPS URL.
 *
 * To override at runtime (e.g. via a meta tag or inline script before this
 * file loads), set `window.API_BASE_URL` first — this file will respect it.
 */
window.API_BASE_URL = window.API_BASE_URL || 'https://vdgvasygd.nexxora-ai.com';
