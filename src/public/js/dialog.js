"use strict";
/**
 * Shows a dialog.
 */
let popupX = () => fn["dialog.o"];
const dialog = document.createElement("dialog");

dialog.id = "dialog";
dialog.innerHTML =
`<div class="li">
    <h2></h2><p></p>
    <div>
        <button class="a" data-f="dialog.x"></button>
        <button class="a" data-f="dialog.o"></button>
    </div>
</div>`;

nav.appendChild(dialog);

const dialogElems = [...document.querySelectorAll("#dialog h2,#dialog p,#dialog .a")],

// Show dialog for info/warnings
showDialog = msg => {
    msg = msg.split("|");

    dialogElems.forEach((e, i) => {
        e.innerHTML = msg[i];
    });

    // Show/hide cancel button
    if (msg[3]) {
        dialogElems[3].classList.remove("hidden");
    }
    else {
        dialogElems[3].classList.add("hidden");
    }

    dialog.showModal();
    dialogElems[3].focus(); // TODO: Replace with something better that actually works
};

// Dialog: Cancel/Exit
fn["dialog.x"] = () => {
    popupX();
};

// Dialog: Close/Okay
fn["dialog.o"] = () => {
    dialog.close();
};
