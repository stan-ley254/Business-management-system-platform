const CACHE_NAME = 'bms-cache-v1';
const OFFLINE_URL = '/offline.html';

// Files to cache immediately on install
const ASSETS_TO_CACHE = [
    '/',
    '/offline.html',
    '/css/app.css',
    '/js/app.js',
    '/js/scriptsfiles.js',
    '/favicon_io/favicon.ico',
    '/favicon_io/apple-touch-icon.png',
    '/favicon_io/android-chrome-192x192.png',
    '/favicon_io/android-chrome-512x512.png'
];

// Install: cache app shell
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('[SW] Caching app shell');
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

// Activate: clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.map(key => {
                    if (key !== CACHE_NAME) {
                        console.log('[SW] Deleting old cache:', key);
                        return caches.delete(key);
                    }
                })
            )
        )
    );
    self.clients.claim();
});

// Fetch: network-first for API, cache-first for assets
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);

    // Skip non-GET requests (e.g. POST, PUT)
    if (request.method !== 'GET') {
        return;
    }

    // Handle API calls with network-first, fallback to cache
    if (url.pathname.startsWith('/api') || url.pathname.startsWith('/getCartItems')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    return response;
                })
                .catch(() => caches.match(request))
        );
        return;
    }

    // For other files, use cache-first
    event.respondWith(
        caches.match(request).then(cached => {
            if (cached) return cached;
            return fetch(request)
                .then(response => {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => cache.put(request, clone));
                    return response;
                })
                .catch(() => {
                    // Offline fallback for HTML pages
                    if (request.headers.get('accept').includes('text/html')) {
                        return caches.match(OFFLINE_URL);
                    }
                });
        })
    );
});
