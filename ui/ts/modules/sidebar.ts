import { gid, qs } from '../utils';

export default function initSidebar() {
    const sidebar = gid<HTMLDivElement>('admin-sidebar');

    if (!sidebar) return;

    const hide = () => {
        sidebar.classList.add('pointer-events-none');
        sidebar.classList.remove('bg-black/75');

        const aside = qs('aside', sidebar);

        if (!aside) return;

        aside.classList.add('-translate-x-full');
    };

    const show = () => {
        sidebar.classList.remove('pointer-events-none');
        sidebar.classList.add('bg-black/75');

        const aside = qs('aside', sidebar);

        if (!aside) return;

        aside.classList.remove('-translate-x-full');
    };

    sidebar.addEventListener('click', (e) => {
        const el = e.target as HTMLElement | null;
        if (!el || el.id !== sidebar.id) return;

        hide();
    });

    let touchStartX: number | null = null;
    const SWIPE_THRESHOLD = 50;

    sidebar.addEventListener('touchstart', (e) => {
        touchStartX = e.touches[0].clientX;
    });

    sidebar.addEventListener('touchend', (e) => {
        if (touchStartX === null) return;

        const deltaX = e.changedTouches[0].clientX - touchStartX;

        if (Math.abs(deltaX) > SWIPE_THRESHOLD) {
            e.preventDefault();

            if (deltaX < 0) {
                hide();
            }
        }

        touchStartX = null;
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            hide();
        }
    });

    const button = qs<HTMLButtonElement>('[data-open-sidebar-btn]');

    if (!button) return;

    button.addEventListener('click', show);
}
