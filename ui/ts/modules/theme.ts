import { qsa, setCookie } from '../utils';

const THEME_KEY = 'theme';

export default function initTheme() {
    const activeClass = [
        'bg-white',
        'shadow-xs',
        'dark:bg-neutral-700',
        'dark:text-neutral-100',
    ];
    const inactiveClass = [
        'text-neutral-500',
        'hover:bg-neutral-200/60',
        'hover:text-black',
        'dark:text-neutral-400',
        'dark:hover:bg-neutral-700/60',
    ];
    const buttons = qsa<HTMLButtonElement>(
        '[data-appearance-tabs] [data-theme]'
    );

    const applyTheme = (value: string) => {
        const root = document.documentElement;

        localStorage.setItem(THEME_KEY, value);

        if (
            value === 'dark' ||
            (value === 'system' &&
                window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            setCookie(THEME_KEY, 'dark');
            root.classList.add('dark');
        } else {
            setCookie(THEME_KEY, 'light');
            root.classList.remove('dark');
        }
    };
    const updateButtons = (current: string) => {
        if (!buttons) {
            return;
        }

        buttons.forEach((btn) => {
            const isActive = btn.dataset.theme === current;
            btn.classList.remove(...activeClass, ...inactiveClass);
            btn.classList.add(...(isActive ? activeClass : inactiveClass));
        });
    };

    const saved = localStorage.getItem(THEME_KEY) || 'system';
    applyTheme(saved);
    updateButtons(saved);

    if (!buttons) {
        return;
    }

    buttons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const value = btn.dataset.theme;

            if (!value) {
                return;
            }

            applyTheme(value);
            updateButtons(value);
        });
    });
}
