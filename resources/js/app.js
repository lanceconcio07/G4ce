import './bootstrap';
import Alpine from 'alpinejs';

// ── Auto-detect visitor timezone and set cookie/header for Livewire ──────────
const detectedTz = Intl.DateTimeFormat().resolvedOptions().timeZone || 'UTC';
document.cookie = `client_timezone=${encodeURIComponent(detectedTz)};path=/;max-age=86400;SameSite=Lax`;

// Expose on window for use in Blade / Livewire calls
window.detectedTimezone = detectedTz;

// ── Alpine.js ────────────────────────────────────────────────────────────────
window.Alpine = Alpine;
Alpine.start();

// ── Livewire hook: send detected timezone on every request ───────────────────
document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ options }) => {
        options.headers['X-Client-Timezone'] = detectedTz;
    });
});
