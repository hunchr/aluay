"use strict";
/**
 * Sends signup form data to backend.
 */
include("get");
include("popup");

// Signup
fn[f] = () => {
    get("signup", getData("div:first-child input")).then(err => {
        if (err) {
            showPopup(...err.split("|"));
        }
        else {
            layer.querySelector("div:first-child").remove();
            layer.querySelector("div").classList.remove("hidden");
        }
    });
};

// Email verification
fn["signup.v"] = () => {
    get("signup", getData("input")).then(err => {
        if (err) {
            showPopup(...err.split("|"));
        }
        else {
            layers.pop().remove();
            get("login");
        }
    });
};

// Resend email verification
fn["signup.r"] = () => {
    TODO("Resend email verification");
};

fn[f]();
