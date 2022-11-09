"use strict";
/**
 * Shows the search bar or makes a search query.
 */
const search = $("nav input"),

// Search
find = () => {
    const q = search.value.trim().replace(/^(https:\/\/)?aluay/, "");

    if (q) {
        body.classList.remove("search");
        search.value = "";

        // Get URI
        if (q[0] === "/") {
            return get(q.substring(1));
        }

        // Search
        TODO("Search: " + q);
    }
}

// Show/hide search bar
fn["search.s"] = () => {
    if (body.classList.contains("search")) {
        body.classList.remove("search");
        return search.value = "";
    }

    body.classList.add("search");
    search.focus();
};

// Advanced search
fn["search.a"] = () => {
    TODO("Advanced search");
};

fn[F]?.();

// Press enter to search
search.addEventListener("keyup", ev => {
    if (ev.key === "Enter") {
        find();
    }
});
