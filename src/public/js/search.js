"use strict";
/**
 * Shows the search bar or makes a search query.
 */
const search = $("nav input");

// Search
fn[f] = () => {
    if (body.classList.contains("search")) {
        const q = search.value.trim().replace(/^(https:\/\/)?aluay/, "");

        if (q) {
            body.classList.remove("search");
            search.value = "";
    
            // Get URI
            // if (q[0] === "/") {
            //     return get(q.substring(1));
            // }
    
            // Search
            return TODO("Search: " + q);
        }
    }

    // Show search bar if hidden
    body.classList.add("search");
    search.focus();
};

fn[f]();

// Press enter to search
search.addEventListener("keyup", ev => {
    if (ev.key === "Enter") {
        find();
    }
});
