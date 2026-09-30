<div wire:poll.60s="loadLeaderboard" class="h-full">
    <div class="bg-secondary rounded-3xl p-4 shadow-lg shadow-black/5 flex flex-col text-white">
        <h3 class="text-base font-bold text-white mb-3 text-center">
            @if($type === 'weekly')
                🏆 Classement de la semaine
            @else
                🌍 Classement global
            @endif
        </h3>

        {{-- Scope toggle --}}
        <div class="flex bg-white/10 rounded-full p-1 mb-4">
            <button
                wire:click="setScope('class')"
                class="flex-1 text-xs font-semibold py-1.5 rounded-full text-center transition-colors cursor-pointer {{ $scope === 'class' ? 'bg-white text-secondary' : 'text-white/70 hover:text-white' }}"
            >
                Ma classe
            </button>
            <button
                wire:click="setScope('year')"
                class="flex-1 text-xs font-semibold py-1.5 rounded-full text-center transition-colors cursor-pointer {{ $scope === 'year' ? 'bg-white text-secondary' : 'text-white/70 hover:text-white' }}"
            >
                Mon année
            </button>
        </div>

        {{-- Leaderboard list --}}
        @if(empty($leaderboard))
            <p class="text-sm text-white/60 text-center py-4">Pas encore de résultats. À toi de jouer !</p>
        @else
            <ol class="space-y-1 lg:max-h-[70vh] overflow-y-auto">
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
                    <li wire:key="row-{{ $entry['user_id'] }}"
                        class="flex items-center gap-2 py-1.5 px-2 rounded-xl text-sm {{ $isCurrentUser ? 'bg-white text-secondary font-bold shadow' : '' }}">
                        <span class="w-6 text-center shrink-0">
                            @if($medal)
                                {{ $medal }}
                            @else
                                <span class="{{ $isCurrentUser ? '' : 'text-white/60' }}">{{ $rank }}</span>
                            @endif
                        </span>
                        <x-avatar :initials="$entry['initials']" :color="$entry['color']" />
                        <span class="truncate flex-1">
                            {{ $entry['name'] }}
                            @if($isCurrentUser)
                                <span class="ml-1 rounded-full bg-primary px-1.5 py-0.5 text-[10px] uppercase text-white">Toi</span>
                            @endif
                        </span>
                        @if($entry['delta'] > 0)
                            <span class="text-[11px] font-bold shrink-0 {{ $isCurrentUser ? 'text-green-600' : 'text-green-300' }}" title="Places gagnées aujourd'hui">▲{{ $entry['delta'] }}</span>
                        @elseif($entry['delta'] < 0)
                            <span class="text-[11px] font-bold shrink-0 {{ $isCurrentUser ? 'text-red-600' : 'text-red-300' }}" title="Places perdues aujourd'hui">▼{{ abs($entry['delta']) }}</span>
                        @endif
                        <span class="text-xs font-semibold shrink-0 tabular-nums {{ $isCurrentUser ? '' : 'text-white/80' }}">
                            {{ $entry['xp'] }} XP
                        </span>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
