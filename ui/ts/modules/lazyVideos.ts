import { qsa } from '../utils';

export default function initLazyVideos() {
    const lazyVideos = qsa<HTMLVideoElement>('video[data-lazy]');

    lazyVideos.forEach((video) => {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;

                    video
                        .querySelectorAll('source[data-src]')
                        .forEach((source) => {
                            const dataSrc = source.getAttribute('data-src');

                            if (dataSrc) {
                                source.setAttribute('src', dataSrc);
                                source.removeAttribute('data-src');
                            }
                        });

                    video.load();
                    observer.disconnect();
                });
            },
            { rootMargin: '200px' }
        );

        observer.observe(video);
    });
}
