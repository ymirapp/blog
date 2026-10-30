// Close the mobile menu with Escape (returning focus to its toggle) or when focus leaves it.
document.querySelectorAll('.site-menu').forEach((menu) => {
    menu.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape' || !menu.open) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        menu.open = false;
        menu.querySelector('summary').focus();
    });

    menu.addEventListener('focusout', (event) => {
        if (!menu.contains(event.relatedTarget)) {
            menu.open = false;
        }
    });
});
