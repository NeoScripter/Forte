import { qs, qsa } from '../utils';

export default function initPasswordInputButtons() {
    const buttons = qsa<HTMLButtonElement>('[data-password-input-btn]');

    if (!buttons) {
        return;
    }

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const input =
                button.previousElementSibling as HTMLInputElement | null;

            if (!input || input.tagName !== 'INPUT') {
                console.warn('Not input element was found');
                return;
            }

            const isText = input.type === 'text';
            input.type = isText ? 'password' : 'text';

            const eyeOn = qs('.icon-eye', button);
            const eyeOff = qs('.icon-eye-off', button);

            if (eyeOn) {
                eyeOn.classList.toggle('hidden', isText);
            }
            if (eyeOff) {
                eyeOff.classList.toggle('hidden', !isText);
            }
        });
    });
}
