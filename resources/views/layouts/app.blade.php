<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&family=Cairo:wght@200..1000&family=Readex+Pro:wght@160..700&display=swap"
        rel="stylesheet"> <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- box Icon style -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    @livewireStyles
    @stack('style')

</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Content -->
        <main>
            @if (session('success'))
                <div class=" max-w-7xl mx-auto sm:px-6 lg:px-8 p-4 my-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 w-full"
                    role="alert">
                    <span class="font-medium"> {{ session('success') }}</span>
                </div>
            @endif
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- @stack('modals') --}}

    @livewireScripts
    @stack('script')
</body>

</html>
