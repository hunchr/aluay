"use strict";
/**
 * Sends signup form data to backend.
 */
// Submit form
fn[f+ ".s"] = () => {
    get("signup", getData("div:first-child input")).then(err => {
        if (err) {
            return popup(err);
        }

        layer.querySelector("div:first-child").remove();
        layer.querySelector("div").classList.remove("hidden");
    });
};

// Send email verification
fn[f + ".e"] = () => {
    get("signup", getData("input")).then(err => {
        if (err) {
            return popup(err);
        }

        layers.pop().remove();
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

include("login", true);
