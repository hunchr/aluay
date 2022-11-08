"use strict";
/**
 * Sends login form data to backend.
 */
// Submit form
fn[f + ".s"] = () => {
    get("login", getData("input")).then(err => {
        if (err) {
            return popup(err);
        }

        pages.pop().remove();
        get((location.search.match(/(?<=^\?next=\/).*/) || ["home"])[0]);
    });
};

// Show password
fn[f + ".v"] = () => {
    const prev = self.previousElementSibling,
          isHidden = prev.type !== "text";
    
    prev.type = isHidden ? "text" : "password";
    self.firstChild.innerHTML = `<use href="#i-visibility${isHidden ? "-off" : ""}"></use>`;
};

include("popup", true);
include("get", true);

fn[F]();
