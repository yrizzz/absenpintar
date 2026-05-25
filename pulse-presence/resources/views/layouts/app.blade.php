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
                                        class="w-9 h-9 bg-gradient-to-tr from-blue-600 via-sky-500 to-emerald-400 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/10 group-hover:scale-[1.03] transition-all">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <span class="text-xl font-black text-white tracking-tight font-display">Absen<span
                                            class="text-sky-400">Pintar</span></span>
                                </a>
                            </div>

                            <!-- Navigation Links (Desktop) -->
                            <div class="hidden space-x-1 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('dashboard') }}"
                                    class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Dasbor
                                </a>

                                <a href="{{ route('attendance.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('attendance.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Ruang Absensi
                                </a>

                                <a href="{{ route('leaves.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('leaves.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Manajemen Cuti
                                </a>

                                <a href="{{ route('permissions.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('permissions.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Izin Kerja
                                </a>

                                <a href="{{ route('reports.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('reports.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Laporan & Telemetri
                                </a>

                                <a href="{{ route('settings.index') }}"
                                    class="inline-flex items-center border-b-[3px] px-3.5 pt-1 text-sm font-semibold transition-all duration-200 {{ request()->routeIs('settings.*') ? 'border-sky-400 text-sky-400' : 'border-transparent text-slate-400 hover:text-white hover:border-slate-700' }}">
                                    Panel Kontrol
                                </a>
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <div class="relative ml-3" x-data="{ open: false }">
                                <button @click="open = !open"
                                    class="flex items-center rounded-2xl bg-[#121d33]/80 hover:bg-[#1c2e50]/80 border border-white/5 p-1.5 focus:outline-none focus:ring-4 focus:ring-blue-500/10 transition-all">
                                    <div class="flex items-center space-x-3.5 pr-2">
                                        <div
                                            class="h-8.5 w-8.5 rounded-xl bg-gradient-to-tr from-blue-600 via-sky-500 to-emerald-400 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <div class="text-left">
                                            <div class="text-sm font-bold text-white leading-tight">
                                                {{ auth()->user()->name }}</div>
                                            <div
                                                class="text-[10px] font-black uppercase text-sky-400 tracking-wider mt-0.5">
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
                                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor"
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
                        <div class="flex items-center sm:hidden" x-data="{ mobileOpen: false }">
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
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'text-sky-400' : 'text-slate-300' }}">Dasbor</a>
                                    <a href="{{ route('attendance.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('attendance.*') ? 'text-sky-400' : 'text-slate-300' }}">Ruang
                                        Absensi</a>
                                    <a href="{{ route('leaves.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('leaves.*') ? 'text-sky-400' : 'text-slate-300' }}">Manajemen
                                        Cuti</a>
                                    <a href="{{ route('permissions.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('permissions.*') ? 'text-sky-400' : 'text-slate-300' }}">Izin Kerja</a>
                                    <a href="{{ route('reports.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('reports.*') ? 'text-sky-400' : 'text-slate-300' }}">Laporan
                                        & Telemetri</a>
                                    <a href="{{ route('settings.index') }}"
                                        class="block py-2 text-sm font-semibold {{ request()->routeIs('settings.*') ? 'text-sky-400' : 'text-slate-300' }}">Panel
                                        Kontrol</a>
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
        <main class="flex-grow relative z-10">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-[#121d33]/40 border-t border-white/5 py-6 mt-12 relative z-10">
            <div
                class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 font-medium">
                <div>© 2026 Platform AbsenPintar. Terverifikasi aman.</div>
                <div class="flex space-x-6 mt-3 sm:mt-0">
                    <a href="#" class="hover:text-sky-400 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-sky-400 transition-colors">Ketentuan Layanan</a>
                    <a href="#" class="hover:text-sky-400 transition-colors">Hubungi Dukungan</a>
                </div>
            </div>
        </footer>
    </div>

    @livewireScripts
</body>

</html>
