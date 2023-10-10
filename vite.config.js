import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        watch: { usePolling: true },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/landing.css', 'resources/js/landing.js'],
            refresh: refreshPaths,
        }),
    ],
});
