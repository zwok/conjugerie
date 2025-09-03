<x-main-layout>

    <h2 class="text-2xl font-bold mb-6 float-animation">Welkom bij <span class="text-transparent bg-clip-text bg-gradient-to-b from-primary to-primary-light">La Conjugerie</span></h2>

    <p class="mb-6">
        La Conjugerie is een hulpmiddel om Franse werkwoorden te leren. Met deze tool kun je:
    </p>

    <ul class="playful-list mb-6 space-y-3">
        <li>Franse werkwoordvervoegingen oefenen</li>
        <li>Je kennis van verschillende tijden en vormen testen</li>
        <li>Je voortgang bijhouden en je vaardigheden verbeteren</li>
    </ul>

    <div class="w-full flex justify-center">
    <a href="{{ route('login') }}" class="rounded-full bg-primary px-3 py-2 font-bold">
        Log in om te oefenen
    </a>
    </div>
</x-main-layout>
