"use strict";
const create = $("#new"),
      createCat = create.querySelector("textarea"),
      postDate = $("#tmp :first-child").innerText;

// Social media functions
fn.s = {
    // ----- New -----
    // Popup
    A: () => {
        create.classList.remove("hidden");
        body.classList.add("freeze");
        createCat.focus();
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

            get("api/new", "new="+JSON.stringify([
                "null",
                ...[...create.querySelectorAll("#new .toggle")].map(e => {
                    return !e.classList.contains("false");
                }),
                desc
            ])).then(res => {
                if (res) {
                    showPopup(...res.split("|"));
                }
                else {
                    create.classList.add("hidden");
                    body.classList.remove("freeze");
                }
            });
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




        // const btn = ev.target,
        //       likes = Number(btn.innerText);

        // btn.classList.toggle("a");

        // if (!isNaN(likes)) {
        //     btn.innerHTML = btn.innerHTML.replace(/\d+$/, () => likes + (btn.classList == "a" ? 1 : -1));
        // }








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
