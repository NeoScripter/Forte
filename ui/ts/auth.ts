import '../css/auth.css';

import accountMenu from './modules/accountMenu';
import checkboxes from './modules/checkboxes';
import filePond from './modules/filePond';
import scrollToInputError from './modules/inputErrors';
import passwordInputButtons from './modules/passwordInputButtons';
import sidebar from './modules/sidebar';
import theme from './modules/theme';
import toasts from './modules/toasts';
import wysiwygs from './modules/wysiwygs';

class AppUI {
    constructor() {
        this.bindEvents();
    }

    private bindEvents(): void {
        const handlers = [
            checkboxes,
            wysiwygs,
            accountMenu,
            passwordInputButtons,
            theme,
            toasts,
            sidebar,
            filePond,
            scrollToInputError,
        ];

        for (const handler of handlers) {
            try {
                handler();
            } catch (error) {
                console.error(error);
            }
        }
    }
}

class App {
    ui: AppUI | null;
    constructor() {
        this.ui = null;
    }

    init() {
        try {
            this.ui = new AppUI();
            console.log('App ialized successfully');
        } catch (error) {
            console.error('Failed to ialize app:', error);
        }
    }
}

let app: App | null;

document.addEventListener('DOMContentLoaded', () => {
    app = new App();
    app.init();
});

window.addEventListener('load', () => {
    if (!app) {
        app = new App();
        app.init();
    }
});
