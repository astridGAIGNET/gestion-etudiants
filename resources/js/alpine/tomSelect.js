export default (config = {}) => ({
    id: config.id || 'select',
    placeholder: config.placeholder || 'Sélectionner...',
    allowClear: config.allowClear !== false,
    tomSelectInstance: null,

    init() {
        this.$nextTick(() => {
            const selectElement = document.getElementById(this.id);

            if (!selectElement) {
                console.error('TomSelect: élément non trouvé', this.id);
                return;
            }

            this.tomSelectInstance = new TomSelect('#' + this.id, {
                placeholder: this.placeholder,
                allowEmptyOption: this.allowClear,
                create: false,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },
                onInitialize: function() {
                    console.log('TomSelect initialisé:', this.id);
                }
            });
        });
    },

    destroy() {
        if (this.tomSelectInstance) {
            this.tomSelectInstance.destroy();
        }
    }
});
