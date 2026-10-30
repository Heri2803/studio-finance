import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

/**
 * --------------------------------------------------------------------------
 * StudioFinance High-Performance Seamless Router & Mascot Animator
 * --------------------------------------------------------------------------
 * 1. Speculative Prefetcher: Prefetches HTML on hover/touchstart for near 0ms navigation.
 * 2. Instant Zero-Flicker Page Swap: Swaps DOM without blank-screen delays.
 * 3. Page Opening Animation: Buttery-smooth slide-up and fade-in (320ms 60fps).
 * 4. High-Energy Mascot Spring Bounce: Dynamic cat pop-up & ear tilt on every page visit.
 * 5. Full Header & Navigation Sync: Top banner, desktop menu, & mobile bottom tabs.
 */
document.addEventListener('DOMContentLoaded', () => {
    const prefetchedUrls = new Set();
    let isNavigating = false;

    // Speculative Link Prefetcher
    function prefetchUrl(url) {
        if (!url || prefetchedUrls.has(url)) return;
        try {
            const parsed = new URL(url, window.location.origin);
            if (parsed.origin !== window.location.origin) return;
            if (parsed.pathname === window.location.pathname && parsed.search === window.location.search) return;
            if (parsed.pathname.includes('/pdf') || parsed.pathname.includes('/destroy')) return;

            prefetchedUrls.add(url);
            const prefetchLink = document.createElement('link');
            prefetchLink.rel = 'prefetch';
            prefetchLink.href = url;
            prefetchLink.as = 'document';
            document.head.appendChild(prefetchLink);
        } catch (e) {
            // Ignore invalid URLs
        }
    }

    // Attach prefetch triggers to links
    function attachPrefetchListeners(root = document) {
        const links = root.querySelectorAll('a[href^="/"], a[href^="' + window.location.origin + '"]');
        links.forEach(link => {
            link.addEventListener('pointerenter', () => prefetchUrl(link.href), { passive: true });
            link.addEventListener('touchstart', () => prefetchUrl(link.href), { passive: true });
        });
    }

    // Trigger dynamic Cat Mascot spring bounce & speech bubble slide-in
    function triggerCatAnimations(container = document) {
        const avatars = container.querySelectorAll('[data-cat-avatar]');
        avatars.forEach(el => {
            el.classList.remove('animate-cat-bounce');
            void el.offsetWidth; // Force DOM reflow to re-trigger CSS keyframe
            el.classList.add('animate-cat-bounce');
        });

        const bubbles = container.querySelectorAll('[data-cat-bubble]');
        bubbles.forEach(el => {
            el.classList.remove('animate-bubble-slide');
            void el.offsetWidth; // Force DOM reflow
            el.classList.add('animate-bubble-slide');
        });
    }

    // Trigger whole page entrance & staggered card cascade
    function triggerPageEntrance(container = document) {
        const animEls = container.querySelectorAll('.anim-enter');
        animEls.forEach(el => {
            el.classList.remove('anim-enter');
            void el.offsetWidth; // Force DOM reflow
            el.classList.add('anim-enter');
        });
    }

    // Seamless Page Navigation Handler
    async function navigateTo(url, push = true) {
        if (isNavigating) return;

        const currentPath = window.location.pathname + window.location.search;
        const targetUrl = new URL(url, window.location.origin);
        const targetPath = targetUrl.pathname + targetUrl.search;

        if (currentPath === targetPath) return;

        isNavigating = true;

        try {
            // Fetch target page HTML immediately (zero blank-screen blackout!)
            const res = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            });

            if (!res.ok) {
                window.location.href = url;
                return;
            }

            const html = await res.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const main = document.getElementById('main-content');
            const newMain = doc.getElementById('main-content');
            if (!newMain || !main) {
                window.location.href = url;
                return;
            }

            // Cleanup previous Alpine components on main
            if (window.Alpine && typeof window.Alpine.destroyTree === 'function') {
                window.Alpine.destroyTree(main);
            }

            // Update document title
            if (doc.title) {
                document.title = doc.title;
            }

            // Sync Top Header Brand (Mascot avatar + Role title)
            const currentHeaderBrand = document.getElementById('header-brand');
            const newHeaderBrand = doc.getElementById('header-brand');
            if (currentHeaderBrand && newHeaderBrand) {
                currentHeaderBrand.innerHTML = newHeaderBrand.innerHTML;
            }

            // Sync Desktop Nav Active State
            const currentDesktopNav = document.getElementById('desktop-nav');
            const newDesktopNav = doc.getElementById('desktop-nav');
            if (currentDesktopNav && newDesktopNav) {
                currentDesktopNav.innerHTML = newDesktopNav.innerHTML;
            }

            // Sync Mobile & Tablet Bottom Nav Active State
            const currentMobileNav = document.getElementById('mobile-nav');
            const newMobileNav = doc.getElementById('mobile-nav');
            if (currentMobileNav && newMobileNav) {
                currentMobileNav.innerHTML = newMobileNav.innerHTML;
            }

            // Push State to browser history
            if (push) {
                window.history.pushState({ url }, '', url);
            }

            // Swap Main Content
            main.innerHTML = newMain.innerHTML;

            // Scroll instantly to top
            window.scrollTo({ top: 0, behavior: 'instant' });

            // Rehydrate Alpine components on newly loaded DOM
            if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                window.Alpine.initTree(main);
            }

            // Visibly trigger Page Entrance Animation & Cat Mascot Bounce
            triggerPageEntrance(main);
            triggerCatAnimations(main);

            // Re-attach prefetch listeners for newly inserted links
            attachPrefetchListeners(main);

        } catch (err) {
            console.error('Seamless SPA transition failed, falling back to full reload:', err);
            window.location.href = url;
        } finally {
            isNavigating = false;
        }
    }

    // Intercept navigation link clicks
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link || !link.href) return;

        // Skip non-HTTP links (mailto, tel, wa.me, etc.)
        if (!link.href.startsWith('http://') && !link.href.startsWith('https://')) return;

        // Skip external domains
        const url = new URL(link.href);
        if (url.origin !== window.location.origin) return;

        // Skip modified clicks (Ctrl, Cmd, Shift, Alt)
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        // Skip new tab targets or downloads
        if (link.target && link.target !== '_self') return;
        if (link.hasAttribute('download')) return;
        if (link.getAttribute('data-no-spa') !== null) return;

        // Skip PDF preview/downloads & destructive endpoints
        if (url.pathname.includes('/pdf') || url.pathname.includes('/destroy')) return;

        // Skip in-page hash anchor links on same page
        if (url.pathname === window.location.pathname && url.hash) return;

        // Eligible for seamless SPA navigation!
        e.preventDefault();
        navigateTo(link.href, true);
    });

    // Handle Browser Back / Forward buttons (popstate)
    window.addEventListener('popstate', () => {
        navigateTo(window.location.href, false);
    });

    // Initial trigger on initial page load / refresh
    const mainEl = document.getElementById('main-content');
    triggerPageEntrance(mainEl);
    triggerCatAnimations(document);
    attachPrefetchListeners();
});
