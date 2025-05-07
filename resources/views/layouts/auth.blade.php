<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <title>{{ $title ?? '' }} | AuditApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- App favicon -->
    <link href="/images/favicon.ico" rel="shortcut icon">


    @vite(['resources/scss/app.scss', 'resources/scss/icons.scss'])
    @vite(['resources/js/head.js', 'resources/js/config.js'])
    @livewireStyles
</head>

<body>

    <div class="bg-gradient-to-r from-rose-100 to-teal-100 dark:from-gray-700 dark:via-gray-900 dark:to-black">


        <div class="flex h-screen w-screen items-center justify-center">

            {{ $slot }}
        </div>

    </div>
    @livewireScripts
</body>

</html>
