@php
    // Each set gets a stable emoji and colour based on its id
    $setEmojis = ['🚀', '🎯', '⚡', '🌟', '🎨', '🧩', '🎸', '🦊', '🍀', '🔮'];
    $setColors = ['bg-orange-500', 'bg-violet-500', 'bg-sky-500', 'bg-rose-500', 'bg-emerald-500', 'bg-amber-500', 'bg-indigo-500', 'bg-teal-500'];
@endphp

<x-main-layout>
    <div class="max-w-2xl mx-auto p-0 md:p-4">
        <h1 class="text-3xl font-bold text-center mb-6 text-accent">On s'entraîne ?</h1>

        <!-- Free Practice Mode -->
        <a href="{{ route('practice.free') }}"
           class="flex items-center gap-4 rounded-2xl bg-primary p-5 text-white shadow-[0_4px_0_var(--color-primary-dark)] transition hover:brightness-105 active:translate-y-1 active:shadow-none">
            <span class="text-4xl" aria-hidden="true">🎲</span>
            <span class="flex-1">
                <span class="block text-xl font-bold">Pratique libre</span>
                <span class="block text-sm text-white/90">Tous les verbes, tous les temps, au hasard.</span>
            </span>
            <span class="text-2xl" aria-hidden="true">›</span>
        </a>

        <!-- Available Sets -->
        <h2 class="text-lg font-bold text-accent mt-8 mb-3">Les séries de ta classe</h2>

        @if($conjugationSets->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($conjugationSets as $set)
                    <a href="{{ route('practice.set', $set->id) }}"
                       class="card flex flex-col gap-3 border-2 border-b-4 border-line transition hover:-translate-y-0.5 hover:border-primary active:translate-y-0.5 active:border-b-2">
                        <div class="flex items-start gap-3">
                            <span class="size-11 shrink-0 rounded-xl {{ $setColors[$set->id % count($setColors)] }} flex items-center justify-center text-2xl" aria-hidden="true">{{ $setEmojis[$set->id % count($setEmojis)] }}</span>
                            <div class="min-w-0">
                                <h3 class="font-bold leading-tight text-ink">{{ $set->name }}</h3>
                                <p class="text-xs font-semibold text-ink-soft mt-0.5">
                                    {{ $set->verbs_count }} {{ $set->verbs_count > 1 ? 'verbes' : 'verbe' }} · {{ $set->tenses_count }} temps
                                </p>
                            </div>
                        </div>
                        @if($set->description)
                            <p class="text-sm text-ink-soft">{{ $set->description }}</p>
                        @endif
                        <div class="mt-auto flex items-center gap-2">
                            <div class="flex-1 h-2.5 rounded-full bg-line overflow-hidden" role="progressbar"
                                 aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $set->progress }}" aria-label="Progression">
                                <div class="h-full rounded-full bg-primary" style="width: {{ $set->progress }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-ink-soft tabular-nums">{{ $set->progress === 100 ? '✅' : $set->progress . '%' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="card text-center text-ink-soft">
                Pas encore de série pour ta classe. Lance-toi en pratique libre !
            </div>
        @endif
    </div>
</x-main-layout>
