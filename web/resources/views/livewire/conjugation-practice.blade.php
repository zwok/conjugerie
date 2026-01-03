<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-8 text-secondary">Pratiquer</h1>

    @if ($currentConjugation)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div class="text-lg font-medium">
                    <span class="text-dark">Verb:</span>
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

            <div class="mb-6">
                <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">Votre Réponse:</label>
                <input
                    type="text"
                    id="answer"
                    wire:model="studentAnswer"
                    wire:keydown.enter="checkAnswer"
                    class="w-full px-4 py-2 border border-gray-300 rounded-full text-center text-2xl focus:outline-0"
                    placeholder="Entrez la conjugaison correcte"
                    autocomplete="off"
                >
            </div>

            <div class="flex space-x-4">
                <button
                    wire:click="checkAnswer"
                    class="flex-1 button-secondary"
                >
                    Check Answer
                </button>
                <button
                    wire:click="getNewConjugation"
                    class="flex-1 button-grey"
                >
                    Next Verb
                </button>
            </div>

            @if ($showFeedback)
                <div class="mt-6 p-4 rounded-md {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $message }}
                </div>
            @endif
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
