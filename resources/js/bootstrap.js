/**
 * TabyFinance / StudioFinance Client Prototype
 * Lightweight browser runtime setup
 */

// CSRF Token setup for fetch requests
window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
