<div wire:ignore>
    <div id="{{ $quillId }}"></div>
</div>

@script
<script>
    const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        // ['blockquote', 'code-block'],

        // [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent

        [{ 'header': [1, 2, 3, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
    ];
    const quill = new Quill('#' + @js($quillId), {
        modules: {
            toolbar: toolbarOptions
        },
        theme: @js($theme)
    });

    quill.root.innerHTML = $wire.get('value');

    quill.on('text-change', function () {
        let value = quill.root.innerHTML;
        @this.set('content', value);
    });
</script>
@endscript
