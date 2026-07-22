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

<body class="min-h-screen text-fg antialiased font-sans"
    x-data="{
        sidebarOpen: false,
        /* desktop modes: 'expanded' | 'collapsed' | 'full' | 'horizontal' */
        desktopNav: localStorage.getItem('desktopNav') || 'expanded',
        setNav(mode) {
            this.desktopNav = mode;
            localStorage.setItem('desktopNav', mode);
        },
        toggleSidebar() {
            const cycle = { expanded: 'collapsed', collapsed: 'full', full: 'expanded' };
            this.setNav(cycle[this.desktopNav] || 'expanded');
        }
    }">

    @auth
        {{-- Desktop sidebar — shown in expanded or collapsed mode only --}}
        <aside
            x-show="desktopNav === 'expanded' || desktopNav === 'collapsed'"
            x-cloak
            :class="desktopNav === 'collapsed' ? 'lg:w-16' : 'lg:w-64'"
            class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 border-r border-border transition-all duration-300 ease-in-out overflow-hidden">
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

    <div class="flex min-h-screen flex-col transition-all duration-300 ease-in-out"
        @auth
            :class="{
                'lg:pl-64': desktopNav === 'expanded',
                'lg:pl-16': desktopNav === 'collapsed',
                'lg:pl-0': desktopNav === 'full' || desktopNav === 'horizontal'
            }"
        @endauth>

        @auth
            {{-- Topbar --}}
            <header class="sticky top-0 z-30 flex flex-col border-b border-border bg-surface/80 backdrop-blur-md">

                {{-- Main topbar row --}}
                <div class="flex h-16 flex-shrink-0 items-center gap-2 px-4 sm:px-6 lg:px-8">

                    {{-- Mobile hamburger --}}
                    <button type="button" @click="sidebarOpen = true" class="lg:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>

                    {{-- Desktop: 1 sidebar toggle button --}}
                    <button type="button" @click="desktopNav === 'horizontal' ? setNav('expanded') : toggleSidebar()"
                        class="hidden lg:flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors"
                        :title="desktopNav === 'expanded' ? 'Collapse Sidebar' : desktopNav === 'collapsed' ? 'Sembunyikan Sidebar' : 'Tampilkan Sidebar'">
                        {{-- Icon: expanded=collapse arrows, collapsed=hide icon, full/horizontal=show sidebar --}}
                        <template x-if="desktopNav === 'expanded'">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                        </template>
                        <template x-if="desktopNav === 'collapsed'">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                        </template>
                        <template x-if="desktopNav === 'full' || desktopNav === 'horizontal'">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8M4 18h16" /></svg>
                        </template>
                    </button>

                    {{-- Brand logo — ALWAYS VISIBLE on topbar --}}
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-2.5 transition-all hover:opacity-90 ml-1">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-primary to-blue-700 text-primary-fg shadow-md shadow-primary/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </span>
                        <span class="text-lg font-bold tracking-tight text-fg">Presensi<span class="text-primary">Ku</span></span>
                    </a>

                    <div class="flex-1"></div>

                    {{-- Right side: nav mode buttons — icon-only, compact (desktop only) --}}
                    <div class="hidden lg:flex items-center gap-0.5 rounded-lg border border-border bg-surface-muted p-0.5 mr-1">
                        {{-- Horizontal mode --}}
                        <button type="button" @click="setNav('horizontal')"
                            :class="desktopNav === 'horizontal' ? 'bg-surface text-primary shadow-sm' : 'text-fg-muted hover:text-fg'"
                            class="flex h-7 w-7 items-center justify-center rounded-md transition-all"
                            title="Navigasi Horizontal">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        {{-- Full / no sidebar mode --}}
                        <button type="button" @click="setNav('full')"
                            :class="desktopNav === 'full' ? 'bg-surface text-primary shadow-sm' : 'text-fg-muted hover:text-fg'"
                            class="flex h-7 w-7 items-center justify-center rounded-md transition-all"
                            title="Tampilan Penuh">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                        </button>
                    </div>

                    @livewire('notification-bell')
                    @include('layouts.partials.theme-toggle')
                </div>

                {{-- Horizontal nav bar (desktop only, shown when mode = horizontal) --}}
                <div x-show="desktopNav === 'horizontal'" x-cloak
                    class="hidden lg:flex items-center gap-1.5 px-6 py-2 overflow-x-auto border-t border-border/60 bg-surface/50">
                    @php
                        $hLinks = [
                            [
                                'route' => 'dashboard',
                                'pattern' => 'dashboard',
                                'label' => 'Dasbor',
                                'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'
                            ],
                            [
                                'route' => 'attendance.index',
                                'pattern' => 'attendance.*',
                                'label' => 'Ruang Absensi',
                                'icon' => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z'
                            ],
                            [
                                'route' => 'permissions.index',
                                'pattern' => 'permissions.*',
                                'label' => 'Izin Tidak Masuk',
                                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                            ],
                        ];
                        if(auth()->user()->hasAnyRole(['super_admin','hr_admin'])) {
                            $hLinks[] = [
                                'route' => 'settings.employees',
                                'pattern' => 'settings.employees',
                                'label' => 'Kelola Karyawan',
                                'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
                            ];
                            $hLinks[] = [
                                'route' => 'reports.index',
                                'pattern' => 'reports.*',
                                'label' => 'Laporan',
                                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
                            ];
                            $hLinks[] = [
                                'route' => 'settings.index',
                                'pattern' => 'settings.index',
                                'label' => 'Panel Kontrol',
                                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'
                            ];
                        }
                        $hLinks[] = [
                            'route' => 'profile',
                            'pattern' => 'profile',
                            'label' => 'Profil',
                            'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
                        ];
                    @endphp
                    @foreach($hLinks as $item)
                        @php $isActive = request()->routeIs($item['pattern']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap
                                  {{ $isActive ? 'bg-primary text-primary-fg shadow-sm shadow-primary/20 font-bold' : 'text-fg-muted hover:bg-surface-muted hover:text-fg' }}">
                            <svg class="h-4 w-4 flex-shrink-0 {{ $isActive ? 'text-primary-fg' : 'text-fg-muted' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
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
