"use strict";
const create = $("#new"),
      createCat = create.querySelector("textarea"),
      postDate = $("#tmp :first-child").innerText;

// Social media functions
fn.s = {
    // ----- New -----
    // Popup
    A: () => {
        __todo__("Popup");
    },
    // Upload files
    B: () => {
        __todo__("Upload files");
    },
    // Poll
    C: () => {
        __todo__("Poll");
    },
    // Create
    D: () => {
        const category = create.querySelector(".list").dataset.v;

        // Create post
        if (category === "0") {
            const desc = createCat.value.trim();

            if (desc < 0) {
                return __todo__("popup invalid length");
            }

            console.log(
                desc,
                ...[...create.querySelectorAll("#new .toggle")].map(e => {
                    return !e.classList.contains("false");
                })
            );

            return __todo__("Create post");
        }
        // Create list
        if (category === "1") {
            return __todo__("Create list");
        }
        // Create community
        if (category === "2") {
            return __todo__("Create community");
        }
    },
    // ----- Profile -----
    // Follow/Unfollow
    a: () => {
        __todo__("Follow/Unfollow");
    },
    // More
    b: () => {
        __todo__("More");
    },
    // ----- Posts -----
    // Username/Community
    c: () => {
        __todo__(self.innerText);
    },
    // Date
    d: () => {
        let date = self.dataset.unix;
        date = {
            "$1": date,
            "$2": new Date(Number(date) * 1e3).toUTCString()
        };

        showPopup(...postDate.replace(/\$\d/g, v => date[v]).split("|"));
    },
    // More
    e: () => {
        __todo__("More");
    },
    // Like/Unlike
    f: () => {
        self.classList.toggle("liked");

        get("like/p", "pid=" + self.parentNode.dataset.id).then(data => {
            console.log(data);
        });
    },
    // Show replies
    g: () => {
        __todo__("Show replies");
    },
    // Save
    h: () => {
        __todo__("Save");
    },
    // Reply
    i: () => {
        __todo__("Reply");
    },
};
