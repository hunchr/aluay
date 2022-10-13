"use strict";
/**
 * Fetches pages from backend.
 */
let layer;

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
                layer?.classList.add("hidden");
                
                // Create new layer
                layer = document.createElement("div");
                layer.innerHTML = xhr.response;
                layer = layer.firstElementChild;

                // Change title
                document.title = title.innerHTML = layer.dataset.title;
                history.pushState(null, "", "https://aluay/" + layer.dataset.url);

                main.appendChild(layer);
                layers.push(layer);
                console.log("layer", layer);
                res();
            }
        };

        // Send data
        if (isFormData) {
            xhr.send(data);
        }
        else {
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("is_fetch=1&" + data);
        }
    });
},

// Get form data
getData = e => (
    [...layer.querySelectorAll(e)].map((e, i) => `${i}=${e.value}&`).join("")
);

fn[f] = () => {
    get(self.dataset.n);
};

fn[f]();
