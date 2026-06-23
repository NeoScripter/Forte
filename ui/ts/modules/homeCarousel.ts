import { qsa } from '../utils';

export default function initHomeCarousel() {
    const SLIDE_ANIMATION_FREQUENCY_MS = 8000;
    const slides = qsa<HTMLDivElement>('.home-slide');

    if (slides.length === 0) return;

    let currentSlideIdx = 0;
    const lastSlide = slides.length - 1;

    const showSlide = (idx: number) => {
        slides[idx].classList.remove('opacity-0');
    };

    const hideSlide = (idx: number) => {
        slides[idx].classList.add('opacity-0');
        slides[idx].classList.add('-translate-x-full');

        setTimeout(
            () => slides[idx].classList.remove('-translate-x-full'),
            1500
        );
    };

    showSlide(currentSlideIdx);

    setInterval(() => {
        const nextSlideIdx =
            currentSlideIdx === lastSlide ? 0 : currentSlideIdx + 1;

        showSlide(nextSlideIdx);
        hideSlide(currentSlideIdx);

        currentSlideIdx =
            currentSlideIdx === lastSlide ? 0 : currentSlideIdx + 1;
    }, SLIDE_ANIMATION_FREQUENCY_MS);
}
