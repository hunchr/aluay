const v = 2,
urls = [
    "/manifest.json",
    "/css/main.css",
    "/css/social.css",
    "/main.js",
    "/sw.js",
    "/font/roboto-latin.woff2"
];

self.addEventListener("install", ev => {
    ev.waitUntil(
        caches.open("v" + v).then(async cache => {
            await cache.addAll(urls.map(url => url + "?v" + v));
        })
    );
});

self.addEventListener("fetch", ev => {
    ev.respondWith(
        caches.match(ev.request).then(res => {
            return res ? res : fetch(ev.request);
        })
    );
});
