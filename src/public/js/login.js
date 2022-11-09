"use strict";
/**
 * Sends login form data to backend.
 */
include("dialog", true);
include("get", true);

// Submit form
fn["login.s"] = () => {
    get("login", getData("input")).then(err => {
        if (err) {
            return showDialog(err);
        }

        pages.pop().remove();
        get((location.search.match(/(?<=^\?next=\/).*/) || ["home"])[0]);
    });
};

// Show password
fn["login.v"] = () => {
    const prev = self.previousElementSibling,
          isHidden = prev.type !== "text";
    
    prev.type = isHidden ? "text" : "password";
    self.firstChild.innerHTML = `<use href="#i-visibility${isHidden ? "-off" : ""}"></use>`;
};

fn[F]();
