<x-main-layout :sidebars="false">
    <div x-data="{ tab: 'weekly' }">
        <h1 class="text-3xl font-bold text-center mb-6 text-accent">Classement</h1>

        <div class="flex bg-surface-2 rounded-full p-1 mb-4" role="tablist">
            <button
                @click="tab = 'weekly'" role="tab" :aria-selected="tab === 'weekly'"
                :class="tab === 'weekly' ? 'bg-secondary text-white' : 'text-ink-soft hover:text-ink'"
                class="flex-1 text-sm font-semibold py-2 rounded-full text-center transition-colors cursor-pointer"
            >
                Semaine
            </button>
            <button
                @click="tab = 'alltime'" role="tab" :aria-selected="tab === 'alltime'"
                :class="tab === 'alltime' ? 'bg-secondary text-white' : 'text-ink-soft hover:text-ink'"
                class="flex-1 text-sm font-semibold py-2 rounded-full text-center transition-colors cursor-pointer"
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
</x-main-layout>
