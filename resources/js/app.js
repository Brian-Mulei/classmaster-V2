import './bootstrap';

// Dark / light mode — persisted in localStorage, respects system preference on first visit
const html = document.documentElement;

function applyTheme(dark) {
    html.classList.toggle('dark', dark);
}

// Apply on load before paint (avoid flash)
const stored = localStorage.getItem('theme');
if (stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    applyTheme(true);
}

// Expose a global toggle usable from Alpine: @click="$dispatch('toggle-theme')"
document.addEventListener('toggle-theme', () => {
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');
});
