@php
    $goalPercent = $weeklyGoal > 0 ? min(100, (int) round($weekXp / $weeklyGoal * 100)) : 100;
    $ringLength = 2 * M_PI * 42;
@endphp

<x-main-layout>
    <div class="max-w-2xl mx-auto p-0 md:p-4 space-y-4">
        <div class="flex items-center gap-4">
            <x-avatar :initials="\App\Support\Avatar::initials($user->name)" :color="\App\Support\Avatar::color($user->id)" size="size-14 text-xl" />
            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-accent truncate">Salut {{ \Illuminate\Support\Str::before($user->name, ' ') }} !</h1>
                <p class="text-sm font-semibold text-ink-soft">{{ $user->mainGroup?->name ?? 'Pas encore de classe' }}</p>
            </div>
        </div>

        <!-- Streak, XP, accuracy -->
        <div class="grid grid-cols-3 gap-3 text-center">
            <div class="card px-2">
                <div class="text-2xl sm:text-3xl font-bold text-orange-500 whitespace-nowrap">🔥 {{ $streak }}</div>
                <div class="text-xs font-semibold text-ink-soft mt-1">{{ $streak > 1 ? 'jours d\'affilée' : 'jour d\'affilée' }}</div>
            </div>
            <div class="card px-2">
                <div class="text-2xl sm:text-3xl font-bold text-accent whitespace-nowrap">⚡ {{ $totalXp }}</div>
                <div class="text-xs font-semibold text-ink-soft mt-1">XP au total</div>
            </div>
            <div class="card px-2 flex flex-col items-center">
                <div class="relative size-16">
                    <svg viewBox="0 0 100 100" class="size-16 -rotate-90" role="img" aria-label="Précision globale : {{ $globalPercentage }}%">
                        <circle cx="50" cy="50" r="42" fill="none" stroke="var(--line)" stroke-width="12"/>
                        <circle cx="50" cy="50" r="42" fill="none" stroke="var(--color-primary)" stroke-width="12" stroke-linecap="round"
                                stroke-dasharray="{{ $ringLength }}" stroke-dashoffset="{{ $ringLength * (1 - $globalPercentage / 100) }}"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-ink">{{ round($globalPercentage) }}%</span>
                </div>
                <div class="text-xs font-semibold text-ink-soft mt-1">précision</div>
            </div>
        </div>

        <!-- Weekly goal -->
        <div class="card">
            <div class="flex items-baseline justify-between mb-2">
                <h2 class="font-bold text-accent">Objectif de la semaine</h2>
                <span class="text-sm font-bold text-ink-soft tabular-nums">{{ $weekXp }} / {{ $weeklyGoal }} XP</span>
            </div>
            <div class="h-4 rounded-full bg-line overflow-hidden" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $goalPercent }}" aria-label="Objectif de la semaine">
                <div class="h-full rounded-full bg-primary" style="width: {{ $goalPercent }}%"></div>
            </div>
            <p class="text-sm text-ink-soft mt-2">
                @if($goalPercent >= 100)
                    Objectif atteint, bravo ! 🎉
                @elseif($weekAnswers === 0)
                    Commence ta semaine avec une première manche !
                @else
                    Encore {{ $weeklyGoal - $weekXp }} XP pour atteindre ton objectif.
                @endif
            </p>
            <div class="flex justify-between mt-4">
                @foreach($weekDays as $day)
                    <div class="flex flex-col items-center gap-1">
                        <span @class([
                            'size-8 rounded-full flex items-center justify-center text-sm font-bold',
                            'bg-orange-500 text-white' => $day['done'],
                            'bg-line text-ink-soft' => ! $day['done'],
                            'ring-2 ring-accent ring-offset-2 ring-offset-surface-2' => $day['today'],
                        ])>{{ $day['done'] ? '✓' : '' }}</span>
                        <span class="text-xs font-semibold text-ink-soft">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <a href="{{ route('practice') }}" class="block w-full button-primary text-lg py-3">
            Pratiquer
        </a>

        <!-- Statistics -->
        <div class="grid grid-cols-2 gap-3">
            @foreach([
                ['title' => 'Cette semaine', 'answers' => $weekAnswers, 'correct' => $weekCorrect, 'percent' => $weekPercentage],
                ['title' => 'Depuis le début', 'answers' => $totalAnswers, 'correct' => $correctAnswers, 'percent' => $globalPercentage],
            ] as $stats)
                <div class="card">
                    <h2 class="font-bold text-accent mb-2">{{ $stats['title'] }}</h2>
                    <dl class="space-y-1 text-sm">
                        <div class="flex justify-between"><dt class="text-ink-soft">Réponses</dt><dd class="font-bold text-ink">{{ $stats['answers'] }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-soft">Correctes</dt><dd class="font-bold text-ink">{{ $stats['correct'] }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-soft">Précision</dt><dd class="font-bold text-ink">{{ $stats['percent'] }}%</dd></div>
                    </dl>
                </div>
            @endforeach
        </div>

        <!-- Most-missed conjugations -->
        <div class="card">
            <h2 class="font-bold text-accent mb-3">Verbes à revoir</h2>
            @forelse($toReview as $item)
                <div class="flex items-center justify-between gap-3 py-2 border-t border-line first:border-t-0">
                    <div class="min-w-0">
                        <p class="font-bold text-ink truncate">{{ $item->conjugation->pronoun() }} {{ $item->conjugation->conjugated_form }}</p>
                        <p class="text-xs font-semibold text-ink-soft">{{ $item->conjugation->verb->infinitive }} · {{ $item->conjugation->tense->name }}</p>
                    </div>
                    <span class="chip bg-red-100 text-red-700 whitespace-nowrap">{{ $item->misses }} {{ $item->misses > 1 ? 'erreurs' : 'erreur' }}</span>
                </div>
            @empty
                <p class="text-sm text-ink-soft">Aucune erreur pour l'instant. Continue comme ça !</p>
            @endforelse
        </div>

        <!-- Account details -->
        <details class="card">
            <summary class="font-bold text-accent cursor-pointer">Mon compte Smartschool</summary>
            <dl class="mt-3 space-y-2 text-sm">
                <div class="flex justify-between gap-3"><dt class="text-ink-soft">Nom</dt><dd class="font-bold text-ink text-right">{{ $user->name }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-ink-soft">ID Smartschool</dt><dd class="font-bold text-ink text-right">{{ $user->smartschool_id ?? '—' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-ink-soft">Nom d'utilisateur</dt><dd class="font-bold text-ink text-right">{{ $user->smartschool_username ?? '—' }}</dd></div>
                <div class="flex justify-between gap-3">
                    <dt class="text-ink-soft">Plateforme</dt>
                    <dd class="font-bold text-ink text-right break-all">
                        @if($user->smartschool_platform)
                            <a href="{{ $user->smartschool_platform }}" target="_blank" rel="noopener" class="underline hover:no-underline">{{ $user->smartschool_platform }}</a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between gap-3"><dt class="text-ink-soft">Classe</dt><dd class="font-bold text-ink text-right">{{ $user->mainGroup?->name ?? '—' }}</dd></div>
            </dl>
        </details>

        {{-- Mobile: these links live in the header on desktop --}}
        <div class="lg:hidden flex flex-col gap-3">
            @if($user->is_teacher)
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="button-secondary">Admin</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="button-grey w-full">Se déconnecter</button>
            </form>
        </div>
    </div>
</x-main-layout>
