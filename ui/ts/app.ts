import '../css/app.css'

import adaptiveImages from './modules/adaptiveImages';
import homeCarousel from './modules/homeCarousel';
import lazyVideos from './modules/lazyVideos';
import navMenu from './modules/navMenu';

class AppUI {
    constructor() {
        this.bindEvents();
    }

    private bindEvents(): void {
        const handlers = [adaptiveImages, lazyVideos, navMenu, homeCarousel];

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
            console.log('App initialized successfully');
        } catch (error) {
            console.error('Failed to initialize app:', error);
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
