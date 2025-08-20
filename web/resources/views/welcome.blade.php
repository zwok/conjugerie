<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'La Conjugerie') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 min-h-screen">
    <header class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ config('app.name', 'La Conjugerie') }}</h1>
                <nav>
                    <ul class="flex space-x-4">
                        <li><a href="{{ route('practice') }}" class="hover:underline">Practice</a></li>
                        @if (Route::has('welcome.nl'))
                            <li><a href="{{ route('welcome.nl') }}" class="hover:underline">Nederlands</a></li>
                        @endif
                        @auth
                            <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="hover:underline">Logout</button>
                                </form>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}" class="hover:underline">Inloggen</a></li>
                            <li><a href="{{ route('register') }}" class="hover:underline">Registreren</a></li>
                        @endauth
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6">Welkom bij La Conjugerie</h2>

                <p class="mb-6 text-gray-700">
                    La Conjugerie is een hulpmiddel om Franse werkwoorden te leren. Met deze tool kun je:
                </p>

                <ul class="list-disc pl-5 mb-6 space-y-2 text-gray-700">
                    <li>Franse werkwoordvervoegingen oefenen</li>
                    <li>Je kennis van verschillende tijden en vormen testen</li>
                    <li>Je voortgang bijhouden en je vaardigheden verbeteren</li>
                </ul>

                <p class="mb-8 text-gray-700">
                    Of je nu een beginner bent of je kennis wilt opfrissen, La Conjugerie helpt je om de Franse werkwoordvervoegingen onder de knie te krijgen.
                </p>

                <a href="{{ route('practice') }}" class="inline-block bg-indigo-700 hover:bg-indigo-800 text-white font-medium py-2 px-6 rounded-md transition-colors">
                    Begin met oefenen
                </a>
            </div>
        </div>
    </main>

    <footer class="bg-white shadow-md mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'La Conjugerie') }} - Een hulpmiddel voor het leren van Franse werkwoordvervoegingen
            </p>
        </div>
    </footer>
</body>
</html>
