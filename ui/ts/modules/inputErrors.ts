import { qs } from '../utils';

export default function initScrollToInputError() {
    const error = qs<HTMLDivElement>('[data-input-error]');

    if (!error) return;

    error.scrollIntoView({
        behavior: 'smooth',
        block: 'center',
        inline: 'nearest',
    });
}
