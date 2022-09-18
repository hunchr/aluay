"use strict";
HTMLElement.prototype.$ = function(e) {
    return this.querySelector(e);
}

let self,
    layer = document.querySelector("#main main"),
    popupCancel = () => {};

const __todo__ = msg => console.warn(msg),
      wait = async ms => await new Promise(res => setTimeout(res, ms)),
      $ = e => document.querySelector(e),
      body = document.body,
      main = $("#main"),
      title = $("h1"),
      side = $("#side"),
      popup = $("#popup"),
      search = $("nav input"),
      layers = [layer],

popupElems = [
    $("#popup h2"),
    $("#popup p"),
    $("#popup div :last-child"),
    $("#popup .red")
],

// Shows a popup for warnings or info
showPopup = (...msg) => {
    popupElems.forEach((e, i) => {
        e.innerHTML = msg[i];
    });

    // Show/hide cancel button
    if (msg[3]) {
        popupElems[3].classList.remove("hidden");
    }
    else {
        popupElems[3].classList.add("hidden");
    }
    
    popup.classList.remove("hidden");
    popup.focus();
},

// Search
find = () => {
    const q = search.value.trim();

    if (q) {
        body.classList.remove("search");
        search.value = "";
        __todo__("search: " + q);
    }
},

// Posts data to database
set = (fileName, data) => {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/api/" + fileName, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.response); // TODO: remove
        }
    };
    xhr.send(data);
},

// Fetches data from database
get = async (path, data) => {
    return await new Promise(res => {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/" + path, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4 && xhr.status === 200) {
                if (data) {
                    return res(xhr.response);
                }

                // --- Hide old layer ---
                layer?.classList.remove("vis");
                
                // --- Create new layer ---
                layer = document.createElement("div");
                layer.innerHTML = xhr.response;
                layer = layer.firstElementChild;
                layer.classList.add("vis");

                // Change title
                document.title = title.innerHTML = layer.dataset.title;

                main.appendChild(layer);
                layers.push(layer);
                res();
            }
        };
        xhr.send("is_fetch=1&" + (data || ""));
    });
},

// Get form data
getData = e => (
    [...layer.querySelectorAll(e)].map((e, i) => `${i}=${e.value}&`).join("")
),

// Global functions
fn = {
    _: {
        // Fetch data
        _: () => {
            get(self.dataset.n);
        },
        // ----- Nav -----
        // Logo button
        A: () => {
            // Cancel search
            if (body.classList.contains("search")) {
                body.classList.remove("search");
                search.value = "";
            }
            // Back
            else if (body.classList.contains("back")) {
                __todo__("Back");
            }
            // Start page
            else {
                get("");
            }
        },
        // Search
        B: () => {
            if (body.classList.contains("search")) {
                find();
            }
            else {
                body.classList.add("search");
                search.focus();
            }
        },
        // Menu
        C: () => {
            side.classList.toggle("hidden");
        },
        // ----- Popup -----
        // Continue (Okay)
        D: () => {
            popup.classList.add("hidden");

        },
        // Cancel
        E: () => {
            popupCancel();
            popup.classList.add("hidden");
        },
        // ----- Auth -----
        // Log in
        a: () => {
            get("login", getData("input")).then(res => {
                if (res) {
                    showPopup(...res.split("|"));
                }
                else {
                    layers.pop().remove();
                    fn._._("home");
                }
            });
        },
        // Sign up
        b: () => {
            get("signup", getData(".form>:first-child input")).then(res => {
                if (res) {
                    showPopup(...res.split("|"));
                }
                else {
                    layer.$(".form>:first-child").remove();
                    layer.$(".form>:last-child").classList.remove("hidden");
                }
            });
        },
        // Verify email
        c: () => {
            get("signup", getData(".form input")).then(res => {
                if (res) {
                    showPopup(...res.split("|"));
                }
                else {
                    layers.pop().remove();
                    fn._._("login");
                }
            });
        },
        // Send verification code again
        d: () => {
            __todo__("Send verification code again");
        }
    }
};

// Event listener
document.addEventListener("click", ev => {
    const f = ev.target?.dataset.f;

    if (f) {
        self = ev.target;
        fn[f[0]][f[1]]();
    }
});

// Search
search?.addEventListener("keyup", ev => {
    if (ev.key === "Enter") {
        find();
    }
});

// Register service worker
if (location.protocol === "https:" && "serviceWorker" in navigator) {
    navigator.serviceWorker.register("/sw.js");
}
