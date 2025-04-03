import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@codemirror/state': resolve(__dirname, 'node_modules/@codemirror/state'),
            '@codemirror/basic-setup': resolve(__dirname, 'node_modules/@codemirror/basic-setup'),
        }
    }
});
