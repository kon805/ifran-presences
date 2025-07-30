<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

         <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>

<body class="bg-gray-50">
    {{-- Sidebar --}}
    @include('components.sidebar')

    <!-- Cloche de notification -->
    <div class="fixed top-4 right-4 z-50">
        <x-notification-bell />
    </div>

    {{-- Main content --}}
    <div
        x-data="{ open: window.innerWidth >= 768 }"
        x-init="window.addEventListener('resize', () => { open = window.innerWidth >= 768 });"
        class="transition-all duration-300"
        :class="open ? 'md:ml-72' : 'ml-0'"
    >
        {{ $slot ?? '' }}
        @yield('content')
    </div>

    @stack('modals')
    @livewireScripts
    @stack('scripts')

</body>
</html>


</html>
