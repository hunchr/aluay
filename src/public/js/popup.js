"use strict";
/**
 * Shows a popup.
 */
let popupX = () => fn[f + ".o"];

const popupCont = document.createElement("div");

popupCont.id = "popup";
popupCont.className = "popup hidden";
popupCont.innerHTML =
`<div class="li">
    <h2></h2><p></p>
    <div>
        <button class="a" data-f="popup.x"></button>
        <button class="a" data-f="popup.o"></button>
    </div>
</div>`;

nav.appendChild(popupCont);

const popupElems = [
    $("#popup h2"),
    $("#popup p"),
    $("#popup .a:last-child"),
    $("#popup .a")
],

// Show popup for info/warnings
popup = msg => {
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
    popupCont.classList.remove("hidden");
    popupCont.focus();
};

// Popup: Cancel/Exit
fn[f + ".x"] = () => {
    popupX();
};

// Popup: Continue/Okay
fn[f + ".o"] = () => {
    popupCont.classList.add("hidden");
    body.classList.remove("freeze");
};
