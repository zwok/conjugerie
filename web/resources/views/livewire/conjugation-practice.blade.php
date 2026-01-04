<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-8 text-secondary">Pratiquer</h1>

    @if ($currentConjugation)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div class="text-lg font-medium">
                    <span class="text-dark">Verbe:</span>
                    <span class="text-secondary font-bold">{{ $infinitive }}</span>
                </div>
                <div class="text-lg font-medium">
                    <span class="text-dark">Temps:</span>
                    <span class="text-secondary font-bold">{{ $this->getTenseName() }}</span>
                </div>
            </div>

            <div class="bg-secondary-light p-6 rounded-lg mb-6">
                <p class="text-xl text-center font-bold text-secondary">
                    {{ $this->getPersonPronoun() }} <span class="text-gray-500">({{ $infinitive }})</span>
                </p>
            </div>

            <div class="mb-2" x-data x-on:refocus-answer.window="$refs.answer?.focus(); $refs.answer?.select();">
                <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">Votre Réponse:</label>
                <input
                    type="text"
                    id="answer"
                    wire:key="answer-input"
                    wire:model.live="studentAnswer"
                    wire:keydown.enter="checkAnswer"
                    class="w-full px-4 py-2 border border-gray-300 rounded-full text-center text-2xl focus:outline-0"
                    placeholder="Entrez la conjugaison correcte"
                    x-ref="answer"
                    autofocus
                    @disabled($remainingTries === 0)
                    autocomplete="off"
                >
            </div>

            <div class="mt-4 mb-6 text-center">
                @if ($showFeedback)
                    <div class="p-4 rounded-full {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {!! $message !!}
                    </div>
                @endif

            </div>

            <div class="flex space-x-4">
                @if($remainingTries > 0)
                    <button
                        wire:click="checkAnswer"
                        class="flex-1 rounded-full {{ ($questionDone || $studentAnswer === '') ? 'button-secondary opacity-50 cursor-not-allowed' : 'button-secondary' }}"
                        @disabled($questionDone || $studentAnswer === '')
>
                        Vérifier
                    </button>
                @endif

                @if($remainingTries === 0)
                    <button
                        wire:click="getNewConjugation"
                        class="flex-1 button-secondary rounded-full"
>
                        Suivant
                    </button>
                @endif
            </div>

        </div>
    @else
        <div class="p-4 bg-yellow-100 text-yellow-800 rounded-md">
            {{ $message }}
        </div>
    @endif

    <div class="mt-8 text-center text-sm text-gray-500">
        <p>Pratiquez régulièrement vos conjugaisons de verbes français pour améliorer vos compétences !</p>
    </div>
</div>
