// var CACHE_NAME = 'om-racun-pos-laundry';
// var urlsToCache = [
//   '/assets/css/bootstrap.css',
//   '/assets/css/bootstrap-extended.css',
//   '/assets/css/colors.css',
//   '/assets/css/components.css',
//   '/assets/css/themes/dark-layout.css',
//   '/assets/css/perfect-scrollbar.min.css',
//   '/assets/css/core/menu/menu-types/vertical-menu.css',
//   '/assets/kasir/css/bootstrap.min.css',
//   '/assets/kasir/js/bootstrap.bundle.min.js',
//   '/assets/fonts/boxicons/css/boxicons.min.css',
//   '/assets/vendors/css/extensions/sweetalert2.min.css',
//   '/assets/vendors/js/extensions/sweetalert2.all.min.js',
//   '/assets/js/moment.min.js',
//   '/assets/js/moment-id.min.js',
//   '/assets/js/core/libraries/jquery.min.js',
//   '/assets/js/popper.min.js',
//   '/assets/js/core/libraries/bootstrap.min.js',
//   '/assets/js/om-grid.js',
//   '/assets/js/perfect-scrollbar.min.js',
//   '/assets/js/core/app-menu.js',
//   '/assets/js/core/app.js',
//   '/assets/js/scripts/components.js',
//   '/assets/js/scripts/footer.js',
//   '/assets/js/vue.js',
//   '/assets/js/vue.min.js',
//   '/assets/js/accounting.umd.js',
//   '/assets/js/vue-numeric.min.js',
//   '/assets/vendors/js/ui/unison.min.js',
// ];

// self.addEventListener('install', function(event) {
//   // Perform install steps
//   event.waitUntil(
//     caches.open(CACHE_NAME)
//       .then(function(cache) {
//         console.log('Opened cache');
//         return cache.addAll(urlsToCache);
//       })
//   );
// });

// self.addEventListener('fetch', function(event) {
//   event.respondWith(
//     caches.match(event.request)
//       .then(function(response) {
//         // Cache hit - return response
//         if (response) {
//           if(response.redirected !== undefined && response.redirected === true){
//             response = cleanResponse(response)
//           }
//           return response;
//         }
//         return fetch(event.request);
//       }
//     )
//   );
// });

// function cleanResponse(response) {
//   const clonedResponse = response.clone();

//   // Not all browsers support the Response.body stream, so fall back to reading
//   // the entire body into memory as a blob.
//   const bodyPromise = 'body' in clonedResponse ?
//     Promise.resolve(clonedResponse.body) :
//     clonedResponse.blob();

//   return bodyPromise.then((body) => {
//     // new Response() is happy when passed either a stream or a Blob.
//     return new Response(body, {
//       headers: clonedResponse.headers,
//       status: clonedResponse.status,
//       statusText: clonedResponse.statusText,
//     });
//   });
// }

importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.0.2/workbox-sw.js');


workbox.routing.registerRoute(
  // Cache style resources, i.e. CSS files.
  ({request}) => request.destination === 'style',
  // Use cache but update in the background.
  new workbox.strategies.StaleWhileRevalidate({
    // Use a custom cache name.
    cacheName: 'css-cache',
  })
);

workbox.routing.registerRoute(
  ({request}) => request.destination === 'script',
  new workbox.strategies.StaleWhileRevalidate({
    cacheName: 'script-cache',
  })
);

workbox.routing.registerRoute(
  ({request}) => request.destination === 'font',
  new workbox.strategies.StaleWhileRevalidate({
    cacheName: 'font-cache',
  })
);

workbox.routing.registerRoute(
  ({request}) => request.destination === 'image',
  new workbox.strategies.NetworkFirst({
    cacheName: 'image-cache',
  })
);
