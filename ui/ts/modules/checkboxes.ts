import { qs, qsa } from '../utils';

export default function initCheckboxes() {
    const activeClasses = [
        'border-primary',
        'bg-primary',
        'text-primary-foreground',
    ];

    const checkboxes = qsa<HTMLSpanElement>('[data-checkbox-input]');

    if (!checkboxes) return;

    const updateInput = (
        label: HTMLSpanElement,
        input: HTMLInputElement,
        checkmark: HTMLSpanElement
    ) => {
        if (input.checked) {
            label.classList.add(...activeClasses);
            checkmark.classList.remove('opacity-0');
        } else {
            label.classList.remove(...activeClasses);
            checkmark.classList.add('opacity-0');
        }
    };

    checkboxes.forEach((label) => {
        const input = qs<HTMLInputElement>('input', label);
        const checkmark = qs<HTMLSpanElement>(
            '[data-checkbox-checkmark]',
            label
        );

        if (input && checkmark) {
            updateInput(label, input, checkmark);

            input.addEventListener('change', (e) =>
                updateInput(label, input, checkmark)
            );
        }
    });
}
