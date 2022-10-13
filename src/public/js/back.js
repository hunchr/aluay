"use strict";
/**
 * Shows the startpage, deletes the search query, or acts as 'go back' button
 */
fn[f] = () => {
    // Go back
    if (body.classList.contains("back")) {
        return TODO("Back");
    }

    // Cancel search
    if (body.classList.contains("search")) {
        body.classList.remove("search");
        return search.value = "";
    }

    // Go to start page
    get("");
};

fn[f]();
