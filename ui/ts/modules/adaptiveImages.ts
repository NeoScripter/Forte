import { qs, qsa } from '../utils';

export default function initAdaptiveImages() {
    const adaptiveImgs = qsa<HTMLDivElement>('.adaptive-img');

    adaptiveImgs.forEach((container) => {
        const img = qs<HTMLImageElement>('img', container);

        if (!img) {
            return;
        }

        const handleImgLoad = () => {
            container.style.backgroundImage = 'none';
            img.classList.remove('opacity-0');

            const overlay = qs('.adaptive-overlay', container);

            if (overlay) {
                overlay.remove();
            }
        };

        if (img.complete) {
            handleImgLoad();
        } else {
            img.addEventListener('load', () => handleImgLoad());
        }
    });
}
