import { gid } from '../utils';

export default function initNavMenu() {
    const openNavMenuBtn = gid<HTMLDivElement>('open-nav-menu-btn');
    const closeNavMenuBtn = gid<HTMLDivElement>('close-nav-menu-btn');
    const navPopup = gid<HTMLDialogElement>('nav-popup');

    if (openNavMenuBtn && navPopup) {
        openNavMenuBtn.addEventListener('click', () => {
            navPopup.showModal();
        });
    }

    if (closeNavMenuBtn && navPopup) {
        closeNavMenuBtn.addEventListener('click', () => {
            navPopup.close();
        });
    }

    if (navPopup) {
        navPopup.addEventListener('click', (e) => {
            if (e.target === navPopup) {
                navPopup.close();
            }
        });
    }
}
