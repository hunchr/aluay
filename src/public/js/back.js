"use strict";
/**
 * Shows the startpage or acts as 'go back' button
 */
fn[f] = () => {
    // Go back
    if (body.classList.contains("back")) {
        return TODO("Back");
    }

    // Go to start page
    get("");
};

fn[f]();
