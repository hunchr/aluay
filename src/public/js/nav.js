"use strict";
/**
 * Functions for nav buttons (sidebar, acc, back).
 */
include("get", true);

const acc = $("#acc");

// Shows/hides the sidebar
$$("s", () => {
    body.classList.toggle("side");
});

// Shows/hides the account menu
$$("a", () => {
    acc.classList.toggle("hidden");
});

// Go back
$$("b", () => {
    // Go to start page
    if (!body.classList.contains("back")) {
        return get("");
    }

    // ----- Go back -----
    pages.pop().remove();
    page = pages.at(-1);
    page.classList.remove("hidden");

    // Change title
    if (!hideURL) {
        const meta = page.dataset.meta.split("|");
        title.innerHTML = meta[2] || meta[1];
        document.title = meta[1];
        history.pushState(null, "", location.origin + meta[0]);
        // TODO: fix history.back();
    }

    if (pages.length === 1) {
        body.classList.remove("back");
    }
});

exec();
