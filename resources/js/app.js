import './bootstrap';
import {Alpine, Livewire} from '../../vendor/livewire/livewire/dist/livewire.esm';
import clipboard from '@ryangjchandler/alpine-clipboard'
import Prism from 'prismjs';


import '../css/prism-okaidia.css';

// Optional: Load additional languages if needed
import "prismjs/components/prism-markup-templating.js"
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-markup';

window.Prism = Prism;


Alpine.plugin(clipboard)

Livewire.start()
