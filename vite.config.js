import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    // POUR EVITER LES WARNINGS DANS LA CONSOLE A CAUSE DE BOOTSTRAP ________________________________
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions']
            }
        }
    }
    //_________________________________________________________________________________________________
});
