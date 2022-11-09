"use strict";
/**
 * Sends signup form data to backend.
 */
include("get", true);
include("dialog", true);
include("login", true);

// Submit form
fn["signup.s"] = () => {
    get("signup", getData("div:first-child input")).then(err => {
        if (err) {
            return showDialog(err);
        }

        page.querySelector("div:first-child").remove();
        page.querySelector("div").classList.remove("hidden");
    });
};

// Send email verification
fn["signup.e"] = () => {
    get("signup", getData("input")).then(err => {
        if (err) {
            return showDialog(err);
        }

        pages.pop().remove();
        get("login");
    });
};

// Resend email verification
fn["signup.r"] = () => {
    TODO("Resend email verification");
};

// Show usename info
fn["signup.i"] = () => {
    showDialog(self.dataset.info);
    // TODO("Show usename info");
};
