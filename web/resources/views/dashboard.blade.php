<x-main-layout>
    <div class="max-w-2xl mx-auto p-0 md:p-6">
        <h1 class="text-3xl font-bold text-center mb-8 text-secondary">Mon compte</h1>

        <div class="mb-8">
            <!-- Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-secondary-light p-6 rounded-lg">
                    <h2 class="text-lg font-bold text-secondary mb-3 text-center">Cette semaine</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-dark">Réponses:</span>
                            <span class="text-secondary font-bold text-xl">{{ $weekAnswers }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-dark">Correctes:</span>
                            <span class="text-secondary font-bold text-xl">{{ $weekCorrect }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-dark">Pourcentage:</span>
                            <span class="text-secondary font-bold text-xl">{{ $weekPercentage }}%</span>
                        </div>
                    </div>
                </div>
                <div class="bg-secondary-light p-6 rounded-lg">
                    <h2 class="text-lg font-bold text-secondary mb-3 text-center">Global</h2>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-dark">Réponses:</span>
                            <span class="text-secondary font-bold text-xl">{{ $totalAnswers }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-dark">Correctes:</span>
                            <span class="text-secondary font-bold text-xl">{{ $correctAnswers }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-dark">Pourcentage:</span>
                            <span class="text-secondary font-bold text-xl">{{ $globalPercentage }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-secondary-light p-6 rounded-lg mb-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-dark font-medium">Nom:</span>
                        <span class="text-secondary font-bold">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-dark font-medium">ID Smartschool:</span>
                        <span class="text-secondary font-bold">{{ $user->smartschool_id ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-dark font-medium">Nom d'utilisateur:</span>
                        <span class="text-secondary font-bold">{{ $user->smartschool_username ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-dark font-medium">Plateforme Smartschool:</span>
                        <span class="text-secondary font-bold">
                            @if($user->smartschool_platform)
                                <a href="{{ $user->smartschool_platform }}" target="_blank" rel="noopener" class="underline hover:no-underline">{{ $user->smartschool_platform }}</a>
                            @else
                                —
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-secondary-light p-6 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-dark font-medium">Votre classe:</span>
                    <span class="text-secondary font-bold">
                        @if($user->mainGroup)
                            {{ $user->mainGroup->name }}
                        @else
                            —
                        @endif
                    </span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('practice') }}" class="block w-full button-primary rounded-full text-center text-lg py-3">
                    Pratiquer
                </a>
            </div>
        </div>
    </div>
</x-main-layout>
