import './bootstrap';
import 'bootstrap';
import Alpine from 'alpinejs';
import TomSelect from 'tom-select';
import List from 'list.js';
import Quill from 'quill';

// Composants Alpine
import autoSave from './alpine/autoSave';
import tomSelectAutoSave from './alpine/tomSelectAutoSave';
import quill from './alpine/quill';
import quillAutoSave from './alpine/quillAutoSave';

// ============================================
// CONFIGURATION GLOBALE
// ============================================
window.TomSelect = TomSelect;
window.List = List;
window.Quill = Quill;
window.axios = axios;

// ============================================
// ENREGISTREMENT DES COMPOSANTS ALPINE
// ============================================
Alpine.data('autoSave', autoSave);
Alpine.data('tomSelectAutoSave', tomSelectAutoSave);
Alpine.data('quill', quill);
Alpine.data('quillAutoSave', quillAutoSave);


// Rendre Alpine disponible globalement
window.Alpine = Alpine;
Alpine.start();

// Gestion du thème
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', savedTheme);
    updateThemeIcon(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    function updateThemeIcon(theme) {
        if (themeToggle) {
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
            }
        }
    }
});
