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
            <header class="sticky top-0 z-30 flex h-16 flex-shrink-0 items-center gap-2 border-b border-border bg-surface/80 backdrop-blur-md px-4 sm:px-6 lg:px-8">
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
        <main class="flex-grow pb-28 lg:pb-0">
            {{ $slot }}
        </main>

        @auth
            <footer class="hidden lg:block border-t border-border bg-surface px-4 sm:px-6 lg:px-8 py-5 mb-16 lg:mb-0">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-fg-muted">
                    <span>© {{ date('Y') }} PresensiKu — Enterprise Presence System</span>
                    <span class="flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-success"></span> Sistem online &amp; terverifikasi
                    </span>
                </div>
            </footer>
        @endauth
    </div>

    @auth
        @php
            $todayLogs = \App\Models\AttendanceLog::where('user_id', auth()->id())
                ->whereDate('timestamp', today())
                ->get();
            $hasCheckInToday = $todayLogs->where('type', 'checkin')->isNotEmpty();
            $hasCheckOutToday = $todayLogs->where('type', 'checkout')->isNotEmpty();
            
            if ($hasCheckInToday && !$hasCheckOutToday) {
                $targetRoute = route('attendance.checkout');
                $buttonLabel = 'OUT';
                $buttonColor = 'bg-gradient-to-tr from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-500/40';
                $pulseColor = 'bg-rose-500';
            } else {
                $targetRoute = route('attendance.checkin');
                $buttonLabel = 'IN';
                $buttonColor = 'bg-gradient-to-tr from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/40';
                $pulseColor = 'bg-emerald-500';
            }
        @endphp

        {{-- Redesigned Mobile Sticky Navigation Bar --}}
        <div class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-surface/90 backdrop-blur-xl border-t border-border/80 shadow-[0_-8px_30px_rgb(0,0,0,0.12)] flex justify-between items-center h-20 px-2 pb-safe">
            <!-- Left Side Buttons -->
            <div class="flex justify-around items-center w-2/5 h-full">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center text-center transition-colors {{ request()->routeIs('dashboard') ? 'text-primary font-semibold' : 'text-fg-muted hover:text-fg' }}">
                    <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="text-[9px] mt-1 tracking-wide">Dasbor</span>
                </a>
                <a href="{{ route('attendance.index') }}" class="flex flex-col items-center justify-center text-center transition-colors {{ request()->routeIs('attendance.index') ? 'text-primary font-semibold' : 'text-fg-muted hover:text-fg' }}">
                    <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span class="text-[9px] mt-1 tracking-wide">Riwayat</span>
                </a>
            </div>

            <!-- Central Floating Action Button -->
            <div class="relative -mt-6 flex justify-center items-center w-1/5 z-50">
                <a href="{{ $targetRoute }}" class="flex flex-col items-center justify-center w-15 h-15 rounded-full {{ $buttonColor }} transition-transform duration-300 hover:scale-105 active:scale-95 border-4 border-surface shadow-2xl relative select-none">
                    <span class="absolute inset-0 rounded-full animate-ping opacity-20 {{ $pulseColor }} -z-10"></span>
                    <svg class="h-5.5 w-5.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                    </svg>
                    <span class="text-[8px] font-extrabold uppercase tracking-widest text-white mt-0.5 leading-none">{{ $buttonLabel }}</span>
                </a>
            </div>

            <!-- Right Side Buttons -->
            <div class="flex justify-around items-center w-2/5 h-full">
                <a href="{{ route('permissions.index') }}" class="flex flex-col items-center justify-center text-center transition-colors {{ request()->routeIs('permissions.*') ? 'text-primary font-semibold' : 'text-fg-muted hover:text-fg' }}">
                    <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-[9px] mt-1 tracking-wide">Izin</span>
                </a>
                <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center text-center transition-colors {{ request()->routeIs('profile') ? 'text-primary font-semibold' : 'text-fg-muted hover:text-fg' }}">
                    <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span class="text-[9px] mt-1 tracking-wide">Profil</span>
                </a>
            </div>
        </div>
    @endauth

    @livewireScripts
</body>

</html>
