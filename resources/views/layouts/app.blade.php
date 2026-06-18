<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'PresensiKu' }} — Enterprise Presence System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=Geist+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    {{-- Theme initializer (light-first): only add .dark when explicitly chosen or OS prefers dark --}}
    <script>
        (function () {
            var t = localStorage.getItem('theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen text-fg antialiased font-sans" x-data="{ sidebarOpen: false }">

    @auth
        {{-- Desktop sidebar --}}
        <aside class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:w-64 lg:z-40 border-r border-border">
            @include('layouts.partials.sidebar')
        </aside>

        {{-- Mobile drawer --}}
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-50 lg:hidden">
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" class="absolute inset-0 modal-overlay"></div>
            <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-150 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                   class="absolute inset-y-0 left-0 w-72 max-w-[80%] shadow-xl">
                @include('layouts.partials.sidebar')
            </aside>
        </div>
    @endauth

    <div class="@auth lg:pl-64 @endauth flex min-h-screen flex-col">

        @auth
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 flex h-16 flex-shrink-0 items-center gap-2 border-b border-border bg-surface px-4 sm:px-6 lg:px-8">
                <button type="button" @click="sidebarOpen = true" class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <a href="{{ route('dashboard') }}" class="lg:hidden flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-primary-fg">
                        <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </span>
                    <span class="text-base font-semibold tracking-tight text-fg">Presensi<span class="text-primary">Ku</span></span>
                </a>

                <div class="flex-1"></div>

                @livewire('notification-bell')
                @include('layouts.partials.theme-toggle')
            </header>
        @endauth

        {{-- Flash --}}
        @if (session('success') || session('error') || session('warning'))
            <div class="w-full px-4 sm:px-6 lg:px-8 mt-6">
                @if (session('success'))
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-success/30 bg-success-soft p-4">
                        <svg class="h-5 w-5 flex-shrink-0 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-sm font-medium text-success">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-danger/30 bg-danger-soft p-4">
                        <svg class="h-5 w-5 flex-shrink-0 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        <p class="text-sm font-medium text-danger">{{ session('error') }}</p>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="mb-3 flex items-center gap-3 rounded-xl border border-warning/30 bg-warning-soft p-4">
                        <svg class="h-5 w-5 flex-shrink-0 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <p class="text-sm font-medium text-warning">{{ session('warning') }}</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Content --}}
        <main class="flex-grow">
            {{ $slot }}
        </main>

        @auth
            <footer class="border-t border-border bg-surface px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-fg-muted">
                    <span>© {{ date('Y') }} PresensiKu — Enterprise Presence System</span>
                    <span class="flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-success"></span> Sistem online &amp; terverifikasi
                    </span>
                </div>
            </footer>
        @endauth
    </div>

    @livewireScripts
</body>

</html>
