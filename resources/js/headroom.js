document.addEventListener('alpine:init', () => {
    Alpine.directive('headroom', (el, {modifiers, expression}, {evaluate, cleanup}) => {
        let lastScrollY = 0;
        let isPinned = true; // Initially pinned (visible)

        // Default options, similar to Headroom.js
        const defaultOptions = {
            offset: 0, // Pixels scrolled down before hiding
            tolerance: 0, // Pixels scrolled up before showing
            classes: {
                initial: 'headroom',
                pinned: 'headroom--pinned',
                unpinned: 'headroom--unpinned',
                top: 'headroom--top',
                notTop: 'headroom--not-top',
                bottom: 'headroom--bottom',
                notBottom: 'headroom--not-bottom',
                frozen: 'headroom--frozen', // Not fully implemented in this basic version, but good for consistency
            },
        };

        let options = {...defaultOptions};

        // Parse expression for options (e.g., x-headroom="{ offset: 100, tolerance: 5 }")
        if (expression) {
            try {
                const userOptions = evaluate(expression);
                options = {
                    ...defaultOptions,
                    ...userOptions,
                    classes: {...defaultOptions.classes, ...(userOptions.classes || {})}
                };
            } catch (e) {
                console.warn('Alpine Headroom Plugin: Invalid options expression', e);
            }
        }

        // Add initial classes
        el.classList.add(options.classes.initial);
        el.classList.add(options.classes.pinned); // Start visible

        const handleScroll = () => {
            const currentScrollY = window.scrollY;
            const scrollDelta = currentScrollY - lastScrollY;
            const isScrollingDown = currentScrollY > lastScrollY;
            const isScrollingUp = currentScrollY < lastScrollY;

            // Update top/not-top classes
            if (currentScrollY <= options.offset) {
                el.classList.remove(options.classes.notTop);
                el.classList.add(options.classes.top);
            } else {
                el.classList.remove(options.classes.top);
                el.classList.add(options.classes.notTop);
            }

            // Update bottom/not-bottom classes (useful for footers)
            const documentHeight = document.body.offsetHeight;
            const viewportHeight = window.innerHeight;
            if ((viewportHeight + currentScrollY) >= documentHeight) {
                el.classList.remove(options.classes.notBottom);
                el.classList.add(options.classes.bottom);
            } else {
                el.classList.remove(options.classes.bottom);
                el.classList.add(options.classes.notBottom);
            }

            // Main headroom logic
            if (isScrollingDown && Math.abs(scrollDelta) > options.tolerance) {
                if (isPinned && currentScrollY > options.offset) {
                    el.classList.remove(options.classes.pinned);
                    el.classList.add(options.classes.unpinned);
                    isPinned = false;
                }
            } else if (isScrollingUp && Math.abs(scrollDelta) > options.tolerance) {
                if (!isPinned) {
                    el.classList.remove(options.classes.unpinned);
                    el.classList.add(options.classes.pinned);
                    isPinned = true;
                }
            }

            lastScrollY = currentScrollY;
        };

        // Attach scroll listener
        window.addEventListener('scroll', handleScroll);

        // Initial call to set correct state on load
        handleScroll();

        // Cleanup function when the element is removed
        cleanup(() => {
            window.removeEventListener('scroll', handleScroll);
            // Remove all added classes to clean up the DOM
            el.classList.remove(
                options.classes.initial,
                options.classes.pinned,
                options.classes.unpinned,
                options.classes.top,
                options.classes.notTop,
                options.classes.bottom,
                options.classes.notBottom
            );
        });
    });
});
