import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/css/entries/admin.css',
                'resources/css/entries/auth.css',
                'resources/css/entries/site.css',
                 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
