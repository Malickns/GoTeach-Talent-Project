import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/employeurs/styles.css',
                'resources/js/employeurs/script.js',
                'resources/css/auth/login.css',
                'resources/js/auth/login.js',
                'resources/css/auth/register.css',
                'resources/js/auth/register.js'
            ],
            refresh: true,
        }),
    ],
});
