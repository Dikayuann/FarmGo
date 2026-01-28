import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1', // Use IPv4 instead of IPv6 to prevent connection issues
        port: 5173,
        strictPort: false,
        hmr: {
            host: '127.0.0.1',
        },
        cors: true, // Enable CORS for asset loading
        watch: {
            usePolling: true, // Better compatibility on Windows
        },
    },
});
