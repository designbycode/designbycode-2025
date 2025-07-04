import axios from 'axios';
import {livewire_hot_reload} from 'virtual:livewire-hot-reload'

livewire_hot_reload();

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
