import { qs } from '../utils';

export default function initAccountMenu() {
    const menu = qs<HTMLDivElement>('[data-account-menu]');
    const trigger = qs<HTMLButtonElement>('[data-account-menu-trigger]');

    if (!trigger || !menu) {
        return;
    }

    trigger.addEventListener('click', () => {
        menu.classList.toggle('pointer-events-none');
        menu.classList.toggle('opacity-0');
        menu.classList.toggle('scale-90');
        menu.classList.toggle('scale-100');
    });

    const closeMenu = () => {
        menu.classList.add('scale-90', 'pointer-events-none', 'opacity-0');
        menu.classList.remove('scale-100');
    };

    document.addEventListener('click', (e) => {
        if (
            !menu.contains(e.target as Node | null) &&
            !trigger.contains(e.target as Node | null)
        ) {
            closeMenu();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeMenu();
        }
    });
}
