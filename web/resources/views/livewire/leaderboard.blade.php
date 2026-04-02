<div wire:poll.60s class="h-full">
    <div class="bg-secondary rounded-lg p-4 shadow-sm h-full flex flex-col text-white">
        <h3 class="text-base font-bold text-white mb-3">
            @if($type === 'weekly')
                Classement de la semaine
            @else
                Classement global
            @endif
        </h3>

        {{-- Scope toggle --}}
        <div class="flex bg-white/10 rounded-full p-1 mb-4">
            <button
                wire:click="toggleScope"
                class="flex-1 text-xs font-semibold py-1.5 rounded-full text-center transition-colors {{ $scope === 'class' ? 'bg-white text-secondary' : 'text-white/70 hover:text-white' }}"
            >
                Ma classe
            </button>
            <button
                wire:click="toggleScope"
                class="flex-1 text-xs font-semibold py-1.5 rounded-full text-center transition-colors {{ $scope === 'year' ? 'bg-white text-secondary' : 'text-white/70 hover:text-white' }}"
            >
                Mon année
            </button>
        </div>

        {{-- Leaderboard list --}}
        @if(empty($leaderboard))
            <p class="text-sm text-white/50 text-center py-4">Pas encore de résultats</p>
        @else
            <ol class="space-y-1.5">
                @foreach($leaderboard as $index => $entry)
                    @php
                        $rank = $index + 1;
                        $isCurrentUser = $entry['user_id'] === auth()->id();
                        $medal = match($rank) {
                            1 => '🥇',
                            2 => '🥈',
                            3 => '🥉',
                            default => null,
                        };
                    @endphp
                    <li class="flex items-center gap-2 py-1.5 px-2 rounded-md text-sm {{ $isCurrentUser ? 'bg-white/15 font-bold' : '' }}">
                        <span class="w-6 text-center shrink-0">
                            @if($medal)
                                {{ $medal }}
                            @else
                                <span class="text-white/50">{{ $rank }}</span>
                            @endif
                        </span>
                        <span class="truncate flex-1 {{ $isCurrentUser ? 'text-primary-light' : 'text-white' }}">
                            {{ $entry['name'] }}
                        </span>
                        <span class="text-xs font-semibold text-white/70 shrink-0">
                            {{ $entry['count'] }}
                        </span>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
