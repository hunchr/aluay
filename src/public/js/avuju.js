/**
 * A Very Useful JavaScript Utility.
 */
const $ = (() => {
    const self = selector => {
        const e = document.querySelector(selector);

        return !e ? null : {
            e,
            /**
             * Gets or sets the content.
             * @param {string} [content] - content to set
             */
            ct(content) {
                if (content) {
                    e.innerHTML === undefined ? e.value = content : e.innerHTML = content 
                    return this;
                }
                return e.innerHTML || e.value;
            },
            /**
             * Checks if the element has the given class (after optionally toggling it).
             * @param {string} cls - class name
             * @param {boolean} [toggle] - if class is set remove it, otherwise add it
             */
            has(cls, toggle) {
                if (toggle) {
                    e.classList.toggle(cls);
                }
                return e.classList.contains(cls);
            },
            /**
             * Adds one or multiple classes.
             * @param {string} cls - class name
             */
            add(...cls) {
                cls.forEach(name => e.classList.add(name));
                return this;
            },
            /**
             * Removes one or multiple classes.
             * @param {string} cls - class name
             */
            rem(...cls) {
                cls.forEach(name => e.classList.remove(name));
                return this;
            },
            /**
             * Adds an event listener.
             * @param {string} event - event name
             * @param {Function} callback - function to call
             */
            on(event, callback) {
                e.addEventListener(event, callback);
                return this;
            },
            /**
             * Removes an event listener.
             * @param {string} event - event name
             * @param {Function} callback - function to remove
             */
            off(event, callback) {
                e.removeEventListener(event, callback);
                return this;
            },
            /**
             * Gets or sets a dataset attribute.
             * @param {string} key - dataset key
             * @param {string} [value] - dataset value
             */
            dt(key, value) {
                if (value) {
                    e.dataset[key] = value;
                    return this;
                }
                return e.dataset[key];
            },
            /**
             * Gets one or adds one or multiple CSS properties.
             * @param {string|Object} key - property or style object
             * @param {string} [value] - property value
             */
            css(key, value) {
                if (value) {
                    e.style[key] = value;
                }
                else {
                    if (typeof key === "string") {
                        return e.style[key] || undefined; 
                    }
                    const style = Object.entries(key);
                    for (const [key, value] of style) {
                        e.style[key] = value;
                    }
                }
                return this;
            },
        };
    };

    /**
     * Waits a certain amount of time.
     * @param {number} ms - time in milliseconds
     */
    self.wait = async ms => {
        await new Promise(res => setTimeout(res, ms));
    };

    return self;
})();
