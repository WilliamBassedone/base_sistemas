import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'Modules/Development/resources/assets/css/table-styles.css',
                'Modules/UI/resources/assets/components/data-table.css',
                'Modules/UI/resources/assets/components/data-table.js',
                'Modules/UI/resources/assets/components/data-table-tw.js',
            ],
            refresh: [
                'resources/views/**/*.blade.php',
                'Modules/**/resources/views/**/*.blade.php',
                'Modules/**/resources/assets/**/*.css',
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
