// Remove featured images that fail to load so the fallback illustration beneath them shows.
document.querySelectorAll('.blog-cover-image').forEach((image) => {
    // A failure can happen before this script runs, so its error event is already gone.
    if (image.complete && !image.naturalWidth) {
        image.remove();
    } else {
        image.addEventListener('error', () => image.remove());
    }
});
