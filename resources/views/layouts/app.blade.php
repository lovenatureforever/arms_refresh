<!DOCTYPE html>
<html data-sidenav-view="{{ $sidenav ?? 'default' }}" lang="en">

<head>

    <meta charset="utf-8">
    <title>{{ $title ?? '' }} AuditApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link href="/images/favicon.ico" rel="shortcut icon">

    @stack('css')
    @vite(['resources/scss/app.scss', 'resources/scss/icons.scss'])
    @vite(['resources/js/head.js', 'resources/js/config.js'])
    @livewireStyles

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    {{-- <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/47.1.0/ckeditor5.css" crossorigin> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    {{-- <x-livewire-alert::scripts /> --}}
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/47.1.0/ckeditor5.umd.js" crossorigin></script> --}}
    <style>
    .editor-container__editor-wrapper {
        display: flex;
        width: fit-content;
    }

    .editor-container_document-editor {
        border: 1px solid var(--ck-color-base-border);
    }

    .editor-container_document-editor .editor-container__toolbar {
        display: flex;
        position: relative;
        box-shadow: 0 2px 3px hsla(0, 0%, 0%, 0.078);
    }

    .editor-container_document-editor .editor-container__toolbar > .ck.ck-toolbar {
        flex-grow: 1;
        width: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        border-top: 0;
        border-left: 0;
        border-right: 0;
    }

    .editor-container_document-editor .editor-container__editor-wrapper {
        max-height: var(--ck-editor-height);
        min-height: var(--ck-editor-height);
        overflow-y: scroll;
        background: var(--ck-color-base-foreground);
    }

    .editor-container_document-editor .editor-container__editor {
        margin-top: 28px;
        margin-bottom: 28px;
        height: 100%;
    }

    .editor-container_document-editor .editor-container__editor .ck.ck-editor__editable {
        box-sizing: border-box;
        min-width: calc(210mm + 2px);
        max-width: calc(210mm + 2px);
        min-height: 297mm;
        height: fit-content;
        padding: 20mm 12mm;
        border: 1px hsl(0, 0%, 82.7%) solid;
        background: hsl(0, 0%, 100%);
        box-shadow: 0 2px 3px hsla(0, 0%, 0%, 0.078);
        flex: 1 1 auto;
        margin-left: 72px;
        margin-right: 72px;
    }
    </style>
</head>

<body class="p-[0px !important]">

    <div class="wrapper flex">

        @if (tenant())
            @include('layouts.components/tenant-sidebar')
        @else
            @include('layouts.components/sidebar')
        @endif

        <div class="page-content">

            @include('layouts.components/topbar')

            <main class="flex-grow p-6">

                {{ $slot }}

            </main>

            @include('layouts.components/footer')

        </div>

    </div>

    @include('layouts.components/customizer')


    <!-- bundle -->
    @stack('script')
    <!-- App js -->
    @stack('script-bottom')


    @vite(['resources/js/app.js', 'resources/js/pages/form-inputmask.js'])
    @livewire('wire-elements-modal')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/cleave.js"></script>
    <script src="https://unpkg.com/@wotz/livewire-sortablejs@1.0.0/dist/livewire-sortable.js"></script>
    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CKEditor 5 Classic build (free) -->
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script> --}}

</body>


</html>
