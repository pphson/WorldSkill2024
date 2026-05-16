const CACHE_NAME = 'lyon-cache-v1';
const ASSETS = [
  'index.html',
  'offline.html',
  'css/style.css',
  'css/responsive.css',
  'css/animations.css',
  'js/main.js'
];

self.addEventListener('install', (e) => {
  e.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(ASSETS))
  );
});

self.addEventListener('fetch', (e) => {
  e.respondWith(
    caches.match(e.request).then((cachedResponse) => {
      if (cachedResponse) return cachedResponse;
      return fetch(e.request).catch(() => {
        if (e.request.mode === 'navigate') {
          return caches.match('offline.html');
        }
      });
    })
  );
});