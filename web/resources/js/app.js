import './bootstrap';

// Small client-side helpers for the practice screens: accent keys, sound and confetti.
const reducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const store = {
    get(key) {
        try { return localStorage.getItem(key); } catch { return null; }
    },
    set(key, value) {
        try { localStorage.setItem(key, value); } catch { /* storage unavailable */ }
    },
};

let audioContext = null;

const SOUNDS = {
    correct: [[660, 0], [880, 0.09]],
    wrong: [[220, 0], [180, 0.12]],
    finish: [[523, 0], [659, 0.1], [784, 0.2], [1047, 0.32]],
};

window.conjugerie = {
    // Insert a character at the cursor of an input and let wire:model / x-model know
    insert(input, char) {
        if (!input || input.readOnly) return;
        const start = input.selectionStart ?? input.value.length;
        const end = input.selectionEnd ?? input.value.length;
        input.value = input.value.slice(0, start) + char + input.value.slice(end);
        input.setSelectionRange(start + char.length, start + char.length);
        input.dispatchEvent(new Event('input', { bubbles: true }));
        input.focus();
    },

    soundOn() {
        return store.get('sound') !== 'off';
    },

    setSound(on) {
        store.set('sound', on ? 'on' : 'off');
    },

    setDark(on) {
        document.documentElement.classList.toggle('dark', on);
        store.set('theme', on ? 'dark' : 'light');
    },

    play(name) {
        if (!this.soundOn() || !SOUNDS[name]) return;
        try {
            audioContext ??= new (window.AudioContext || window.webkitAudioContext)();
            const now = audioContext.currentTime;
            for (const [frequency, offset] of SOUNDS[name]) {
                const oscillator = audioContext.createOscillator();
                const gain = audioContext.createGain();
                oscillator.type = name === 'wrong' ? 'sawtooth' : 'sine';
                oscillator.frequency.value = frequency;
                gain.gain.setValueAtTime(0.08, now + offset);
                gain.gain.exponentialRampToValueAtTime(0.001, now + offset + 0.18);
                oscillator.connect(gain).connect(audioContext.destination);
                oscillator.start(now + offset);
                oscillator.stop(now + offset + 0.2);
            }
        } catch { /* audio unavailable */ }
    },

    confetti(count = 28) {
        if (reducedMotion()) return;
        const colors = ['#57cc02', '#235391', '#ffc800', '#ff4b4b', '#ce82ff', '#1cb0f6'];
        for (let i = 0; i < count; i++) {
            const piece = document.createElement('span');
            const size = 6 + Math.random() * 6;
            piece.style.cssText = `position:fixed;left:50%;top:40%;width:${size}px;height:${size * 1.4}px;` +
                `background:${colors[i % colors.length]};border-radius:2px;pointer-events:none;z-index:100;`;
            document.body.appendChild(piece);
            const angle = Math.random() * Math.PI * 2;
            const distance = 120 + Math.random() * 220;
            const x = Math.cos(angle) * distance;
            const y = Math.sin(angle) * distance - 120;
            piece.animate([
                { transform: 'translate(-50%, -50%) rotate(0deg)', opacity: 1 },
                { transform: `translate(${x}px, ${y}px) rotate(${Math.random() * 720}deg)`, opacity: 1, offset: 0.7 },
                { transform: `translate(${x}px, ${y + 160}px) rotate(${Math.random() * 1080}deg)`, opacity: 0 },
            ], { duration: 900 + Math.random() * 500, easing: 'cubic-bezier(.2,.7,.3,1)' })
                .onfinish = () => piece.remove();
        }
    },
};

window.addEventListener('answer-correct', () => {
    window.conjugerie.play('correct');
    window.conjugerie.confetti();
});

window.addEventListener('answer-wrong', () => window.conjugerie.play('wrong'));

window.addEventListener('round-finished', (event) => {
    window.conjugerie.play('finish');
    const { score = 0, total = 1 } = event.detail ?? {};
    if (score / total >= 0.7) window.conjugerie.confetti(70);
});
