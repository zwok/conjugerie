<x-main-layout>
    <div class="text-center space-y-6">
        <h1 class="text-3xl sm:text-5xl font-extrabold text-accent leading-tight">
            La conjugaison,<br><span class="text-primary">version jeu.</span>
        </h1>
        <p class="text-lg text-ink-soft max-w-md mx-auto">
            Des manches de 10 questions, des XP, des séries et un classement avec ta classe.
        </p>

        {{-- Try-it-now demo question (client-side only) --}}
        <div class="card text-left"
             x-data="{
                questions: [
                    { pronoun: 'Nous', verb: 'finir', tense: 'Présent', answer: 'finissons' },
                    { pronoun: 'Tu', verb: 'être', tense: 'Imparfait', answer: 'étais' },
                    { pronoun: 'Elles', verb: 'aller', tense: 'Futur simple', answer: 'iront' },
                    { pronoun: 'Vous', verb: 'faire', tense: 'Présent', answer: 'faites' },
                ],
                index: 0,
                value: '',
                state: '',
                shake: false,
                get question() { return this.questions[this.index]; },
                check() {
                    if (this.state === 'correct') { return this.next(); }
                    if (this.value.trim() === '') { return; }
                    if (this.value.trim().toLowerCase() === this.question.answer) {
                        this.state = 'correct';
                        window.dispatchEvent(new CustomEvent('answer-correct'));
                    } else {
                        this.state = 'wrong';
                        this.shake = true;
                        setTimeout(() => this.shake = false, 450);
                        window.dispatchEvent(new CustomEvent('answer-wrong'));
                    }
                },
                next() {
                    this.index = (this.index + 1) % this.questions.length;
                    this.value = '';
                    this.state = '';
                    this.$nextTick(() => this.$refs.answer.focus());
                },
             }">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs font-bold uppercase tracking-wide text-ink-soft">Essaie tout de suite</span>
                <span class="chip bg-secondary text-white" x-text="question.tense">Présent</span>
            </div>

            <label for="demo-answer" class="sr-only">Ta réponse</label>
            <p class="flex flex-wrap items-baseline justify-center gap-x-3 gap-y-3 text-2xl md:text-3xl font-bold text-accent py-4"
               :class="{ 'animate-shake': shake }">
                <span x-text="question.pronoun">Nous</span>
                <input type="text" id="demo-answer" x-ref="answer" x-model="value" @keydown.enter="check()"
                       :readonly="state === 'correct'"
                       class="w-44 max-w-full bg-transparent border-0 border-b-4 border-dashed border-accent/50 px-1 pb-1 text-center font-bold text-ink placeholder:text-ink-soft/40 focus:outline-0 focus:border-solid focus:border-primary"
                       placeholder="…" autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false">
                <span class="text-lg md:text-xl font-semibold text-ink-soft">(<span x-text="question.verb">finir</span>)</span>
            </p>

            <x-accent-bar class="mb-4" x-show="state !== 'correct'" />

            <div aria-live="polite">
                <p class="rounded-2xl bg-green-100 text-green-800 px-5 py-3 font-bold mb-4 animate-pop" x-show="state === 'correct'" style="display: none;">✅ Bien joué !</p>
                <p class="rounded-2xl bg-red-100 text-red-800 px-5 py-3 font-bold mb-4" x-show="state === 'wrong'" style="display: none;">Presque ! Essaie encore.</p>
            </div>

            <button type="button" class="w-full py-3" :class="state === 'correct' ? 'button-primary' : 'button-secondary'"
                    @click="check()" x-text="state === 'correct' ? 'Suivant' : 'Vérifier'">Vérifier</button>
        </div>

        @guest
            <a href="{{ route('smartschool.redirect') }}" class="button-primary block text-lg py-4">
                Commencer avec Smartschool
            </a>
        @endguest

        @auth
            <a href="{{ route('practice') }}" class="button-primary block text-lg py-4">Pratiquer</a>
        @endauth
    </div>
</x-main-layout>
