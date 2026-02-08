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

<header class="w-full px-4 md-px-0 relative z-20 text-white flex justify-center" x-data="{ mobileMenuOpen: false }">
    <div class="w-2xl py-4 flex justify-between items-center">
        <img src="/img/logo.svg" class="h-10" alt="">
        <h1 class="text-2xl font-bold logo-text">
            <a href="{{ auth()->check() ? route('dashboard') : route('welcome') }}">{{ config('app.name', 'La Conjugerie') }}</a>
        </h1>
        <!-- Desktop Navigation - hidden on mobile, visible from md: breakpoint -->
        <nav class="hidden md:block">
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

        <!-- Hamburger Button - visible only on mobile -->
        <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="z-20 md:hidden inline-flex items-center justify-center p-2 rounded-md text-white hover:bg-white/10 focus:outline-none focus:bg-white/10 transition duration-150 ease-in-out"
            aria-label="Toggle navigation menu"
            :aria-expanded="mobileMenuOpen.toString()"
        >
            <!-- Hamburger Icon -->
            <svg x-show="!mobileMenuOpen" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Close Icon -->
            <svg x-show="mobileMenuOpen" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation Menu -->
    <div
        x-show="mobileMenuOpen"
        @click.outside="mobileMenuOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="z-20 md:hidden absolute top-full left-0 right-0 bg-secondary shadow-lg"
        style="display: none;"
    >
        <div class="px-4 pt-2 pb-6 space-y-1">
            @if (Route::has('welcome.nl'))
                <x-responsive-nav-link href="{{ route('welcome.nl') }}" :active="false">
                    Nederlands
                </x-responsive-nav-link>
            @endif

            @auth
                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    Compte
                </x-responsive-nav-link>

                <x-responsive-nav-link href="{{ route('practice') }}" :active="request()->routeIs('practice')">
                    Pratiquer
                </x-responsive-nav-link>

                @if(auth()->user()?->is_teacher)
                    <x-responsive-nav-link href="{{ route('filament.admin.pages.dashboard') }}" :active="false">
                        Admin
                    </x-responsive-nav-link>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left ps-3 pe-4 py-2 border-l-4 border-white/20 text-base font-medium text-white hover:bg-white/10 hover:border-white/30 focus:outline-none transition duration-150 ease-in-out">
                        Se déconnecter
                    </button>
                </form>
            @else
                <div class="px-3 py-2">
                    <a href="{{ route('smartschool.redirect') }}" class="button-primary block text-center">
                        Se connecter
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

@auth
<main class="w-full z-10 relative py-4 md:py-10 px-4">
    <div class="max-w-7xl mx-auto lg:grid lg:grid-cols-[280px_1fr_280px] lg:gap-6">
        {{-- Left sidebar: desktop only --}}
        <aside class="hidden lg:block sticky top-6 self-start">
            <livewire:leaderboard type="weekly" />
        </aside>

        {{-- Center column --}}
        <div class="max-w-2xl mx-auto lg:max-w-none">
            <div class="bg-white rounded-lg p-5">
                {{ $slot }}
            </div>

            {{-- Mobile: tabbed leaderboards below content --}}
            <div class="lg:hidden mt-6" x-data="{ tab: 'weekly' }">
                <div class="flex bg-white rounded-full p-1 shadow-md mb-4 border border-white">
                    <button
                        @click="tab = 'weekly'"
                        :class="tab === 'weekly' ? 'bg-secondary text-white' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 text-sm font-semibold py-2 rounded-full text-center transition-colors"
                    >
                        Semaine
                    </button>
                    <button
                        @click="tab = 'alltime'"
                        :class="tab === 'alltime' ? 'bg-secondary text-white' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 text-sm font-semibold py-2 rounded-full text-center transition-colors"
                    >
                        Global
                    </button>
                </div>
                <div x-show="tab === 'weekly'">
                    <livewire:leaderboard type="weekly" />
                </div>
                <div x-show="tab === 'alltime'" style="display: none;">
                    <livewire:leaderboard type="alltime" />
                </div>
            </div>
        </div>

        {{-- Right sidebar: desktop only --}}
        <aside class="hidden lg:block sticky top-6 self-start">
            <livewire:leaderboard type="alltime" />
        </aside>
    </div>
</main>
@else
<main class="w-full z-10 relative flex justify-center py-4 md:py-10">
    <div class="w-2xl md:rounded-lg bg-white p-5">
        {{ $slot }}
    </div>
</main>
@endauth


<!-- Livewire Scripts -->
@livewireScripts

<!-- Additional Scripts -->
@stack('scripts')
</body>
</html>
