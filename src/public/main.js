"use strict";
/**
 * Main script (used everywhere).
 */
let self = document.body,
    F, // Last used function
    lastIncludedFile;
    
const TODO = msg => console.log(msg),
      wait = async ms => await new Promise(res => setTimeout(res, ms)),
      $ = e => document.querySelector(e),
      head = document.head,
      nonce = head.querySelector("script").nonce,
      body = self,
      nav = $("nav"),
      scripts = [],
      fn = {},

// Include JavaScript file
include = (path, noOverwrite) => {    
    if (!noOverwrite) {
        F = path;
    }

    lastIncludedFile = path.replace(/\..+/, "");

    if (!scripts.includes(lastIncludedFile)) {
        const script = document.createElement("script");

        script.setAttribute("nonce", nonce);
        script.setAttribute("src", `js/${lastIncludedFile}.js`);
    
        head.appendChild(script);
        scripts.push(lastIncludedFile);
    }
};

// Event listener for executing functions
document.addEventListener("click", ev => {
    lastIncludedFile = ev.target?.dataset.f;

    if (lastIncludedFile) {
        self = ev.target;
        fn[lastIncludedFile] ? fn[lastIncludedFile]() : include(lastIncludedFile);
    }
});

// Register service worker
if (location.protocol === "https:" && "serviceWorker" in navigator) {
    //* navigator.serviceWorker.register("/sw.js");
}
