@props(['sidebars' => true])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'La Conjugerie') }}</title>

    <!-- Apply the saved (or system) theme before first paint -->
    <script>
        (function () {
            var theme = null;
            try { theme = localStorage.getItem('theme'); } catch (e) {}
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=quicksand:400,500,600,700&display=swap" rel="stylesheet"/>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Additional Styles -->
    @stack('styles')
</head>
<body class="antialiased min-h-screen flex flex-col font-sans @auth pb-20 lg:pb-0 @endauth">

<header class="w-full px-4 relative z-20 text-white flex justify-center bg-secondary dark:bg-surface shadow-md shadow-black/10">
    <div class="w-full max-w-7xl py-3 flex justify-between items-center gap-3">
        <a href="{{ auth()->check() ? route('dashboard') : route('welcome') }}" class="flex items-center gap-2 min-w-0">
            <img src="/img/logo.svg" class="h-9 shrink-0" alt="">
            <span class="text-xl font-bold truncate hidden sm:block">{{ config('app.name', 'La Conjugerie') }}</span>
        </a>

        <div class="flex items-center gap-2">
            @auth
                {{-- Streak and XP, kept in sync with the practice component --}}
                <div class="flex items-center gap-2"
                     x-data="{ streak: {{ auth()->user()->streak() }}, xp: {{ auth()->user()->totalXp() }} }"
                     x-on:stats-updated.window="streak = $event.detail.streak; xp = $event.detail.xp">
                    <span class="chip bg-white/15" title="Jours d'affilée">🔥 <span x-text="streak"></span></span>
                    <span class="chip bg-white/15" title="Points d'expérience">⚡ <span x-text="xp"></span> XP</span>
                </div>
            @endauth

            <!-- Desktop Navigation -->
            <nav class="hidden lg:block">
                <ul class="flex items-center gap-1 font-bold">
                    @if (Route::has('welcome.nl'))
                        <li><a href="{{ route('welcome.nl') }}" class="nav-link">Nederlands</a></li>
                    @endif
                    @auth
                        <li><a href="{{ route('practice') }}" class="nav-link">Pratiquer</a></li>
                        <li><a href="{{ route('dashboard') }}" class="nav-link">Profil</a></li>
                        @if(auth()->user()?->is_teacher)
                            <li><a href="{{ route('filament.admin.pages.dashboard') }}" class="nav-link">Admin</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link cursor-pointer">Se déconnecter</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </nav>

            {{-- Sound and theme toggles --}}
            <div class="flex items-center gap-1"
                 x-data="{ dark: document.documentElement.classList.contains('dark'), sound: true }"
                 x-init="sound = window.conjugerie.soundOn()">
                <button type="button" class="nav-link cursor-pointer" @click="sound = !sound; window.conjugerie.setSound(sound)"
                        :aria-label="sound ? 'Couper le son' : 'Activer le son'" :title="sound ? 'Couper le son' : 'Activer le son'">
                    <span x-text="sound ? '🔊' : '🔇'">🔊</span>
                </button>
                <button type="button" class="nav-link cursor-pointer" @click="dark = !dark; window.conjugerie.setDark(dark)"
                        :aria-label="dark ? 'Mode clair' : 'Mode sombre'" :title="dark ? 'Mode clair' : 'Mode sombre'">
                    <span x-text="dark ? '☀️' : '🌙'">🌙</span>
                </button>
            </div>

            @guest
                @if (Route::has('welcome.nl'))
                    <a href="{{ route('welcome.nl') }}" class="nav-link font-bold lg:hidden">NL</a>
                @endif
                <a href="{{ route('smartschool.redirect') }}" class="button-primary whitespace-nowrap">Se connecter</a>
            @endguest
        </div>
    </div>
</header>

@auth
<main class="w-full z-10 relative py-4 md:py-6 px-4 lg:px-6 flex-1">
    <div @class(['max-w-7xl mx-auto w-full', 'lg:grid lg:grid-cols-[280px_auto_280px] lg:gap-6 lg:justify-center' => $sidebars])>
        @if($sidebars)
            {{-- Left sidebar: desktop only --}}
            <aside class="hidden lg:block">
                <livewire:leaderboard type="weekly" />
            </aside>
        @endif

        {{-- Center column --}}
        <div class="w-full max-w-2xl mx-auto lg:w-2xl">
            <div class="bg-surface rounded-3xl p-5 shadow-lg shadow-black/5">
                {{ $slot }}
            </div>
        </div>

        @if($sidebars)
            {{-- Right sidebar: desktop only --}}
            <aside class="hidden lg:block">
                <livewire:leaderboard type="alltime" />
            </aside>
        @endif
    </div>
</main>

{{-- Mobile: bottom tab bar --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-30 bg-surface border-t border-line pb-[env(safe-area-inset-bottom)]" aria-label="Navigation principale">
    <ul class="grid grid-cols-3 max-w-md mx-auto">
        @foreach([
            ['route' => 'practice', 'match' => 'practice*', 'icon' => '✏️', 'label' => 'Pratiquer'],
            ['route' => 'leaderboard', 'match' => 'leaderboard', 'icon' => '🏆', 'label' => 'Classement'],
            ['route' => 'dashboard', 'match' => 'dashboard', 'icon' => '👤', 'label' => 'Profil'],
        ] as $tab)
            @php $active = request()->routeIs($tab['match']); @endphp
            <li>
                <a href="{{ route($tab['route']) }}" @if($active) aria-current="page" @endif
                   @class(['flex flex-col items-center gap-0.5 py-2 text-xs font-bold border-t-4 -mt-px', 'border-primary text-accent' => $active, 'border-transparent text-ink-soft' => ! $active])>
                    <span class="text-xl" aria-hidden="true">{{ $tab['icon'] }}</span>
                    {{ $tab['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
@else
<main class="w-full z-10 relative flex justify-center py-4 md:py-10 px-4 flex-1">
    <div class="w-full max-w-2xl self-start rounded-3xl bg-surface p-5 md:p-8 shadow-lg shadow-black/5">
        {{ $slot }}
    </div>
</main>
@endauth


<footer class="w-full z-10 relative py-4 text-center text-ink-soft text-sm">
    &copy; {{ date('Y') }} {{ config('app.name', 'La Conjugerie') }}. Tous droits réservés.
</footer>

<!-- Livewire Scripts -->
@livewireScripts

<!-- Additional Scripts -->
@stack('scripts')
</body>
</html>
