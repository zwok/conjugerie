<x-main-layout>
    <div class="text-center space-y-6">
        <div class="text-5xl" aria-hidden="true">👋</div>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-accent leading-tight">
            Salut !<br><span class="text-primary">Prêt·e à conjuguer ?</span>
        </h1>
        <p class="text-lg text-ink-soft max-w-md mx-auto">
            Des manches de 10 questions, des XP, des séries et un classement avec ta classe.
            Cinq minutes par jour, et les verbes n'auront plus de secrets pour toi.
        </p>

        {{-- Live numbers as a nudge --}}
        <div class="grid grid-cols-3 gap-3">
            <div class="card px-2">
                <div class="text-2xl sm:text-3xl font-bold text-accent tabular-nums">{{ number_format($stats['conjugations'], 0, ',', ' ') }}</div>
                <div class="text-xs font-semibold text-ink-soft mt-1">conjugaisons à maîtriser</div>
            </div>
            <div class="card px-2">
                <div class="text-2xl sm:text-3xl font-bold text-accent tabular-nums">{{ number_format($stats['answersTotal'], 0, ',', ' ') }}</div>
                <div class="text-xs font-semibold text-ink-soft mt-1">réponses données par les élèves</div>
            </div>
            <div class="card px-2">
                <div class="text-2xl sm:text-3xl font-bold text-accent tabular-nums">{{ $stats['activeStudents'] }}</div>
                <div class="text-xs font-semibold text-ink-soft mt-1">{{ $stats['activeStudents'] === 1 ? 'élève actif cette semaine' : 'élèves actifs cette semaine' }}</div>
            </div>
        </div>

        @if($topGroups->isNotEmpty())
            <div class="card text-left">
                <h2 class="font-bold text-accent mb-3">🔥 Les classes les plus actives cette semaine</h2>
                <ol class="space-y-2">
                    @foreach($topGroups as $group)
                        <li class="flex items-center gap-3">
                            <span class="w-6 text-center">{{ ['🥇', '🥈', '🥉'][$loop->index] }}</span>
                            <span class="flex-1 font-bold text-ink">{{ $group->name }}</span>
                            <span class="chip bg-primary text-white">{{ $group->xp }} XP</span>
                        </li>
                    @endforeach
                </ol>
                <p class="text-sm text-ink-soft mt-3">Ta classe n'y est pas ? À toi de la faire monter.</p>
            </div>
        @elseif($stats['answersThisWeek'] === 0)
            <div class="card">
                <p class="font-bold text-accent">Personne n'a encore joué cette semaine.</p>
                <p class="text-sm text-ink-soft mt-1">Sois le premier ou la première à marquer des XP pour ta classe !</p>
            </div>
        @endif

        @guest
            <a href="{{ route('smartschool.redirect') }}" class="button-primary block text-lg py-4">
                Commencer avec Smartschool
            </a>
        @endguest

        @auth
            <a href="{{ route('practice') }}" class="button-primary block text-lg py-4">Pratiquer</a>
        @endauth
    </div>
</x-main-layout>
