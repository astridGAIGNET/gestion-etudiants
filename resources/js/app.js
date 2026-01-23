import './bootstrap';
import 'bootstrap';
import Alpine from 'alpinejs';

// ============================================
//  COMPOSANT UNIVERSEL AUTO-SAVE
// Utilisable pour TOUS tes formulaires
// ============================================

Alpine.data('autoSave', (config) => ({
    // Configuration reçue depuis la vue
    id: config.id,                    // ID de l'entité (student, classe, etc.)
    endpoint: config.endpoint,        // URL de base (/admin/students, /admin/classes, etc.)
    fields: config.fields,            // Liste des champs du formulaire

    // État du composant
    form: {},                         // Données du formulaire
    original: {},                     // Copie pour détecter les changements
    loading: true,                    // Chargement en cours ?
    status: '',                       // '', 'saving', 'saved', 'error'
    errorMessage: '',                 // Message d'erreur si problème

    // ========================================
    // INITIALISATION (appelée automatiquement)
    // ========================================
    async init() {
        // Initialiser les champs à vide
        this.fields.forEach(field => {
            this.form[field] = '';
        });

        // Charger les données depuis le serveur
        await this.loadData();
    },

    // ========================================
    // CHARGER LES DONNÉES
    // ========================================
    async loadData() {
        this.loading = true;

        try {
            const response = await axios.get(`${this.endpoint}/${this.id}/data`);

            if (response.data.success) {
                // Remplir le formulaire avec les données reçues
                this.form = response.data.data || response.data.student || response.data.classe || response.data.formateur || response.data.place;

                // Sauvegarder une copie
                this.original = {...this.form};
            }
        } catch (error) {
            console.error('Erreur de chargement:', error);
            this.status = 'error';
            this.errorMessage = 'Impossible de charger les données';
        } finally {
            this.loading = false;
        }
    },

    // ========================================
    // SAUVEGARDE AUTOMATIQUE
    // ========================================
    async save() {
        // Vérifier si quelque chose a changé
        if (JSON.stringify(this.form) === JSON.stringify(this.original)) {
            return; // Rien n'a changé
        }

        this.status = 'saving';
        this.errorMessage = '';

        try {
            const response = await axios.post(
                `${this.endpoint}/${this.id}/auto-save`,
                this.form
            );

            if (response.data.success) {
                // Mettre à jour la copie
                this.original = {...this.form};

                // Afficher le succès
                this.status = 'saved';

                // Cacher le badge après 2 secondes
                setTimeout(() => {
                    this.status = '';
                }, 2000);
            }
        } catch (error) {
            console.error('Erreur de sauvegarde:', error);
            this.status = 'error';

            // Afficher le message d'erreur du serveur si disponible
            if (error.response?.data?.message) {
                this.errorMessage = error.response.data.message;
            } else {
                this.errorMessage = 'Erreur lors de la sauvegarde';
            }

            // Cacher l'erreur après 3 secondes
            setTimeout(() => {
                this.status = '';
                this.errorMessage = '';
            }, 3000);
        }
    },

    // ========================================
    // MÉTHODE HELPER : Vérifier si un champ a changé
    // ========================================
    hasChanged(fieldName) {
        return this.form[fieldName] !== this.original[fieldName];
    },

    // ========================================
    // MÉTHODE HELPER : Réinitialiser le formulaire
    // ========================================
    reset() {
        this.form = {...this.original};
    }
}));

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
