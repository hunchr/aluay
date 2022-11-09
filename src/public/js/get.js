"use strict";
/**
 * Fetches pages from backend.
 */
let page = $("main");

const main = $("#main"),
      title = $("h1"),
      pages = [],
      notPrivate = location.pathname !== "/private",

// Get page
get = async (path, data = "", isFormData) => {
    return await new Promise(res => {
        const xhr = new XMLHttpRequest();

        xhr.open("POST", "/" + path, true);
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (data) {
                    return res(xhr.response);
                }

                // Create new page
                page.classList.add("hidden");
                page = document.createElement("div");
                page.innerHTML = xhr.response;
                page = page.firstElementChild;

                // Change title
                const meta = page.dataset.meta.split("|");
                title.innerHTML = meta[2] || meta[1];

                if (notPrivate) {
                    document.title = meta[1];
                    history.pushState(null, "", location.origin + meta[0]);
                }
                
                main.appendChild(page);
                pages.push(page);
                body.classList.add("back");
                res();
            }
        };

        // Send data
        if (isFormData) {
            xhr.send(data);
        }
        else {
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("fetch=1&" + data);
        }
    });
},

// Get form data
getData = e => (
    [...page.querySelectorAll(e)].map((e, i) => `&${i}=${e.value}`).join("").substring(1)
);

// Get file
fn["get"] = () => {
    get(self.dataset.n);
};

// Get all pages
pages.push(...document.querySelectorAll("main"));

if (lastIncludedFile === "get") {
    fn["get"]();
}
