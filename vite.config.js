import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        exclude: ['moment']
    },
    build: {
        rollupOptions: {
            external: (id) => {
                // Exclude problematic moment.js locale imports
                if (id.includes('moment') && id.includes('locale')) {
                    return true;
                }
                return false;
            }
        }
    }
});
