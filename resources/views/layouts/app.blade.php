<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'GoTeach'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Styles spécifiques aux employeurs -->
        @if(request()->routeIs('dashboard') && auth()->check() && auth()->user()->role === 'employeur')
            @vite(['resources/css/employeurs/styles.css'])
        @endif

        <!-- Styles spécifiques aux jeunes -->
        @if(request()->routeIs('jeunes.*') && auth()->check() && auth()->user()->role === 'jeune')
            @vite(['resources/css/jeunes/styles.css'])
        @endif
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @hasSection('content')
                    @yield('content')
                @elseif(isset($slot))
                    {{ $slot }}
                @else
                    <!-- Contenu par défaut si aucun slot n'est défini -->
                    <div class="py-12">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6 text-gray-900">
                                    {{ __("You're logged in!") }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </main>
        </div>
        
        <!-- Scripts spécifiques aux employeurs -->
        @if(request()->routeIs('dashboard') && auth()->check() && auth()->user()->role === 'employeur')
            @vite(['resources/js/employeurs/script.js'])
        @endif

        <!-- Scripts spécifiques aux jeunes -->
        @if(request()->routeIs('jeunes.*') && auth()->check() && auth()->user()->role === 'jeune')
            @vite(['resources/js/jeunes/script.js'])
        @endif
    </body>
</html>
