<div class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-center mb-8 text-indigo-700">{{ config('app.name', 'La Conjugerie') }}</h1>

    @if ($currentConjugation)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div class="text-lg font-medium">
                    <span class="text-gray-600">Verb:</span>
                    <span class="text-indigo-600 font-bold">{{ $infinitive }}</span>
                </div>
                <div class="text-lg font-medium">
                    <span class="text-gray-600">Tense:</span>
                    <span class="text-indigo-600 font-bold">{{ $this->getTenseName() }}</span>
                </div>
            </div>

            <div class="bg-indigo-50 p-6 rounded-lg mb-6">
                <p class="text-xl text-center font-bold text-indigo-800">
                    {{ $this->getPersonPronoun() }} <span class="text-gray-500">({{ $infinitive }})</span>
                </p>
            </div>

            <div class="mb-6">
                <label for="answer" class="block text-sm font-medium text-gray-700 mb-2">Your answer:</label>
                <input
                    type="text"
                    id="answer"
                    wire:model="studentAnswer"
                    wire:keydown.enter="checkAnswer"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Type the correct conjugation..."
                    autocomplete="off"
                >
            </div>

            <div class="flex space-x-4">
                <button
                    wire:click="checkAnswer"
                    class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Check Answer
                </button>
                <button
                    wire:click="getNewConjugation"
                    class="flex-1 bg-gray-200 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
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
        <p>Practice your French verb conjugations regularly to improve your skills!</p>
    </div>
</div>
