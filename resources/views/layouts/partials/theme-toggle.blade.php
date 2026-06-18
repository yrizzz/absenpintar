{{-- Light/dark theme toggle. Light is the default; .dark is added to <html> when dark. --}}
<div x-data="{
        dark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        }
     }">
    <button type="button" @click="toggle()" title="Ganti tema"
        class="flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-surface text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
        {{-- Sun (shown in dark mode → click to go light) --}}
        <svg x-show="dark" x-cloak class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
        </svg>
        {{-- Moon (shown in light mode → click to go dark) --}}
        <svg x-show="!dark" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
        </svg>
    </button>
</div>
