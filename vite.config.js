import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        watch: { usePolling: true },
    },
    plugins: [
        laravel({
            input: ['resources/css/landing.css', 'resources/js/landing.js', 'resources/css/filament/app/theme.css'],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
                'app/Filament/**',
            ]
        }),
    ],
});
