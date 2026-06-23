import { defineConfig } from 'vite';
import { resolve } from 'path';
import tailwindcss from '@tailwindcss/vite';

const VALET_HOST = 'swatreliefinitiative.test';
const VITE_PORT = 5173;

export default defineConfig({
    build: {
        outDir: 'public/dist',
        manifest: true,
        rollupOptions: {
            input: ['ui/ts/app.ts', 'ui/ts/auth.ts'],
            // output: {
            //     entryFileNames: chunk => {
            //         if (chunk.facadeModuleId.endsWith('ui/ts/main.ts')) {
            //             return 'main.js';
            //         } else {
            //             return 'admin.js';
            //         }
            //     }
            // }
        },
        copyPublicDir: false,
    },
    server: {
        host: 'localhost',
        port: VITE_PORT,
        strictPort: true,

        origin: `http://${VALET_HOST}`,

        hmr: {
            host: 'localhost',
            port: VITE_PORT,
            protocol: 'ws',
        },

        cors: {
            origin: [`http://${VALET_HOST}`, `https://${VALET_HOST}`],
        },

        watch: {
            ignored: [resolve(__dirname, 'public/**')],
        },
    },
    plugins: [tailwindcss()],
});
