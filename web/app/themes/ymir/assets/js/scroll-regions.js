// Let keyboard users focus and scroll article code blocks and tables, which scroll horizontally when wide.
// Keeps tabindex, roles and accessible names the author already provided, and hidden content out of the tab order.
document.querySelectorAll('.entry-content pre, .entry-content table').forEach((region) => {
    const role = (region.getAttribute('role') || '').trim().split(/\s+/)[0];
    const isPresentational = 'presentation' === role || 'none' === role;

    if (region.closest('[aria-hidden="true"], [inert]')) {
        return;
    }

    if ('TABLE' === region.tagName) {
        // Tables scroll inside a focusable wrapper so the table element keeps its native display, role and attributes.
        const wrapper = document.createElement('div');

        wrapper.className = 'table-scroll';
        wrapper.setAttribute('tabindex', '0');
        wrapper.setAttribute('role', 'region');
        wrapper.setAttribute('aria-label', isPresentational ? 'Scrollable content' : 'Data table');
        region.before(wrapper);
        wrapper.append(region);

        return;
    }

    // Focus or a label would override an author's presentational role.
    if (isPresentational) {
        return;
    }

    if (!region.hasAttribute('tabindex')) {
        region.setAttribute('tabindex', '0');
    }

    if (!region.hasAttribute('role')) {
        region.setAttribute('role', 'region');
    }

    if (!region.hasAttribute('aria-label') && !region.hasAttribute('aria-labelledby')) {
        region.setAttribute('aria-label', 'Code example');
    }
});
