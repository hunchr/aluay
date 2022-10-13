"use strict";
/**
 * Sends login form data to backend.
 */
include("get");
include("popup");

fn[f] = () => {
    get("login", getData("input")).then(err => {
        if (err) {
            showPopup(...err.split("|"));
        }
        else {
            layers.pop().remove();
            get("home");
        }
    });
};

fn[f]();
