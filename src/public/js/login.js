"use strict";
/**
 * Sends login form data to backend.
 */
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

include("popup");
include("get");

fn["login"]();
