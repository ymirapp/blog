// Activate the deferred fallback only after the featured image actually fails.
document.querySelectorAll('.blog-cover-image').forEach((image) => {
    const showFallback = () => {
        const fallback = image.closest('.blog-cover')?.querySelector('template');

        if (fallback) {
            image.replaceWith(fallback.content);
            fallback.remove();
        } else {
            // Cached older markup already has the fallback beneath the featured image.
            image.remove();
        }
    };

    // A failure can happen before this script runs, so its error event is already gone.
    if (image.complete && !image.naturalWidth) {
        showFallback();
    } else {
        image.addEventListener('error', showFallback, {once: true});
    }
});
