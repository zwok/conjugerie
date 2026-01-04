<x-main-layout>
    <div class="text-center space-y-6">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-secondary">
            Bienvenue à <span class="text-transparent bg-clip-text bg-primary">La Conjugerie</span>
        </h1>
        <p class="text-lg text-gray-700 max-w-2xl mx-auto">
            Apprends et pratique la conjugaison des verbes français de manière simple et ludique.
            Choisis un verbe, un temps, et entraine-toi à ton rythme.
        </p>
        <ul class="text-left max-w-xl mx-auto space-y-2 text-gray-700">
            <li>• Pratiquer les conjugaisons les plus courantes</li>
            <li>• Tester tes connaissances selon la personne et le temps</li>
            <li>• Suivre tes progrès au fil du temps</li>
        </ul>

        @guest
            <div class="pt-2">
                <a href="{{ route('smartschool.redirect') }}" class="button-primary inline-block">
                    Se connecter avec Smartschool
                </a>
            </div>
        @endguest

        @auth
            <div class="flex items-center justify-center gap-3 pt-2">
                <a href="{{ route('dashboard') }}" class="button-secondary">Compte</a>
                <a href="{{ route('practice') }}" class="button-primary">Pratiquer</a>
            </div>
        @endauth
    </div>
</x-main-layout>
