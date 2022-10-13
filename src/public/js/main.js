"use strict";
/**
 * Main script (used everywhere).
 */
let self = document.body,
    f; // Last used function
    
const TODO = msg => console.log(msg),
      wait = async ms => await new Promise(res => setTimeout(res, ms)),
      $ = e => document.querySelector(e),
      head = document.head,
      nonce = head.querySelector("script").nonce,
      body = self,
      scripts = [],
      fn = {},

// Include JavaScript file
include = path => {
    if (!scripts.includes(path)) {
        const script = document.createElement("script");

        script.setAttribute("nonce", nonce);
        script.setAttribute("src", `js/${path}.js`);
    
        head.appendChild(script);
        scripts.push(script);
    }
};

// Event listener for executing functions
document.addEventListener("click", ev => {
    f = ev.target?.dataset.f;

    if (f) {
        self = ev.target;
        fn[f] ? fn[f]() : include(f);
    }
});

// Register service worker
if (location.protocol === "https:" && "serviceWorker" in navigator) {
    navigator.serviceWorker.register("/sw.js");
}
