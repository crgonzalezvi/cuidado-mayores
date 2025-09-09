import './bootstrap';
import Alpine from 'alpinejs';
import axios from 'axios';

window.Alpine = Alpine;

// Configurar CSRF token para Axios
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

Alpine.start();
