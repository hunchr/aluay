"use strict";
/**
 * Sends login/signup form data to backend.
 */
include("get", true);
include("dialog", true);

// Submits a form
const submitForm = (name, path, callback) => {
    $$(name, () => {
        get("api/" + path, getFormData()).then(res => {
            res = JSON.parse(res);
            res.status === 200 ? callback() : showDialog(
                page.querySelector(`[name="${res.message}"]`).dataset.err
            );
        });
    });
};

// Submits login form
submitForm("l", "login", () => {
    pages.pop().remove();
    get("home");
});

// Submits signup form
submitForm("s", "signup", () => {
    page.querySelector("div:first-child").remove();
    page.querySelector("div").classList.remove("hidden");
});

// Submits email verification code
submitForm("e", "signup", () => {
    pages.pop().remove();
    get("login");
});

// Resends email verification code
$$("r", () => {
    TODO("Resend email verification");
});

// Shows username info
$$("i", () => {
    showDialog(self.dataset.info);
});

// Shows/hides password
$$("v", () => {
    const isVisible = self.innerText === "visibility";

    self.previousElementSibling.type = isVisible ? "text" : "password";
    self.firstElementChild.innerHTML = isVisible ? "visibility_off" : "visibility";
});

wait(100).then(() => exec());
