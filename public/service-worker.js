// WINKY STORE Service Worker - P Production Ready
const CACHE_NAME = 'winky-store-v1';
const PRECACHE_URLS = [
  '/',
  '/products',
  '/products/{product:slug}',
  '/cart',
  '/checkout',
  '/account',
  '/flash-sale',
  '/about',
  '/privacy',
  '/terms',
  '/returns-policy'
];

// Install service worker and cache essential files
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(PRECACHE_URLS))
      .then(() => self.skipWaiting())
  );
});

// Remove old caches and activate the service worker
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keyList) => {
      return Promise.all(keyList.map((key) => {
        if (key !== CACHE_NAME) {
          return caches.delete(key);
        }
      }));
    })
    .then(() => self.clients.claim())
  );
});

// Fetch strategy: Cache first for navigation, cache first for images, network first for API
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    event.respondWith(fetch(event.request));
    return;
  }

  // API routes - network first
  if (url.pathname.startsWith('/api/') || url.pathname.startsWith('/payment/') || url.pathname.startsWith('/payment/webhook')) {
    event.respondWith(
      fetch(event.request).catch(() => {
        return new Response('Network error', { status: 503, statusText: 'Service Unavailable' });
      })
    );
    return;
  }

  // Navigation and page requests - cache first, then network
  if (url.pathname === '/' || url.pathname.startsWith('/products') || url.pathname.startsWith('/cart') || url.pathname.startsWith('/account/') || url.pathname.startsWith('/account/rewards') || url.pathname.startsWith('/account/referrals') || url.pathname.startsWith('/affiliate') || url.pathname.startsWith('/flash-sale') || url.pathname === '/about' || url.pathname === '/privacy' || url.pathname === '/terms' || url.pathname === '/returns-policy') {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request).then((networkResponse) => {
          // Clone the response and cache it
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
          return networkResponse;
        });
      })
    );
    return;
  }

  // Image assets - cache first with 30 day max age
  if (url.pathname.match(/\.(png|jpg|jpeg|svg|webp|gif)$/)) {
    event.respondWith(
      caches.match(event.request).then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request).then((networkResponse) => {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
          return networkResponse;
        });
      })
    );
    return;
  }

  // Fallback to network for everything else
  event.respondWith(fetch(event.request).catch(() => {
    // Return offline page for navigation requests
    if (event.request.mode === 'navigate' || (event.request.method === 'GET' && url.pathname.startsWith('/'))) {
      return caches.match('/offline');
    }
    return new Response('Offline', { status: 503, statusText: 'Offline' });
  }));
});

// Listen for messages from the page
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});