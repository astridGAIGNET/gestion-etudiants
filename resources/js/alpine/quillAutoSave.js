/**
 * Composant Alpine.js : Quill + Auto-Save
 */
export default function quillAutoSave(config) {
    return {
        quillEditor: null,
        saveTimer: null,
        initialized: false,

        init() {
            const checkLoaded = setInterval(() => {
                if (!this.loading && !this.initialized) {
                    clearInterval(checkLoaded);
                    this.$nextTick(() => this.initQuill());
                }
            }, 100);
        },

        initQuill() {
            this.quillEditor = new window.Quill(`#${config.editorId}`, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'header': [1, 2, 3, false] }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            const initialContent = this.form[config.fieldName];
            if (initialContent) {
                this.quillEditor.root.innerHTML = initialContent;
            }
            this.initialized = true;

            this.quillEditor.on('text-change', () => {
                const content = this.quillEditor.root.innerHTML;
                this.form[config.fieldName] = content;
                this.onFieldChange(config.fieldName);

                clearTimeout(this.saveTimer);
                this.saveTimer = setTimeout(() => {
                    this.saveField(config.fieldName);
                }, 1000);
            });

            this.$watch(`form.${config.fieldName}`, (newValue) => {
                if (this.quillEditor && this.quillEditor.root.innerHTML !== newValue) {
                    this.quillEditor.root.innerHTML = newValue || '';
                }
            });
        }
    };
}
