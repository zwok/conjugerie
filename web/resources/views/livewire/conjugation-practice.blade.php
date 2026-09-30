@php
    // One colour per tense so learners recognise it at a glance
    $tenseColors = [
        'present' => 'bg-emerald-500',
        'imparfait' => 'bg-violet-500',
        'passe_compose' => 'bg-orange-500',
        'futur_simple' => 'bg-sky-500',
        'futur_proche' => 'bg-cyan-600',
        'imperatif_present' => 'bg-rose-500',
    ];
    $answered = $questionNumber - 1 + ($questionDone ? 1 : 0);
@endphp

<div class="max-w-2xl mx-auto p-0 md:p-4"
     x-data="{ shake: false, pulse: false }"
     x-on:refocus-answer.window="$nextTick(() => { $refs.answer?.focus(); })"
     x-on:answer-wrong.window="shake = true; setTimeout(() => shake = false, 450)"
     x-on:answer-correct.window="pulse = true; setTimeout(() => pulse = false, 400)">

    @if ($feedback === 'empty')
        <div class="p-4 bg-yellow-100 text-yellow-800 rounded-2xl text-center">
            Aucune conjugaison disponible pour le moment.
        </div>
        <a href="{{ route('practice') }}" class="button-secondary block mt-4">Retour</a>
    @elseif ($roundFinished)
        {{-- Round summary --}}
        @php $ratio = $roundCorrect / $roundSize; @endphp
        <div class="text-center py-4 animate-pop">
            <div class="text-6xl mb-3" aria-hidden="true">{{ $ratio >= 0.9 ? '🏆' : ($ratio >= 0.7 ? '🎉' : ($ratio >= 0.4 ? '💪' : '🌱')) }}</div>
            <h1 class="text-3xl font-bold text-accent mb-1">Manche terminée !</h1>
            <p class="text-ink-soft mb-6">
                {{ $ratio >= 0.9 ? 'Presque parfait, bravo !' : ($ratio >= 0.7 ? 'Super manche !' : ($ratio >= 0.4 ? 'Pas mal, tu progresses !' : 'Continue, ça va venir !')) }}
            </p>

            <div class="grid grid-cols-3 gap-3 mb-8">
                <div class="card">
                    <div class="text-2xl font-bold text-accent">{{ $roundCorrect }}/{{ $roundSize }}</div>
                    <div class="text-xs font-semibold text-ink-soft">Score</div>
                </div>
                <div class="card">
                    <div class="text-2xl font-bold text-accent">+{{ $roundXp }}</div>
                    <div class="text-xs font-semibold text-ink-soft">XP gagnés</div>
                </div>
                <div class="card">
                    <div class="text-2xl font-bold text-accent">🔥 {{ $bestCombo }}</div>
                    <div class="text-xs font-semibold text-ink-soft">Meilleure série</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('practice') }}" class="button-secondary flex-1 py-3">Changer de mode</a>
                <button wire:click="restartRound" class="button-primary flex-1 py-3 text-lg" autofocus>Rejouer</button>
            </div>
        </div>
    @else
        {{-- Round progress --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('practice') }}" class="text-2xl leading-none text-ink-soft hover:text-ink" aria-label="Quitter la manche" title="Quitter">&times;</a>
            <div class="flex-1 h-4 rounded-full bg-surface-2 overflow-hidden" role="progressbar"
                 aria-valuemin="0" aria-valuemax="{{ $roundSize }}" aria-valuenow="{{ $answered }}" aria-label="Progression de la manche">
                <div class="h-full rounded-full bg-primary transition-all duration-500" style="width: {{ $answered / $roundSize * 100 }}%"></div>
            </div>
            <span class="text-sm font-bold text-ink-soft tabular-nums">{{ $questionNumber }}/{{ $roundSize }}</span>
            @if ($combo >= 2)
                <span class="chip bg-orange-100 text-orange-700 animate-pop" wire:key="combo-{{ $combo }}">🔥 {{ $combo }}</span>
            @endif
        </div>

        {{-- Question --}}
        <div class="text-center mb-5">
            <span class="chip text-white {{ $tenseColors[$tense] ?? 'bg-secondary' }}">{{ $this->getTenseName() }}</span>
            @if ($setName)
                <p class="mt-2 text-xs font-semibold text-ink-soft">{{ $setName }}</p>
            @endif
        </div>

        <div class="card mb-4 px-4 py-8 border-2 transition-colors
                    {{ $feedback === 'correct' ? 'border-primary' : (in_array($feedback, ['retry', 'reveal']) ? 'border-red-400' : 'border-transparent') }}"
             :class="{ 'animate-shake': shake, 'animate-pop': pulse }">
            <label for="answer" class="sr-only">Ta réponse</label>
            <p class="flex flex-wrap items-baseline justify-center gap-x-3 gap-y-3 text-2xl md:text-3xl font-bold text-accent">
                <span>{{ $this->getPersonPronoun() }}</span>
                <input
                    type="text"
                    id="answer"
                    wire:key="answer-input"
                    wire:model="studentAnswer"
                    wire:keydown.enter="submit"
                    class="w-52 max-w-full bg-transparent border-0 border-b-4 border-dashed border-accent/50 px-1 pb-1 text-center font-bold text-ink placeholder:text-ink-soft/40 placeholder:font-medium focus:outline-0 focus:border-solid focus:border-primary"
                    placeholder="…"
                    x-ref="answer"
                    autofocus
                    @readonly($questionDone)
                    autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false"
                >
                <span class="text-lg md:text-xl font-semibold text-ink-soft">({{ $infinitive }})</span>
            </p>
        </div>

        @unless ($questionDone)
            <x-accent-bar class="mb-4" />
        @endunless

        {{-- Feedback --}}
        <div class="min-h-16 mb-4" aria-live="polite">
            @if ($feedback === 'correct')
                <div class="flex items-center justify-between gap-3 rounded-2xl bg-green-100 text-green-800 px-5 py-3 font-bold animate-pop">
                    <span>✅ {{ $praise }} <span class="font-medium">« {{ $correctForm }} »</span></span>
                    <span class="chip bg-primary text-white whitespace-nowrap">+{{ $lastXp }} XP</span>
                </div>
            @elseif ($feedback === 'retry')
                <div class="rounded-2xl bg-red-100 text-red-800 px-5 py-3 font-bold">
                    Presque ! Encore {{ $remainingTries === 1 ? 'un essai' : $remainingTries . ' essais' }}.
                </div>
            @elseif ($feedback === 'reveal')
                <div class="rounded-2xl bg-red-100 text-red-800 px-5 py-3">
                    La bonne réponse : <strong>{{ $correctForm }}</strong>. Recopie-la pour continuer.
                </div>
            @elseif ($feedback === 'copied')
                <div class="rounded-2xl bg-sky-100 text-sky-800 px-5 py-3 font-bold animate-pop">
                    C'est noté ! Tu l'auras la prochaine fois 💪
                </div>
            @endif
        </div>

        @if ($questionDone)
            <button wire:click="getNewConjugation" class="button-primary w-full py-3 text-lg">
                {{ $questionNumber >= $roundSize ? 'Voir mon score' : 'Suivant' }}
            </button>
        @else
            <button wire:click="checkAnswer" class="button-secondary w-full py-3 text-lg">Vérifier</button>
        @endif

        <p class="mt-4 text-center text-xs text-ink-soft hidden md:block">Astuce : appuie sur Entrée pour valider et passer à la suite.</p>
    @endif
</div>
