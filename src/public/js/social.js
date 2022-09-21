"use strict";
const postDate = $("#tmp :first-child").innerText;

// Social media functions
fn.p = {
    // ----- Profile -----
    // Follow/Unfollow
    A: () => {
        __todo__("Follow/Unfollow");
    },
    // More
    B: () => {
        __todo__("More");
    },
    // ----- Posts -----
    // Username/Community
    a: () => {
        __todo__(self.innerText);
    },
    // Date
    b: () => {
        let date = self.dataset.unix;
        date = {
            "$1": date,
            "$2": new Date(Number(date) * 1e3).toUTCString()
        };

        showPopup(...postDate.replace(/\$\d/g, v => date[v]).split("|"));
    },
    // More
    c: () => {
        __todo__("More");
    },
    // Like/Unlike
    d: () => {
        self.classList.toggle("liked");

        __todo__("Like/Unlike");
        console.log(self.parentNode.dataset.id);
    },
    // Show replies
    e: () => {
        __todo__("Show replies");
    },
    // Save
    f: () => {
        __todo__("Save");
    },
    // Reply
    g: () => {
        __todo__("Reply");
    }
};
