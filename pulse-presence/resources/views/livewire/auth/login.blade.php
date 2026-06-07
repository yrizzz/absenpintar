<div class="login-root min-h-screen flex bg-[#060d1f] font-sans relative overflow-hidden">

    {{-- ── BACKGROUND GLOWS ── --}}
    <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-[600px] h-[600px] bg-indigo-600/15 rounded-full blur-[130px] pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-900/10 rounded-full blur-[100px] pointer-events-none"></div>

    {{-- ── GRID TEXTURE ── --}}
    <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.015)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.015)_1px,transparent_1px)] bg-[size:40px_40px] pointer-events-none"></div>

    {{-- ── THEME TOGGLE ── --}}
    <div class="absolute top-5 right-5 z-50" x-data="{
        isLight: localStorage.getItem('theme') === 'light',
        toggleTheme() {
            this.isLight = !this.isLight;
            document.documentElement.classList.toggle('light', this.isLight);
            localStorage.setItem('theme', this.isLight ? 'light' : 'dark');
        }
    }">
        <button @click="toggleTheme()"
            class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-400 hover:text-white transition-all flex items-center justify-center backdrop-blur-md"
            title="Ganti Tema">
            <svg x-show="isLight" class="w-4.5 h-4.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20" style="display:none;">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
            </svg>
            <svg x-show="!isLight" class="w-4.5 h-4.5 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
        </button>
    </div>

    {{-- ════════════════════════════════════
         LEFT PANEL
    ════════════════════════════════════ --}}
    <div class="left-panel hidden lg:flex lg:w-[48%] xl:w-[45%] flex-col justify-between p-10 xl:p-14 relative z-10">

        {{-- Logo --}}
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-xl font-black text-white tracking-tight">Presensi<span class="text-blue-400">Ku</span></span>
        </div>

        {{-- Center Hero --}}
        <div class="flex-1 flex flex-col justify-center space-y-10">

            {{-- Face Scan Visual --}}
            <div class="relative flex items-center justify-center h-64">
                {{-- Rings --}}
                <div class="scan-ring-1 absolute w-64 h-64 rounded-full border border-blue-500/10 animate-[spin_20s_linear_infinite]"></div>
                <div class="scan-ring-2 absolute w-52 h-52 rounded-full border border-blue-500/15 animate-[spin_14s_linear_infinite_reverse]"></div>
                <div class="scan-ring-3 absolute w-40 h-40 rounded-full border border-blue-400/20 animate-[spin_9s_linear_infinite]"></div>
                <div class="scan-ring-4 absolute w-28 h-28 rounded-full border-2 border-blue-500/40 shadow-[0_0_30px_rgba(59,130,246,0.3)]"></div>

                {{-- Scan line --}}
                <div class="absolute w-24 h-24 rounded-full overflow-hidden">
                    <div class="absolute inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-400 to-transparent shadow-[0_0_8px_#60a5fa]"
                         style="animation: scanBeat 2.4s ease-in-out infinite; top: 50%;"></div>
                </div>

                {{-- Center icon --}}
                <div class="scan-center relative w-20 h-20 bg-gradient-to-br from-blue-600/30 to-indigo-700/30 rounded-full border border-blue-500/30 flex items-center justify-center backdrop-blur-sm shadow-[0_0_40px_rgba(59,130,246,0.25)]">
                    <svg class="w-8 h-8 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>

                {{-- Dots orbiting --}}
                <div class="absolute w-64 h-64" style="animation: spin 12s linear infinite;">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full bg-blue-400 shadow-[0_0_6px_#60a5fa]"></div>
                </div>
                <div class="absolute w-52 h-52" style="animation: spin 8s linear infinite reverse;">
                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-indigo-400 shadow-[0_0_6px_#818cf8]"></div>
                </div>

                {{-- Corner brackets --}}
                <div class="bracket absolute top-[38px] left-[38px] w-5 h-5 border-t-2 border-l-2 border-blue-400/60 rounded-tl-sm"></div>
                <div class="bracket absolute top-[38px] right-[38px] w-5 h-5 border-t-2 border-r-2 border-blue-400/60 rounded-tr-sm"></div>
                <div class="bracket absolute bottom-[38px] left-[38px] w-5 h-5 border-b-2 border-l-2 border-blue-400/60 rounded-bl-sm"></div>
                <div class="bracket absolute bottom-[38px] right-[38px] w-5 h-5 border-b-2 border-r-2 border-blue-400/60 rounded-br-sm"></div>
            </div>

            {{-- Copy --}}
            <div class="space-y-4 max-w-sm">
                <div class="badge-hero inline-flex items-center gap-2 px-3 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse flex-shrink-0"></span>
                    <span class="badge-text text-[11px] font-bold tracking-widest uppercase">Sistem Kehadiran Cerdas</span>
                </div>
                <h1 class="text-4xl xl:text-5xl font-black text-white leading-[1.1] tracking-tight">
                    Hadir Lebih<br>
                    <span class="heading-gradient text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">Cerdas & Aman</span>
                </h1>
                <p class="text-sm text-slate-400 leading-relaxed max-w-xs">
                    Verifikasi wajah real-time, geofencing otomatis, dan pelaporan kehadiran terpusat untuk tim Anda.
                </p>
            </div>

            {{-- Feature chips --}}
            <div class="flex flex-wrap gap-2">
                <div class="feature-chip flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/[0.04] border border-white/[0.07] text-[11px] text-slate-300 font-medium">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Verifikasi Wajah
                </div>
                <div class="feature-chip flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/[0.04] border border-white/[0.07] text-[11px] text-slate-300 font-medium">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Geofence GPS
                </div>
                <div class="feature-chip flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/[0.04] border border-white/[0.07] text-[11px] text-slate-300 font-medium">
                    <svg class="w-3.5 h-3.5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Laporan Real-time
                </div>
            </div>
        </div>

        {{-- Status footer --}}
        <div class="flex items-center gap-5 text-[11px] text-slate-500">
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="status-label">Server Aktif</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="status-label">AI Core Online</span>
            </div>
        </div>
    </div>

    {{-- ── VERTICAL DIVIDER ── --}}
    <div class="hidden lg:block w-px self-stretch relative z-10 flex-shrink-0">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-blue-500/30 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/[0.06] to-transparent"></div>
    </div>

    {{-- ════════════════════════════════════
         RIGHT PANEL — LOGIN FORM
    ════════════════════════════════════ --}}
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10 relative z-10">
        <div class="w-full max-w-[400px] space-y-6">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex flex-col items-center gap-2 mb-2">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-2xl font-black text-white tracking-tight">Presensi<span class="text-blue-400">Ku</span></span>
            </div>

            {{-- Form heading --}}
            <div>
                <h2 class="text-2xl xl:text-3xl font-bold text-white tracking-tight">Selamat Datang</h2>
                <p class="text-sm text-slate-400 mt-1">Masuk ke akun PresensiKu Anda</p>
            </div>

            {{-- Form card --}}
            <div class="bg-white/[0.035] border border-white/[0.08] rounded-2xl p-6 sm:p-8 backdrop-blur-xl shadow-[0_32px_64px_rgba(0,0,0,0.4)]">
                <form wire:submit="login" class="space-y-5">

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3.5 flex items-center text-slate-500 pointer-events-none">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </span>
                            <input wire:model="email" id="email" type="email" required autofocus
                                placeholder="nama@perusahaan.com"
                                class="login-input w-full pl-10 pr-4 py-3 bg-white/[0.04] border border-white/10 rounded-xl text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500/70 focus:bg-blue-500/5 focus:ring-2 focus:ring-blue-500/15 transition-all @error('email') border-rose-500/40 @enderror">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-400 flex items-center gap-1 mt-1">
                                <span class="w-1 h-1 rounded-full bg-rose-400 flex-shrink-0"></span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kata Sandi</label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="absolute inset-y-0 left-3.5 flex items-center text-slate-500 pointer-events-none">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input wire:model="password" id="password" :type="show ? 'text' : 'password'" required
                                placeholder="••••••••"
                                class="login-input w-full pl-10 pr-11 py-3 bg-white/[0.04] border border-white/10 rounded-xl text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500/70 focus:bg-blue-500/5 focus:ring-2 focus:ring-blue-500/15 transition-all @error('password') border-rose-500/40 @enderror">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors focus:outline-none">
                                <svg x-show="!show" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-400 flex items-center gap-1 mt-1">
                                <span class="w-1 h-1 rounded-full bg-rose-400 flex-shrink-0"></span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input wire:model="remember" id="remember" type="checkbox"
                            class="w-4 h-4 rounded border-white/20 bg-white/5 text-blue-500 focus:ring-blue-500/20 focus:ring-offset-0">
                        <span class="text-xs text-slate-400">Tetap masuk di perangkat ini</span>
                    </label>

                    {{-- Submit --}}
                    <button type="submit" wire:loading.attr="disabled"
                        class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-sm shadow-lg shadow-blue-600/30 hover:shadow-blue-500/40 transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50 hover:-translate-y-px active:translate-y-0">
                        <span wire:loading.remove>
                            <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk ke Akun
                        </span>
                        <span wire:loading.flex class="items-center gap-2" style="display:none;">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Mengautentikasi...
                        </span>
                    </button>
                </form>
            </div>

            {{-- Role simulator --}}
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-px bg-white/[0.07]"></div>
                    <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">Demo Akun</span>
                    <div class="flex-1 h-px bg-white/[0.07]"></div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="document.getElementById('email').value='admin@presensiku.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="role-card group flex flex-col gap-0.5 p-3 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-white/[0.12] transition-all text-left">
                        <span class="role-chip chip-admin inline-block text-[10px] font-bold px-2 py-0.5 rounded-md border bg-violet-500/10 border-violet-500/20 text-violet-400">Super Admin</span>
                        <span class="role-email text-[10px] text-slate-500 font-mono truncate mt-1">admin@presensiku.com</span>
                    </button>
                    <button type="button" onclick="document.getElementById('email').value='hr@presensiku.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="role-card group flex flex-col gap-0.5 p-3 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-white/[0.12] transition-all text-left">
                        <span class="role-chip chip-hr inline-block text-[10px] font-bold px-2 py-0.5 rounded-md">HR Manager</span>
                        <span class="role-email text-[10px] text-slate-500 font-mono truncate mt-1">hr@presensiku.com</span>
                    </button>
                    <button type="button" onclick="document.getElementById('email').value='manager@presensiku.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="role-card group flex flex-col gap-0.5 p-3 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-white/[0.12] transition-all text-left">
                        <span class="role-chip chip-branch inline-block text-[10px] font-bold px-2 py-0.5 rounded-md border bg-emerald-500/10 border-emerald-500/20 text-emerald-400">Branch Head</span>
                        <span class="role-email text-[10px] text-slate-500 font-mono truncate mt-1">manager@presensiku.com</span>
                    </button>
                    <button type="button" onclick="document.getElementById('email').value='employee4@presensiku.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="role-card group flex flex-col gap-0.5 p-3 rounded-xl bg-white/[0.03] border border-white/[0.06] hover:bg-white/[0.06] hover:border-white/[0.12] transition-all text-left">
                        <span class="role-chip chip-employee inline-block text-[10px] font-bold px-2 py-0.5 rounded-md border bg-amber-500/10 border-amber-500/20 text-amber-400">Employee</span>
                        <span class="role-email text-[10px] text-slate-500 font-mono truncate mt-1">employee4@presensiku.com</span>
                    </button>
                </div>

                <div class="flex items-center justify-center gap-2 text-xs text-slate-600">
                    <span>Sandi default:</span>
                    <code class="pwd-badge font-mono font-bold px-2 py-0.5 rounded-lg">password</code>
                </div>
            </div>

            {{-- Copyright --}}
            <p class="text-center text-[11px] text-slate-700">© {{ date('Y') }} PresensiKu. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</div>

<style>
    @keyframes scanBeat {
        0%, 100% { transform: translateY(-48px); opacity: 0; }
        20%       { opacity: 1; }
        80%       { opacity: 1; }
        50%       { transform: translateY(48px); }
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* ── Light mode ── */
    html.light .login-root {
        background: #f0f4ff !important;
    }
    html.light .login-root > div:not(.absolute) {
        border-color: #e2e8f0 !important;
    }
    html.light .login-input {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        color: #1e293b !important;
    }
    html.light .login-input::placeholder { color: #94a3b8 !important; }
    html.light .login-input:focus {
        border-color: #3b82f6 !important;
        background: #eff6ff !important;
    }
    html.light .login-root h2 { color: #0f172a !important; }
    html.light .login-root p  { color: #475569 !important; }
    html.light .login-root .bg-white\/\[0\.035\] {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 20px 60px -12px rgba(15,23,42,0.12), 0 4px 16px rgba(15,23,42,0.06) !important;
    }
    html.light .login-root .bg-white\/\[0\.03\] {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }
    html.light .login-root .bg-white\/\[0\.03\]:hover {
        background: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
    }
    html.light .login-root .text-slate-400 { color: #475569 !important; }
    html.light .login-root .text-slate-500 { color: #64748b !important; }
    html.light .login-root .text-slate-600 { color: #64748b !important; }
    html.light .login-root .text-slate-700 { color: #94a3b8 !important; }
    html.light .login-root .border-white\/\[0\.07\] { border-color: #e2e8f0 !important; }

    /* ── Left panel in light mode ── */
    html.light .login-root .left-panel {
        background: linear-gradient(135deg, #e8eeff 0%, #eef2ff 60%, #f0f4ff 100%) !important;
    }

    /* Badge "Sistem Kehadiran Cerdas" — full CSS control, no Tailwind bg */
    .badge-hero {
        background: rgba(59,130,246,0.10);
        border: 1px solid rgba(59,130,246,0.22);
    }
    .badge-text { color: #93c5fd; }

    html.light .badge-hero {
        background: rgba(37,99,235,0.12) !important;
        border-color: rgba(37,99,235,0.38) !important;
    }
    html.light .badge-text { color: #2563eb !important; }

    /* Scan rings */
    html.light .login-root .left-panel .scan-ring-1 { border-color: rgba(59,130,246,0.20) !important; }
    html.light .login-root .left-panel .scan-ring-2 { border-color: rgba(59,130,246,0.28) !important; }
    html.light .login-root .left-panel .scan-ring-3 { border-color: rgba(59,130,246,0.40) !important; }
    html.light .login-root .left-panel .scan-ring-4 {
        border-color: rgba(59,130,246,0.70) !important;
        box-shadow: 0 0 20px rgba(59,130,246,0.25) !important;
    }
    html.light .login-root .left-panel .scan-center {
        background: linear-gradient(135deg, rgba(59,130,246,0.25), rgba(79,70,229,0.25)) !important;
        border-color: rgba(59,130,246,0.50) !important;
        box-shadow: 0 0 30px rgba(59,130,246,0.20) !important;
    }
    html.light .login-root .left-panel .scan-center svg { color: #2563eb !important; }

    /* Corner brackets */
    html.light .login-root .left-panel .bracket { border-color: rgba(59,130,246,0.50) !important; }

    /* Heading gradient */
    html.light .login-root .left-panel .heading-gradient {
        background: linear-gradient(to right, #2563eb, #4f46e5) !important;
        -webkit-background-clip: text !important;
        background-clip: text !important;
    }

    /* Feature chips */
    html.light .login-root .left-panel .feature-chip {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        color: #334155 !important;
        box-shadow: 0 1px 3px rgba(15,23,42,0.08) !important;
    }

    /* Status dots label */
    html.light .login-root .left-panel .status-label { color: #64748b !important; }

    /* Heading & paragraph */
    html.light .login-root .left-panel h1 { color: #0f172a !important; }
    html.light .login-root .left-panel p  { color: #475569 !important; }

    /* ── Demo Akun: role chips — full CSS control ── */
    .chip-hr {
        background: rgba(59,130,246,0.10);
        border: 1px solid rgba(59,130,246,0.22);
        color: #60a5fa;
    }

    html.light .chip-admin    { background: #ede9fe !important; border: 1px solid #a78bfa !important; color: #6d28d9 !important; }
    html.light .chip-hr       { background: rgba(37,99,235,0.12) !important; border: 1px solid rgba(37,99,235,0.38) !important; color: #1d4ed8 !important; }
    html.light .chip-branch   { background: #d1fae5 !important; border: 1px solid #34d399 !important; color: #047857 !important; }
    html.light .chip-employee { background: #fef3c7 !important; border: 1px solid #fbbf24 !important; color: #b45309 !important; }

    /* Role card background */
    html.light .role-card {
        background: #ffffff !important;
        border-color: #e2e8f0 !important;
        box-shadow: 0 1px 4px rgba(15,23,42,0.06) !important;
    }
    html.light .role-card:hover {
        background: #f8fafc !important;
        border-color: #cbd5e1 !important;
    }
    html.light .role-email { color: #64748b !important; }

    /* "password" badge — full CSS control */
    .pwd-badge {
        background: rgba(59,130,246,0.10);
        border: 1px solid rgba(59,130,246,0.22);
        color: #60a5fa;
    }
    html.light .pwd-badge {
        background: rgba(37,99,235,0.12) !important;
        border: 1px solid rgba(37,99,235,0.38) !important;
        color: #1d4ed8 !important;
    }
</style>
