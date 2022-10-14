"use strict";
/**
 * Shows a popup.
 */
let popupX = () => {};

const popup = document.createElement("div");

popup.id = "popup";
popup.className = "popup hidden";
popup.innerHTML =
`<div class="btns">
    <h2></h2><p></p>
    <button class="a upper red" data-f="popup.x"></button>
    <button class="a upper" data-f="popup.o"></button>
</div>`;

nav.appendChild(popup);

const popupElems = [
    $("#popup h2"),
    $("#popup p"),
    $("#popup .a:last-child"),
    $("#popup .a")
],

// Show popup for info/warnings
showPopup = msg => {
    msg = msg.split("|");

    popupElems.forEach((e, i) => {
        e.innerHTML = msg[i];
    });

    // Show/hide cancel button
    if (msg[3]) {
        popupElems[3].classList.remove("hidden");
    }
    else {
        popupElems[3].classList.add("hidden");
    }
    
    body.classList.add("freeze");
    popup.classList.remove("hidden");
    popup.focus();
};

// Popup: Cancel/Exit
fn["popup.x"] = () => {
    popupX();
};

// Popup: Continue/Okay
fn["popup.o"] = () => {
    popup.classList.add("hidden");
    body.classList.remove("freeze");
};
