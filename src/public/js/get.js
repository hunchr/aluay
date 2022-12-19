"use strict";
/**
 * Fetches pages from backend.
 */
let page = $("main");

const main = $("#main"),
      title = $("h1"),
      pages = [page],
      hideURL = !!body.dataset.hideurl,

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
                console.log(xhr.response);
                
                page.classList.add("hidden");
                page = document.createElement("div");
                page.innerHTML = xhr.response;
                page = page.firstElementChild;

                // Change title
                const meta = page.dataset.meta.split("|");
                title.innerHTML = meta[2] || meta[1];

                if (!hideURL) {
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
getFormData = () => (
    [...page.querySelectorAll("[name]")].map(e => `${e.name}=${e.value}&`).join("")
);

// ----- Get file -----
fn["get"] = () => {
    get(self.dataset.n);
};

fn["get.i"] = () => {
    get(self.firstElementChild.innerText);
};

if (lastIncludedFunc === "get") {
    exec();
}
