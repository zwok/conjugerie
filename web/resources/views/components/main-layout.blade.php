<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'La Conjugerie') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="antialiased background  min-h-screen">
{{--<img class="fixed z-0 -top-10" src="/img/bg_topleft.png" alt="">--}}
{{--<img class="fixed z-0 -bottom-10 -right-10" src="/img/bg_bottomright.png" alt="">--}}

<header class="w-full relative z-10 text-white flex justify-center">
    <div class="w-2xl py-4 flex justify-between items-center">
        <img src="/img/logo.svg" class="h-10" alt="">
        <h1 class="text-2xl font-bold logo-text">
            <a href="{{ auth()->check() ? route('dashboard') : route('welcome') }}">{{ config('app.name', 'La Conjugerie') }}</a>
        </h1>
        <nav>
            <ul class="flex space-x-4 font-bold">
                @if (Route::has('welcome.nl'))
                    <li><a href="{{ route('welcome.nl') }}" class="nav-link">Nederlands</a></li>
                @endif
                @auth
                        <li><a href="{{ route('dashboard') }}" class="nav-link">Compte</a></li>
                        <li><a href="{{ route('practice') }}" class="nav-link">Pratiquer</a></li>
                    @if(auth()->user()?->is_teacher)
                            <li><a href="{{ route('filament.admin.pages.dashboard') }}" class="nav-link">Admin</a></li>
                        @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link">Se déconnecter</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('smartschool.redirect') }}" class="button-primary">Se connecter</a></li>
                @endauth
            </ul>
        </nav>
    </div>
</header>

<main class="w-full z-10 relative  flex justify-center py-10">
    <!-- Main content -->
    <div class="w-2xl rounded-lg bg-white p-5">
        {{ $slot }}
    </div>
</main>


<!-- Livewire Scripts -->
@livewireScripts

<!-- Additional Scripts -->
@stack('scripts')
</body>
</html>
