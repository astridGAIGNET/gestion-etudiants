/**
 * Composant Alpine.js : Auto-Save
 * Sauvegarde automatique des formulaires
 */
export default function autoSave(config) {
    return {
        // Configuration
        id: config.id,
        endpoint: config.endpoint,
        fields: config.fields,
        debounceDelay: config.debounceDelay || 750,

        // État
        form: {},
        original: {},
        loading: true,
        status: '',
        errorMessage: '',
        fieldStatus: {},

        // Initialisation
        async init() {
            this.fields.forEach(field => {
                this.form[field] = '';
                this.fieldStatus[field] = '';
            });
            await this.loadData();
        },

        // Charger les données
        async loadData() {
            this.loading = true;
            try {
                const response = await axios.get(`${this.endpoint}/${this.id}/data`);
                if (response.data.success) {
                    this.form = response.data.data || {};
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

        // Sauvegarde automatique
        async save() {
            if (JSON.stringify(this.form) === JSON.stringify(this.original)) return;

            this.status = 'saving';
            this.errorMessage = '';

            try {
                const response = await axios.post(`${this.endpoint}/${this.id}/auto-save`, this.form);
                if (response.data.success) {
                    this.original = {...this.form};
                    this.status = 'saved';
                    setTimeout(() => this.status = '', 2000);
                }
            } catch (error) {
                console.error('Erreur de sauvegarde:', error);
                this.status = 'error';
                this.errorMessage = error.response?.data?.message || 'Erreur lors de la sauvegarde';
                setTimeout(() => {
                    this.status = '';
                    this.errorMessage = '';
                }, 3000);
            }
        },

        // Sauvegarde d'un champ spécifique
        async saveField(fieldName) {
            const current = JSON.stringify(this.form[fieldName]);
            const original = JSON.stringify(this.original[fieldName]);

            if (current === original) {
                this.fieldStatus[fieldName] = '';
                return;
            }

            this.fieldStatus[fieldName] = 'saving';

            try {
                const response = await axios.post(`${this.endpoint}/${this.id}/auto-save`, this.form);
                if (response.data.success) {
                    this.original = {...this.form};
                    this.fieldStatus[fieldName] = 'saved';
                    setTimeout(() => {
                        if (this.form[fieldName] === this.original[fieldName]) {
                            this.fieldStatus[fieldName] = '';
                        }
                    }, 2000);
                }
            } catch (error) {
                console.error('Erreur de sauvegarde:', error);
                this.fieldStatus[fieldName] = 'error';
                this.errorMessage = error.response?.data?.message || 'Erreur lors de la sauvegarde';
                setTimeout(() => {
                    this.fieldStatus[fieldName] = '';
                    this.errorMessage = '';
                }, 3000);
            }
        },

        // Détection de changement
        onFieldChange(fieldName) {
            if (this.form[fieldName] !== this.original[fieldName]) {
                this.fieldStatus[fieldName] = 'changed';
            } else {
                this.fieldStatus[fieldName] = '';
            }
        },

        // Helpers CSS
        getInputClass(fieldName) {
            const status = this.fieldStatus[fieldName];
            const classes = {
                changed: 'border-warning border-2',
                saving: 'border-warning border-2',
                saved: 'border-success border-2',
                error: 'border-danger border-2'
            };
            return classes[status] || '';
        },

        getIconClass(fieldName) {
            const status = this.fieldStatus[fieldName];
            const icons = {
                changed: 'bi bi-hourglass-split text-warning',
                saving: 'bi bi-hourglass-split text-warning',
                saved: 'bi bi-check-circle-fill text-success',
                error: 'bi bi-exclamation-triangle-fill text-danger'
            };
            return icons[status] || '';
        },

        getStatusMessage(fieldName) {
            const status = this.fieldStatus[fieldName];
            const messages = {
                changed: { icon: 'bi-exclamation-circle', text: 'Modification en cours...', class: 'text-warning' },
                saving: { icon: 'bi-clock-history', text: 'Sauvegarde en cours...', class: 'text-warning' },
                saved: { icon: 'bi-check-circle', text: 'Sauvegardé avec succès', class: 'text-success' },
                error: { icon: 'bi-exclamation-triangle', text: this.errorMessage || 'Erreur de sauvegarde', class: 'text-danger' }
            };
            return messages[status] || null;
        },

        hasStatus(fieldName) {
            return this.fieldStatus[fieldName] !== '';
        },

        hasChanged(fieldName) {
            return this.form[fieldName] !== this.original[fieldName];
        },

        reset() {
            this.form = {...this.original};
        }
    };
}
