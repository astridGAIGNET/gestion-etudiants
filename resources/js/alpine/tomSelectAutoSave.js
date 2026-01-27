/**
 * Composant Alpine.js : Tom Select + Auto-Save
 */
export default function tomSelectAutoSave(config) {
    return {
        tomSelectInstance: null,

        init() {
            const checkLoaded = setInterval(() => {
                if (!this.loading) {
                    clearInterval(checkLoaded);
                    this.$nextTick(() => this.initTomSelect());
                }
            }, 100);
        },

        initTomSelect() {
            const selectElement = document.getElementById(config.selectId);

            if (!selectElement) {
                console.error(`Element #${config.selectId} not found`);
                return;
            }

            this.tomSelectInstance = new window.TomSelect(`#${config.selectId}`, {
                placeholder: config.placeholder || 'Sélectionner...',
                allowClear: config.allowClear !== false,
                maxOptions: config.maxOptions || 50,
                create: false,
                dropdownParent: 'body',
                plugins: config.allowClear ? {
                    clear_button: { title: 'Effacer la sélection' }
                } : {},
                onChange: (value) => {
                    this.form[config.fieldName] = value || null;
                    this.onFieldChange(config.fieldName);
                    this.saveField(config.fieldName);
                }
            });

            if (this.form[config.fieldName]) {
                this.tomSelectInstance.setValue(this.form[config.fieldName], true);
            }

            this.$watch(`form.${config.fieldName}`, (newValue) => {
                if (this.tomSelectInstance) {
                    const currentValue = this.tomSelectInstance.getValue();
                    if (currentValue !== newValue) {
                        this.tomSelectInstance.setValue(newValue || '', true);
                    }
                }
            });

            this.$cleanup(() => {
                if (this.tomSelectInstance) {
                    this.tomSelectInstance.destroy();
                }
            });
        }
    };
}
