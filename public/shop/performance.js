/**
 * Order Saif Storefront Performance Library
 * Phase 65: Lazy Loading, Image Optimization (WebP), Skeleton Loaders, & PWA Registration
 */

(function (window, document) {
    'use strict';

    const StorefrontPerformance = {
        webpSupported: false,
        observer: null,
        mutationObserver: null,
        config: {
            lazySelector: 'img[data-src], source[data-srcset], .lazy-image, .lazy-bg',
            skeletonSelector: '.skeleton-loader, [data-skeleton="true"]',
            rootMargin: '200px 0px',
            threshold: 0.01,
            swPath: '/shop/service-worker.js'
        },

        /**
         * Initialize Storefront Performance optimizations
         */
        init: function (customConfig = {}) {
            this.config = Object.assign({}, this.config, customConfig);
            this.injectSkeletonStyles();
            this.checkWebPSupport(() => {
                this.initLazyLoading();
                this.initSkeletons();
                this.observeDOMMutations();
            });
            this.registerServiceWorker(this.config.swPath);
            console.log('[StorefrontPerformance] Initialized successfully.');
        },

        /**
         * Check browser WebP support
         */
        checkWebPSupport: function (callback) {
            const webP = new Image();
            webP.onload = webP.onerror = () => {
                this.webpSupported = (webP.height === 2);
                if (this.webpSupported) {
                    document.documentElement.classList.add('webp');
                } else {
                    document.documentElement.classList.add('no-webp');
                }
                if (typeof callback === 'function') callback(this.webpSupported);
            };
            webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
        },

        /**
         * Convert image URL to WebP if supported and applicable
         */
        optimizeImageUrl: function (url) {
            if (!url) return url;
            if (!this.webpSupported) return url;

            // If URL explicitly opted out or is already SVG/WebP/Data-URI
            if (url.match(/\.(svg|webp|gif)$/i) || url.startsWith('data:')) {
                return url;
            }

            // If local/static image ending with jpg, jpeg, png, convert to webp if auto-webp enabled
            if (url.match(/\.(jpg|jpeg|png)$/i)) {
                return url.replace(/\.(jpg|jpeg|png)$/i, '.webp');
            }

            return url;
        },

        /**
         * Initialize IntersectionObserver for Lazy Loading
         */
        initLazyLoading: function () {
            const elements = document.querySelectorAll(this.config.lazySelector);

            if ('IntersectionObserver' in window) {
                if (this.observer) this.observer.disconnect();

                this.observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            this.loadElement(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    rootMargin: this.config.rootMargin,
                    threshold: this.config.threshold
                });

                elements.forEach((el) => {
                    if (!el.classList.contains('loaded')) {
                        this.observer.observe(el);
                    }
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                elements.forEach((el) => this.loadElement(el));
            }
        },

        /**
         * Load individual lazy element (Image, Background, or Source)
         */
        loadElement: function (el) {
            if (el.classList.contains('loaded')) return;

            // Trigger skeleton loading state if present
            this.showSkeleton(el);

            const isImg = el.tagName.toLowerCase() === 'img';
            const isSource = el.tagName.toLowerCase() === 'source';
            let src = el.getAttribute('data-src');
            let srcset = el.getAttribute('data-srcset');

            // Optimize to WebP if auto-optimization is requested or data-webp-src is available
            const webpSrc = el.getAttribute('data-webp-src');
            if (this.webpSupported && webpSrc) {
                src = webpSrc;
            } else if (el.hasAttribute('data-auto-webp') && src) {
                src = this.optimizeImageUrl(src);
            }

            if (isImg) {
                el.onload = () => {
                    el.classList.add('loaded');
                    el.classList.remove('lazy-image', 'lazy-load');
                    this.hideSkeleton(el);
                    el.removeAttribute('data-src');
                    if (srcset) el.removeAttribute('data-srcset');
                };
                el.onerror = () => {
                    // Fallback to original src if WebP failed
                    const originalSrc = el.getAttribute('data-original-src') || el.getAttribute('data-src');
                    if (originalSrc && el.src !== originalSrc) {
                        el.src = originalSrc;
                    }
                    el.classList.add('error-loaded');
                    this.hideSkeleton(el);
                };

                if (srcset) el.srcset = srcset;
                if (src) el.src = src;
            } else if (isSource) {
                if (srcset) el.srcset = srcset;
                if (src) el.src = src;
                const parentPicture = el.closest('picture');
                if (parentPicture) {
                    const img = parentPicture.querySelector('img');
                    if (img) this.loadElement(img);
                }
            } else {
                // Background image lazy loading (.lazy-bg)
                if (src) {
                    const tempImg = new Image();
                    tempImg.onload = () => {
                        el.style.backgroundImage = `url('${src}')`;
                        el.classList.add('loaded');
                        el.classList.remove('lazy-bg');
                        this.hideSkeleton(el);
                    };
                    tempImg.onerror = () => this.hideSkeleton(el);
                    tempImg.src = src;
                }
            }
        },

        /**
         * Skeleton Loaders Management
         */
        initSkeletons: function () {
            const skeletons = document.querySelectorAll(this.config.skeletonSelector);
            skeletons.forEach((el) => {
                if (!el.classList.contains('skeleton-active') && !el.classList.contains('loaded')) {
                    el.classList.add('skeleton-active');
                }
            });
        },

        showSkeleton: function (el) {
            if (el.hasAttribute('data-skeleton') || el.classList.contains('skeleton-loader')) {
                el.classList.add('skeleton-active');
            } else {
                const wrapper = el.closest('.skeleton-wrapper');
                if (wrapper) wrapper.classList.add('skeleton-active');
            }
        },

        hideSkeleton: function (el) {
            el.classList.remove('skeleton-active');
            const wrapper = el.closest('.skeleton-wrapper');
            if (wrapper) {
                wrapper.classList.remove('skeleton-active');
                wrapper.classList.add('skeleton-completed');
            }
        },

        /**
         * Inject CSS for modern Shimmer Skeleton Animation
         */
        injectSkeletonStyles: function () {
            if (document.getElementById('fast-order-skeleton-styles')) return;

            const style = document.createElement('style');
            style.id = 'fast-order-skeleton-styles';
            style.textContent = `
                .skeleton-active, .skeleton-loader {
                    position: relative !important;
                    overflow: hidden !important;
                    background-color: #e2e8f0 !important;
                    color: transparent !important;
                    border-color: transparent !important;
                    user-select: none !important;
                    pointer-events: none !important;
                    border-radius: 8px;
                }
                .skeleton-active::after, .skeleton-loader::after {
                    content: "" !important;
                    position: absolute !important;
                    top: 0; right: 0; bottom: 0; left: 0;
                    transform: translateX(-100%);
                    background-image: linear-gradient(
                        90deg,
                        rgba(255, 255, 255, 0) 0,
                        rgba(255, 255, 255, 0.4) 20%,
                        rgba(255, 255, 255, 0.7) 60%,
                        rgba(255, 255, 255, 0) 100%
                    );
                    animation: OrderSaifShimmer 1.5s infinite;
                }
                @keyframes OrderSaifShimmer {
                    100% {
                        transform: translateX(100%);
                    }
                }
                .skeleton-completed {
                    transition: opacity 0.3s ease-in-out;
                }
                /* Dark mode skeleton support if html or body has dark class */
                .dark .skeleton-active, .dark .skeleton-loader,
                [data-theme="dark"] .skeleton-active, [data-theme="dark"] .skeleton-loader {
                    background-color: #334155 !important;
                }
                .dark .skeleton-active::after, .dark .skeleton-loader::after,
                [data-theme="dark"] .skeleton-active::after, [data-theme="dark"] .skeleton-loader::after {
                    background-image: linear-gradient(
                        90deg,
                        rgba(255, 255, 255, 0) 0,
                        rgba(255, 255, 255, 0.05) 20%,
                        rgba(255, 255, 255, 0.1) 60%,
                        rgba(255, 255, 255, 0) 100%
                    );
                }
            `;
            document.head.appendChild(style);
        },

        /**
         * Watch DOM mutations for dynamically loaded content (AJAX / Infinite Scroll)
         */
        observeDOMMutations: function () {
            if (!('MutationObserver' in window)) return;
            if (this.mutationObserver) this.mutationObserver.disconnect();

            this.mutationObserver = new MutationObserver((mutations) => {
                let hasNewLazy = false;
                mutations.forEach((mutation) => {
                    if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === 1) {
                                if (node.matches && node.matches(this.config.lazySelector)) {
                                    hasNewLazy = true;
                                    if (this.observer) this.observer.observe(node);
                                }
                                const nestedLazy = node.querySelectorAll ? node.querySelectorAll(this.config.lazySelector) : [];
                                if (nestedLazy.length > 0) {
                                    hasNewLazy = true;
                                    nestedLazy.forEach((el) => {
                                        if (this.observer && !el.classList.contains('loaded')) {
                                            this.observer.observe(el);
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
                if (hasNewLazy) {
                    this.initSkeletons();
                }
            });

            this.mutationObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        },

        /**
         * Register PWA Service Worker
         */
        registerServiceWorker: function (swPath) {
            if ('serviceWorker' in navigator && swPath) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register(swPath, { scope: '/shop/' })
                        .then((registration) => {
                            console.log('[PWA] Service Worker registered with scope:', registration.scope);
                            registration.onupdatefound = () => {
                                const installingWorker = registration.installing;
                                if (installingWorker) {
                                    installingWorker.onstatechange = () => {
                                        if (installingWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                            console.log('[PWA] New content available; please refresh.');
                                            // Dispatch event so UI can show update notification if desired
                                            window.dispatchEvent(new CustomEvent('pwaUpdateAvailable', { detail: registration }));
                                        }
                                    };
                                }
                            };
                        })
                        .catch((error) => {
                            console.warn('[PWA] Service Worker registration failed:', error);
                        });
                });
            }
        }
    };

    // Expose global object
    window.StorefrontPerformance = StorefrontPerformance;

    // Auto initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => StorefrontPerformance.init());
    } else {
        StorefrontPerformance.init();
    }

})(window, document);
