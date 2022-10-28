"use strict";
/**
 * Fetches pages from backend.
 */
let layer = $("main");

const main = $("#main"),
      title = $("h1"),
      layers = [],

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

                // Hide old layer
                layer.classList.add("hidden");
                
                // Create new layer
                layer = document.createElement("div");
                layer.innerHTML = xhr.response;
                layer = layer.firstElementChild;

                // Change title
                const meta = layer.dataset.meta.split("|");
                document.title = meta[1];
                title.innerHTML = meta[2] || meta[1];
                history.pushState(null, "", "https://aluay" + meta[0]);

                main.appendChild(layer);
                layers.push(layer);
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
    [...layer.querySelectorAll(e)].map((e, i) => `${i}=${e.value}&`).join("")
);

fn[f] = () => {
    console.log(`get(${self.dataset.n})`);
    get(self.dataset.n);
};

// fn[fL]();
