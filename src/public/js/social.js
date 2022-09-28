"use strict";
let createFormData = false;
const create = $("#new"),
      createCat = create.querySelector("textarea"),
      createFiles = create.querySelector("input"),
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
        createFiles.click();
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
            const desc = createCat.value.trim(),
                  hasMedia = !!createFormData;

            // Validate description
            if (desc < 0) {
                return __todo__("popup invalid length"); // TODO: lang
            }

            // // Media post
            // if (createFormData) {
            //     __todo__("Create media post");

            //     createFormData.append("json", JSON.stringify([
            //         "null",
            //         ...[...create.querySelectorAll("#new .toggle")].map(e => {
            //             return !e.classList.contains("false");
            //         }),
            //         desc
            //     ]));

            //     get("api/new", createFormData, true).then(res => {
            //         console.log(res);
            //         // if (res) {
            //         //     showPopup(...res.split("|"));
            //         // }
            //         // else {
            //         //     create.classList.add("hidden");
            //         //     body.classList.remove("freeze");
            //         // }
            //     });
            // }
            // // Text post
            // else {
            //     get("api/new", "new="+JSON.stringify([
            //         "null",
            //         ...[...create.querySelectorAll("#new .toggle")].map(e => {
            //             return !e.classList.contains("false");
            //         }),
            //         desc
            //     ])).then(res => {
            //         if (res) {
            //             showPopup(...res.split("|"));
            //         }
            //         else {
            //             create.classList.add("hidden");
            //             body.classList.remove("freeze");
            //         }
            //     });
            // }

            // Create form data
            if (!hasMedia) {
                createFormData = new FormData();
            }

            // Append data
            createFormData.append("new", JSON.stringify([
                "null",
                ...[...create.querySelectorAll("#new .toggle")].map(e => {
                    return !e.classList.contains("false");
                }),
                desc
            ]));

            // Send post and files to database
            get("api/new", createFormData, hasMedia).then(res => {
                console.log(res);
                // if (res) {
                //     showPopup(...res.split("|"));
                // }
                // else {
                //     create.classList.add("hidden");
                //     body.classList.remove("freeze");
                //     createFormData = false;
                // }
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

// New: Upload files
createFiles.addEventListener("change", ev => {
    const files = ev.target.files,
          len = files.length;

    if (!len) return;

    const fileType = files[0].type.match(/\w+/)[0],
          fileCat = fileType === "image" ? 0 : false,
          mimeTypes = [["png","jpeg"]][fileCat];

    // Validate file count
    if (
        fileCat === false ||
        (fileCat === 0 && len > 1)
    ) {
        return __todo__("ERR_INV_FILE_TYPE_OR_TOO_MANY"); // TODO: lang
    }

    let i = 0;
    createFormData = new FormData();

    // Get files
    for (const file of files) {
        console.log(file);
        const type = file.type.match(/\w+/)[0];

        // Validate file type (e.g. image)
        if (type !== fileType) {
            return __todo__("ERR_INCONSISTENT_FILE_TYPE"); // TODO: lang
        }

        // Validate mime type (e.g. png)
        if (!mimeTypes.includes(file.type.match(/\w+$/)[0])) {
            return __todo__("ERR_UNSUPPORTED_FILE_TYPE"); // TODO: lang
        }

        // Validate file size (max. 10 MB for images)
        if (type === 'image' && file.size > 1.25e6) {
            return __todo__("ERR_FILE_SIZE_EXCEEDED"); // TODO: lang
        }

        createFormData.append(i++, file); // TODO: preview, change order
    }
});
