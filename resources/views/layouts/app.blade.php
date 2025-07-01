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

    <link href="https://cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    {{-- <x-livewire-alert::scripts /> --}}
</head>

<body>

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
</body>


</html>
