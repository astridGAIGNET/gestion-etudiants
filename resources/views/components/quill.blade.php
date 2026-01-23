{{--
    Composant Quill.js - Éditeur WYSIWYG simple et gratuit

    Usage:
    <x-quill id="description" model="form.description" :height="300" />
--}}

@once
    @push('head-scripts')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    @endpush
@endonce

<div class="quill-container"
     x-data="{
        quillEditor: null,
        saveTimer: null,

        initQuill() {
            // Initialise Quill
            this.quillEditor = new Quill('#{{ $id }}-editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'header': [1, 2, 3, false] }],
                        // ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            @if($model)
            // Synchronise Quill → Alpine quand on tape
            const self = this;
            this.quillEditor.on('text-change', () => {
                const content = this.quillEditor.root.innerHTML;
                {{ $model }} = content;
                document.getElementById('{{ $id }}').value = content;

                // Auto-save avec debounce
                clearTimeout(self.saveTimer);
                self.saveTimer = setTimeout(() => {
                    // Dispatch un événement personnalisé
                    self.$dispatch('quill-change');
                }, 1000);
            });
            @endif
        }
    }"
     x-init="
        // Attend que le DOM soit prêt
        $nextTick(() => {
            initQuill();

            @if($model)
            // Surveille les changements de form.description pour mettre à jour Quill
            $watch('{{ $model }}', (value) => {
                if (quillEditor && quillEditor.root.innerHTML !== value) {
                    quillEditor.root.innerHTML = value || '';
                }
            });
            @endif
        });
    ">
    {{-- Zone d'édition Quill --}}
    <div id="{{ $id }}-editor"></div>

    {{-- Champ caché pour le formulaire --}}
    <input type="hidden" id="{{ $id }}" name="{{ $id }}">
</div>
