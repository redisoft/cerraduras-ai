const CACHE_NAME = 'cerraduras-pos-v1';
const OFFLINE_URLS = [
  '/',
  '/ventas',
  '/ventas/puntoVenta/0',
  '/manifest.json',
  '/css/adm/menuBarra.css',
  '/js/ventas/offlineIndicator.js',
  '/js/ventas/offlineSales.js',
  '/js/installPrompt.js'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(OFFLINE_URLS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  const request = event.request;
  if(request.method !== 'GET') {
    event.respondWith(fetch(request));
    return;
  }

  event.respondWith(
    caches.match(request).then(response => {
      const fetchPromise = fetch(request).then(networkResponse => {
        if(networkResponse && networkResponse.status === 200 && networkResponse.type === 'basic') {
          const responseClone = networkResponse.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(request, responseClone));
        }
        return networkResponse;
      }).catch(() => response || caches.match('/'));

      return response || fetchPromise;
    }).catch(() => fetch(request))
  );
});

self.addEventListener('sync', event => {
  if(event.tag === 'sync-ventas') {
    event.waitUntil(self.clients.matchAll({ includeUncontrolled: true, type: 'window' }).then(clients => {
      clients.forEach(client => client.postMessage({ type: 'SYNC_VENTAS'}));
    }));
  }
});
