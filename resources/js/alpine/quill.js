export default (config = {}) => ({
    quillInstance: null,
    content: '',

    // Configuration par défaut
    editorId: config.id || config.name || 'editor',
    fieldName: config.name || '',
    height: config.height || 300,
    placeholder: config.placeholder || '',
    value: config.value || '',

    init() {
        this.$nextTick(() => {
            if (typeof Quill === 'undefined') {
                console.error('Quill non disponible');
                return;
            }

            this.quillInstance = new Quill('#quill-' + this.editorId, {
                theme: 'snow',
                placeholder: this.placeholder,
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        //['link', 'image'],
                        ['clean']
                    ]
                }
            });

            // Charge la valeur initiale
            if (this.value) {
                this.quillInstance.root.innerHTML = this.value;
            }

            // Synchronise avec le champ hidden
            this.quillInstance.on('text-change', () => {
                this.content = this.quillInstance.root.innerHTML;
                const hiddenInput = document.getElementById(this.fieldName);
                if (hiddenInput) {
                    hiddenInput.value = this.content;
                }
            });

            console.log('Quill initialisé:', this.fieldName);
        });
    }
});
