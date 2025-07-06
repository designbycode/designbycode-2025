import './bootstrap';
import {Alpine, Livewire} from '../../vendor/livewire/livewire/dist/livewire.esm';
import Prism from 'prismjs';
import "prismjs/components/prism-markup-templating.js"
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-markup';
import '../css/prism-okaidia.css';


import Clipboard from "@ryangjchandler/alpine-clipboard"
import Intersect from '@alpinejs/intersect'
import imageUploader from "@/tools/favicon-generator.js";

document.addEventListener('livewire:navigated', () => {
    if (window.Prism) {
        Prism.highlightAll();
    }
});

document.addEventListener('alpine:init', () => {
    Alpine.data('imageUploader', imageUploader)
})

// Optional: Load additional languages if needed


Alpine.plugin(Clipboard)
Alpine.plugin(Intersect)


window.Alpine = Alpine
Livewire.start()
