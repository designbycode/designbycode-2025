import './bootstrap';
import {Alpine, Livewire} from '../../vendor/livewire/livewire/dist/livewire.esm';
import Prism from 'prismjs';

import Clipboard from "@ryangjchandler/alpine-clipboard"


import '../css/prism-okaidia.css';

// Optional: Load additional languages if needed
import "prismjs/components/prism-markup-templating.js"
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-markup';

window.Prism = Prism;

Alpine.plugin(Clipboard)

window.Alpine = Alpine


Livewire.start()
