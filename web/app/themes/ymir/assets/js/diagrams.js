document.querySelectorAll('[data-ymir-animation]').forEach((figure) => {
    const picture = figure.querySelector('picture');
    const image = picture?.querySelector('img');
    const button = figure.querySelector('[data-animation-control]');
    const status = figure.querySelector('[data-animation-status]');

    if (!image || !button || !status) {
        return;
    }

    const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
    const mobile = matchMedia('(max-width: 799px)');
    let frame = null;
    let timer;
    let generation = 0;
    let playing = false;
    let played = false;
    let visibility = 0;
    let autoStarted = false;

    const stop = () => {
        generation++;
        clearTimeout(timer);
        frame?.replaceWith(picture);
        frame = null;
        playing = false;
        button.textContent = played ? 'Replay' : 'Play';
    };

    const play = async () => {
        if (playing || reducedMotion.matches || document.hidden) {
            return;
        }

        const current = ++generation;
        playing = true;
        button.textContent = 'Stop';
        status.hidden = true;

        try {
            const source = [...picture.querySelectorAll('source[type="image/svg+xml"]')].find((source) => !source.media || matchMedia(source.media).matches);
            const url = new URL(source?.srcset || '', location.href);

            if (!url.pathname.endsWith('.svg')) {
                throw new Error('An SVG source is required.');
            }

            // A fresh image URL restarts the SVG timeline even when the browser cached an earlier playback.
            url.searchParams.set('ymir-animation', `${Date.now()}-${current}`);
            url.hash = 'play';

            const next = image.cloneNode(false);
            next.loading = 'eager';
            next.src = url.href;
            await next.decode();

            if (current !== generation) {
                return;
            }

            picture.replaceWith(next);
            frame = next;
            played = true;
            timer = setTimeout(stop, 12000);
        } catch {
            if (current !== generation) {
                return;
            }

            stop();
            status.textContent = 'Animation unavailable. The static diagram is shown.';
            status.hidden = false;
        }
    };

    const autoPlay = () => {
        if (!autoStarted && visibility >= 0.6 && !reducedMotion.matches && !document.hidden) {
            autoStarted = true;
            play();
        }
    };

    const updateMotion = () => {
        stop();
        button.hidden = reducedMotion.matches;
        autoPlay();
    };

    updateMotion();
    reducedMotion.addEventListener('change', updateMotion);
    mobile.addEventListener('change', stop);

    button.addEventListener('click', () => {
        autoStarted = true;
        if (playing) {
            stop();
        } else {
            play();
        }
    });

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stop();
        } else {
            autoPlay();
        }
    });

    if ('IntersectionObserver' in window) {
        new IntersectionObserver((entries) => {
            const entry = entries[entries.length - 1];
            visibility = entry.isIntersecting ? entry.intersectionRatio : 0;
            if (visibility === 0) {
                stop();
            } else if (visibility >= 0.6) {
                autoPlay();
            }
        }, { threshold: [0, 0.6] }).observe(picture.parentElement);
    }
});
