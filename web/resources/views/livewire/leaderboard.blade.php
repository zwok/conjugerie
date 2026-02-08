<div wire:poll.60s>
    <div class="bg-white rounded-lg p-4 shadow-sm">
        <h3 class="text-base font-bold text-secondary mb-3">
            @if($type === 'weekly')
                Classement de la semaine
            @else
                Classement global
            @endif
        </h3>

        {{-- Scope toggle --}}
        <div class="flex bg-gray-100 rounded-full p-1 mb-4">
            <button
                wire:click="toggleScope"
                class="flex-1 text-xs font-semibold py-1.5 rounded-full text-center transition-colors {{ $scope === 'class' ? 'bg-secondary text-white' : 'text-gray-500 hover:text-gray-700' }}"
            >
                Ma classe
            </button>
            <button
                wire:click="toggleScope"
                class="flex-1 text-xs font-semibold py-1.5 rounded-full text-center transition-colors {{ $scope === 'year' ? 'bg-secondary text-white' : 'text-gray-500 hover:text-gray-700' }}"
            >
                Mon année
            </button>
        </div>

        {{-- Leaderboard list --}}
        @if(empty($leaderboard))
            <p class="text-sm text-gray-400 text-center py-4">Pas encore de résultats</p>
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
                    <li class="flex items-center gap-2 py-1.5 px-2 rounded-md text-sm {{ $isCurrentUser ? 'bg-primary/10 font-bold' : '' }}">
                        <span class="w-6 text-center shrink-0">
                            @if($medal)
                                {{ $medal }}
                            @else
                                <span class="text-gray-400">{{ $rank }}</span>
                            @endif
                        </span>
                        <span class="truncate flex-1 {{ $isCurrentUser ? 'text-secondary' : 'text-dark' }}">
                            {{ $entry['name'] }}
                        </span>
                        <span class="text-xs font-semibold text-gray-500 shrink-0">
                            {{ $entry['count'] }}
                        </span>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
