"use strict";
/**
 * Main script (used everywhere).
 */
let self = document.body,
    lastIncludedFunc,
    lastIncludedFile;
    
const TODO = msg => console.log(msg),
      wait = async ms => await new Promise(res => setTimeout(res, ms)),
      $ = e => document.querySelector(e),
      $$ = (func, callback) => fn[lastIncludedFile + "." + func] = callback,
      exec = () => fn[lastIncludedFunc](),
      head = document.head,
      nonce = head.querySelector("script").nonce,
      body = self,
      scripts = [],
      fn = {},

// Include JavaScript file
include = (path, noOverwrite) => {   
    const filePath = path.replace(/\..+/, "");

    if (!noOverwrite) {
        lastIncludedFunc = path;
        lastIncludedFile = filePath;
    }

    if (!scripts.includes(filePath)) {
        const script = document.createElement("script");

        script.setAttribute("nonce", nonce);
        script.setAttribute("src", `js/${filePath}.js`);
    
        head.appendChild(script);
        scripts.push(filePath);
    }
};

// Event listener for executing functions
document.addEventListener("click", ev => {
    lastIncludedFile = ev.target?.dataset.f;

    if (lastIncludedFile) {
        self = ev.target;
        console.log(lastIncludedFile);
        fn[lastIncludedFile] ? fn[lastIncludedFile]() : include(lastIncludedFile);
    }
});

// Register service worker
if (location.protocol === "https:" && "serviceWorker" in navigator) {
    //* navigator.serviceWorker.register("/sw.js");
}
