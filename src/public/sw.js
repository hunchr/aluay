const v = 1,
urls = [
    "/manifest.json",
    "/sw.js",
    "/js/_main.js",
    "/css/_main.css",
    "/css/social.css",
    "/img/favicon.svg",
    "/font/roboto.woff2",
    "/font/material-icons.woff2"
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
