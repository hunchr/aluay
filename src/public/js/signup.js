"use strict";
/**
 * Sends signup form data to backend.
 */
// Submit form
fn[f+ ".s"] = () => {
    get("signup", getData("div:first-child input")).then(err => {
        console.log(err);
        if (err) {
            return popup(err);
        }

        page.querySelector("div:first-child").remove();
        page.querySelector("div").classList.remove("hidden");
    });
};

// Send email verification
fn[f + ".e"] = () => {
    get("signup", getData("input")).then(err => {
        if (err) {
            return popup(err);
        }

        pages.pop().remove();
        get("login");
    });
};

// Resend email verification
fn[f + ".r"] = () => {
    TODO("Resend email verification");
};

// Show usename info
fn[f + ".i"] = () => {
    TODO("Show usename info");
};

include("get", true);
include("login", true);
