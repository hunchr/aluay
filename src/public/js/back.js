"use strict";
/**
 * Shows the startpage or acts as 'go back' button
 */
fn["back"] = () => {
    // Go to start page
    if (!body.classList.contains("back")) {
        return get("");
    }

    // ----- Go back -----
    pages.pop().remove();
    page = pages.at(-1);
    page.classList.remove("hidden");

    // Change title
    if (notPrivate) {
        const meta = page.dataset.meta.split("|");
        title.innerHTML = meta[2] || meta[1];
        document.title = meta[1];
        history.pushState(null, "", location.origin + meta[0]);
        // FIXME: history.back();
    }

    if (pages.length === 1) {
        body.classList.remove("back");
    }
};

fn["back"]();
