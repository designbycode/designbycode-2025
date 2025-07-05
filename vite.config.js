import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import livewire from "@defstudio/vite-livewire-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        livewire({
            // refresh css (tailwind ) as well
            refresh: ['resources/css/app.css'],
        }),
    ],
    esbuild: {
        logLevel: 'debug'
    }
});
