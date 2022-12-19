"use strict";
/**
 * Shows a dialog.
 */
const nav = $("nav"),
      dialog = document.createElement("dialog"),
      dialogElems = [],

// Shows dialog for info/warnings
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
    dialogElems[2].focus();
};

dialog.innerHTML =
`<div class="li">
    <h2></h2><p></p>
    <div>
        <button class="a" data-f="dialog.x"></button>
        <button class="a" data-f="dialog.o"></button>
    </div>
</div>`;

nav.appendChild(dialog);
dialogElems.push(...document.querySelectorAll("dialog h2,dialog p,dialog .a:last-child,dialog .a"));

// Buttons: Okay/Cancel
fn["dialog.o"] = fn["dialog.x"] = () => dialog.close();
