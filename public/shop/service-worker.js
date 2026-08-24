/**
 * Order Saif Storefront Service Worker
 * Phase 65: Storefront Performance & PWA Cache
 */

const CACHE_VERSION = 'v1';
const CACHE_NAME = `fast-order-storefront-${CACHE_VERSION}`;
const RUNTIME_CACHE = `fast-order-runtime-${CACHE_VERSION}`;

// Core static assets to pre-cache on install
const PRECACHE_ASSETS = [
    '/shop/styles.css',
    '/shop/shared.js',
    '/shop/performance.js'
];

// Install Event - Pre-cache core assets
self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('[SW] Pre-caching some assets failed:', err);
            });
        })
    );
});

// Activate Event - Clean up outdated caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME && cacheName !== RUNTIME_CACHE && cacheName.startsWith('fast-order-')) {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Helper: Determine request caching strategy
function getCachingStrategy(request) {
    const url = new URL(request.url);

    // Skip non-GET requests or external domains not related to our app
    if (request.method !== 'GET' || !url.protocol.startsWith('http')) {
        return 'IGNORE';
    }

    // Ignore admin, API mutations, storage uploads, or authentication routes
    if (url.pathname.startsWith('/admin') || url.pathname.startsWith('/api/') || url.pathname.includes('/login') || url.pathname.includes('/logout')) {
        return 'NETWORK_ONLY';
    }

    // Static Assets (CSS, JS, Images, Fonts) -> Stale-While-Revalidate
    if (
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font' ||
        url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|webp|svg|woff|woff2|ttf|eot|ico)$/i)
    ) {
        return 'STALE_WHILE_REVALIDATE';
    }

    // HTML Navigation requests -> Network-First with Cache Fallback
    if (request.mode === 'navigate' || request.headers.get('accept')?.includes('text/html')) {
        return 'NETWORK_FIRST';
    }

    return 'NETWORK_FIRST';
}

// Fetch Event
self.addEventListener('fetch', (event) => {
    const strategy = getCachingStrategy(event.request);

    if (strategy === 'IGNORE' || strategy === 'NETWORK_ONLY') {
        return;
    }

    if (strategy === 'STALE_WHILE_REVALIDATE') {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                const fetchPromise = fetch(event.request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
                        const responseToCache = networkResponse.clone();
                        caches.open(RUNTIME_CACHE).then((cache) => {
                            cache.put(event.request, responseToCache);
                        });
                    }
                    return networkResponse;
                }).catch(() => {
                    return cachedResponse;
                });

                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    if (strategy === 'NETWORK_FIRST') {
        event.respondWith(
            fetch(event.request).then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseToCache = networkResponse.clone();
                    caches.open(RUNTIME_CACHE).then((cache) => {
                        cache.put(event.request, responseToCache);
                    });
                }
                return networkResponse;
            }).catch(async () => {
                const cachedResponse = await caches.match(event.request);
                if (cachedResponse) {
                    return cachedResponse;
                }
                // Return offline fallback if available in cache
                if (event.request.mode === 'navigate') {
                    const offlinePage = await caches.match('/shop/offline.html');
                    if (offlinePage) {
                        return offlinePage;
                    }
                }
                return new Response('Offline: Network unavailable and resource not cached.', {
                    status: 503,
                    statusText: 'Service Unavailable',
                    headers: new Headers({ 'Content-Type': 'text/plain' })
                });
            })
        );
    }
});

// Listen for messages from frontend clients
self.addEventListener('message', (event) => {
    if (event.data && event.data.action === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    if (event.data && event.data.action === 'CLEAR_CACHE') {
        caches.keys().then((names) => {
            for (const name of names) {
                if (name.startsWith('fast-order-')) {
                    caches.delete(name);
                }
            }
        });
    }
});
