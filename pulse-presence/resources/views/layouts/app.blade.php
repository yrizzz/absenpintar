<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'AbsenPintar' }} - Enterprise Presence System</title>

    <!-- Premium Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <!-- Theme Initializer Script to prevent flashing -->
    <script>
        if (localStorage.getItem('theme') === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches)) {
            document.documentElement.classList.add('light');
        } else {
            document.documentElement.classList.remove('light');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[#090e1a] text-slate-100 antialiased font-sans">
    <div class="min-h-screen flex flex-col relative overflow-hidden">
        <!-- Decorative Ambient Glows for Dashboard Layout -->
        <div
            class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-blue-500/5 blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-emerald-500/5 blur-[100px] pointer-events-none">
        </div>

        <!-- Navigation: Frosted Glass Dark Navigation -->
        @auth
            <nav class="bg-[#121d33]/70 backdrop-blur-md border-b border-white/5 sticky top-0 z-50 shadow-lg">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex flex-shrink-0 items-center">
                                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                                     <div
                                        class="w-9 h-9 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/10 group-hover:scale-[1.03] transition-all">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <span class="text-xl font-black text-white tracking-tight font-display">Absen<span
                                            class="text-blue-500">Pintar</span></span>
                                </a>
                            </div>

                            <!-- Navigation Links (Desktop) -->
                            <div class="hidden space-x-1 lg:-my-px lg:ml-6 xl:ml-10 lg:flex">
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center border-b-[3px] px-2.5 xl:px-3.5 pt-1 text-xs xl:text-sm font-semibold tracking-tight transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-blue-600 text-white' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Dasbor
                                </a>

                                <a href="{{ route('attendance.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-2.5 xl:px-3.5 pt-1 text-xs xl:text-sm font-semibold tracking-tight transition-all duration-200 {{ request()->routeIs('attendance.*') ? 'border-blue-600 text-white' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Ruang Absensi
                                </a>

                                <a href="{{ route('leaves.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-2.5 xl:px-3.5 pt-1 text-xs xl:text-sm font-semibold tracking-tight transition-all duration-200 {{ request()->routeIs('leaves.*') ? 'border-blue-600 text-white' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Manajemen Cuti
                                </a>

                                @if(auth()->user()->hasAnyRole(['super_admin', 'hr_admin', 'manager']))
                                <a href="{{ route('permissions.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-2.5 xl:px-3.5 pt-1 text-xs xl:text-sm font-semibold tracking-tight transition-all duration-200 {{ request()->routeIs('permissions.*') ? 'border-blue-600 text-white' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Izin Kerja
                                </a>
                                @endif

                                @if(auth()->user()->hasAnyRole(['super_admin', 'hr_admin']))
                                <a href="{{ route('reports.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-2.5 xl:px-3.5 pt-1 text-xs xl:text-sm font-semibold tracking-tight transition-all duration-200 {{ request()->routeIs('reports.*') ? 'border-blue-600 text-white' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Laporan & Telemetri
                                </a>

                                <a href="{{ route('settings.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-2.5 xl:px-3.5 pt-1 text-xs xl:text-sm font-semibold tracking-tight transition-all duration-200 {{ request()->routeIs('settings.*') ? 'border-blue-600 text-white' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Panel Kontrol
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="hidden lg:ml-6 lg:flex lg:items-center space-x-3">
                            <!-- Theme Toggle Button (Desktop) -->
                            <div x-data="{ 
                                isLight: localStorage.getItem('theme') === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches),
                                toggleTheme() {
                                    this.isLight = !this.isLight;
                                    if (this.isLight) {
                                        document.documentElement.classList.add('light');
                                        localStorage.setItem('theme', 'light');
                                    } else {
                                        document.documentElement.classList.remove('light');
                                        localStorage.setItem('theme', 'dark');
                                    }
                                }
                            }">
                                <button @click="toggleTheme()" 
                                    class="flex items-center justify-center w-9 h-9 rounded-2xl bg-[#121d33]/80 hover:bg-[#1c2e50]/80 border border-white/5 text-slate-300 hover:text-white transition-all focus:outline-none"
                                    title="Ganti Tema">
                                    <svg x-show="isLight" class="w-4.5 h-4.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                    </svg>
                                    <svg x-show="!isLight" class="w-4.5 h-4.5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center rounded-2xl bg-[#121d33]/80 hover:bg-[#1c2e50]/80 border border-white/5 p-1.5 focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                                    <div class="flex items-center space-x-3.5 pr-2">
                                        <div
                                            class="h-9 w-9 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <div class="text-left">
                                            <div class="label-md font-bold text-white">
                                                {{ auth()->user()->name }}</div>
                                            <div
                                                class="label-xs text-blue-400 mt-0.5">
                                                {{ auth()->user()->employee_id }}</div>
                                        </div>
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 z-50 mt-2.5 w-52 origin-top-right rounded-2xl bg-[#121d33] p-2 shadow-2xl border border-white/10"
                                    style="display: none;">
                                    <a href="{{ route('profile') }}"
                                        class="flex items-center space-x-2.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-slate-300 hover:bg-white/5 hover:text-white transition-all duration-150">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span>Profil Karyawan</span>
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center space-x-2.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition-all duration-150 text-left">
                                            <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            <span>Keluar Sesi</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center lg:hidden space-x-2" x-data="{ mobileOpen: false }">
                            <!-- Theme Toggle Button (Mobile) -->
                            <div x-data="{ 
                                isLight: localStorage.getItem('theme') === 'light' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: light)').matches),
                                toggleTheme() {
                                    this.isLight = !this.isLight;
                                    if (this.isLight) {
                                        document.documentElement.classList.add('light');
                                        localStorage.setItem('theme', 'light');
                                    } else {
                                        document.documentElement.classList.remove('light');
                                        localStorage.setItem('theme', 'dark');
                                    }
                                }
                            }">
                                <button @click="toggleTheme()" 
                                    class="flex items-center justify-center w-9 h-9 rounded-xl bg-[#121d33]/80 hover:bg-[#1c2e50]/80 border border-white/5 text-slate-300 hover:text-white transition-all focus:outline-none"
                                    title="Ganti Tema">
                                    <svg x-show="isLight" class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                    </svg>
                                    <svg x-show="!isLight" class="w-4.5 h-4.5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                                    </svg>
                                </button>
                            </div>

                            <button @click="mobileOpen = !mobileOpen"
                                class="inline-flex items-center justify-center rounded-xl p-2 text-slate-400 hover:bg-slate-800 hover:text-white transition-colors duration-150">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" style="display: none;" />
                                </svg>
                            </button>

                            <div x-show="mobileOpen" @click.away="mobileOpen = false"
                                class="absolute top-16 left-0 right-0 bg-[#121d33] border-b border-white/5 shadow-md z-50"
                                style="display: none;">
                                <div class="space-y-1.5 pb-3.5 pt-2.5 px-4">
                                    <a href="{{ route('dashboard') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-300' }}">Dasbor</a>
                                    <a href="{{ route('attendance.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('attendance.*') ? 'text-white' : 'text-slate-300' }}">Ruang
                                        Absensi</a>
                                    <a href="{{ route('leaves.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('leaves.*') ? 'text-white' : 'text-slate-300' }}">Manajemen
                                        Cuti</a>
                                    @if(auth()->user()->hasAnyRole(['super_admin', 'hr_admin', 'manager']))
                                    <a href="{{ route('permissions.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('permissions.*') ? 'text-white' : 'text-slate-300' }}">Izin Kerja</a>
                                    @endif
                                    @if(auth()->user()->hasAnyRole(['super_admin', 'hr_admin']))
                                    <a href="{{ route('reports.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('reports.*') ? 'text-white' : 'text-slate-300' }}">Laporan
                                        & Telemetri</a>
                                    <a href="{{ route('settings.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('settings.*') ? 'text-white' : 'text-slate-300' }}">Panel
                                        Kontrol</a>
                                    @endif
                                </div>
                                <div class="border-t border-white/5 pb-3.5 pt-3.5 px-4">
                                    <div class="text-sm font-bold text-white">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ auth()->user()->email }}</div>
                                    <div class="mt-3.5 space-y-1.5">
                                        <a href="{{ route('profile') }}"
                                            class="block py-2 text-sm font-semibold text-slate-300">Profil Karyawan</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="block w-full text-left py-2 text-sm font-semibold text-rose-400">Keluar
                                                Sesi</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Flash Messages -->
        @if (session('success') || session('error') || session('warning'))
            <div class="mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 mt-6 relative z-10">
                @if (session('success'))
                    <div
                        class="rounded-2xl bg-emerald-950/40 backdrop-blur-md border border-emerald-500/20 p-4 mb-3.5 shadow-sm flex items-center space-x-3">
                        <span class="p-1.5 bg-emerald-600 rounded-lg text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <p class="text-sm font-semibold text-emerald-300">{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="rounded-2xl bg-rose-950/40 backdrop-blur-md border border-rose-500/20 p-4 mb-3.5 shadow-sm flex items-center space-x-3">
                        <span class="p-1.5 bg-rose-600 rounded-lg text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                        <p class="text-sm font-semibold text-rose-300">{{ session('error') }}</p>
                    </div>
                @endif
                @if (session('warning'))
                    <div
                        class="rounded-2xl bg-amber-950/40 backdrop-blur-md border border-amber-500/20 p-4 mb-3.5 shadow-sm flex items-center space-x-3">
                        <span class="p-1.5 bg-amber-600 rounded-lg text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </span>
                        <p class="text-sm font-semibold text-amber-300">{{ session('warning') }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Page Content -->
        <main class="flex-grow relative">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-[#0b1324] border-t border-white/5 py-10 mt-16 relative z-10 hidden lg:block">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-6 border-b border-white/5">
                    <!-- Logo / Brand Info -->
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md shadow-blue-600/10">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-base font-extrabold text-white tracking-tight font-display">Absen<span class="text-blue-500">Pintar</span></span>
                            <span class="text-[10px] text-slate-500 block">Enterprise Presence System</span>
                        </div>
                    </div>

                    <!-- System Status Badge -->
                    <div class="flex items-center space-x-2 bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[10px] font-bold text-emerald-400">Sistem online & terverifikasi</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 font-medium pt-6 gap-4">
                    <div>
                        © {{ date('Y') }} Platform AbsenPintar. Hak Cipta Dilindungi Undang-Undang.
                    </div>
                    <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                        <a href="#" class="hover:text-blue-400 transition-colors">Kebijakan privasi</a>
                        <a href="#" class="hover:text-blue-400 transition-colors">Ketentuan layanan</a>
                        <a href="#" class="hover:text-blue-400 transition-colors">Hubungi dukungan</a>
                    </div>
                </div>
            </div>
        </footer>

        {{-- Spacer: prevent content from hiding behind fixed mobile bottom nav --}}
        <div class="h-24 lg:hidden" aria-hidden="true"></div>

        @auth
            <!-- Mobile Floating Bottom Navigation Dock -->
            <div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-[#0d1627]/90 backdrop-blur-xl border-t border-white/5 py-2 shadow-[0_-8px_30px_rgb(0,0,0,0.5)]">
                <div class="flex items-center justify-around h-14 w-full">
                    <!-- Tab 1: Dasbor -->
                    <a href="{{ route('dashboard') }}" 
                        class="flex flex-col items-center justify-center flex-1 h-12 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'text-blue-400' : 'text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="text-[9px] font-bold tracking-tight">Dasbor</span>
                        @if(request()->routeIs('dashboard'))
                            <span class="w-1 h-1 rounded-full bg-blue-500 mt-0.5"></span>
                        @endif
                    </a>

                    <!-- Tab 2: Absen -->
                    <a href="{{ route('attendance.index') }}" 
                        class="flex flex-col items-center justify-center flex-1 h-12 transition-all duration-200 {{ request()->routeIs('attendance.*') ? 'text-emerald-400' : 'text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-[9px] font-bold tracking-tight">Presensi</span>
                        @if(request()->routeIs('attendance.*'))
                            <span class="w-1 h-1 rounded-full bg-emerald-500 mt-0.5"></span>
                        @endif
                    </a>

                    <!-- Tab 3: Cuti/Izin -->
                    <a href="{{ route('leaves.index') }}" 
                        class="flex flex-col items-center justify-center flex-1 h-12 transition-all duration-200 {{ request()->routeIs('leaves.*') || request()->routeIs('permissions.*') ? 'text-amber-400' : 'text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[9px] font-bold tracking-tight">Izin/Cuti</span>
                        @if(request()->routeIs('leaves.*') || request()->routeIs('permissions.*'))
                            <span class="w-1 h-1 rounded-full bg-amber-500 mt-0.5"></span>
                        @endif
                    </a>

                    <!-- Tab 4: Admin / Laporan -->
                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('settings.index') }}" 
                            class="flex flex-col items-center justify-center flex-1 h-12 transition-all duration-200 {{ request()->routeIs('settings.*') ? 'text-purple-400' : 'text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-[9px] font-bold tracking-tight">Admin</span>
                            @if(request()->routeIs('settings.*'))
                                <span class="w-1 h-1 rounded-full bg-purple-500 mt-0.5"></span>
                            @endif
                        </a>
                    @else
                        <a href="{{ route('reports.index') }}" 
                            class="flex flex-col items-center justify-center flex-1 h-12 transition-all duration-200 {{ request()->routeIs('reports.*') ? 'text-purple-400' : 'text-slate-400 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="text-[9px] font-bold tracking-tight">Laporan</span>
                            @if(request()->routeIs('reports.*'))
                                <span class="w-1 h-1 rounded-full bg-purple-500 mt-0.5"></span>
                            @endif
                        </a>
                    @endif

                    <!-- Tab 5: Profil -->
                    <a href="{{ route('profile') }}" 
                        class="flex flex-col items-center justify-center flex-1 h-12 transition-all duration-200 {{ request()->routeIs('profile') ? 'text-pink-400' : 'text-slate-400 hover:text-slate-200' }}">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="text-[9px] font-bold tracking-tight">Profil</span>
                        @if(request()->routeIs('profile'))
                            <span class="w-1 h-1 rounded-full bg-pink-500 mt-0.5"></span>
                        @endif
                    </a>
                </div>
            </div>
        @endauth
    </div>

    @livewireScripts
</body>

</html>
