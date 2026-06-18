<div class="login-root min-h-screen flex flex-col lg:flex-row bg-[#f8fafc] dark:bg-[#081125] font-sans relative overflow-hidden">

    {{-- ════════════════════════════════════
         LEFT PANEL (Blue Gradient & Branding)
    ════════════════════════════════════ --}}
    <div class="left-panel hidden lg:flex lg:w-1/2 flex-col justify-between p-16 xl:p-24 relative overflow-hidden select-none">
        
        {{-- Concentric Wave Arcs (top-left, mirrored of bottom-right) --}}
        <svg class="absolute top-0 left-0 w-[450px] h-[450px] text-white/[0.08] pointer-events-none z-10" viewBox="0 0 500 500" fill="none" stroke="currentColor" stroke-width="1.2">
            <path d="M 0,420 A 420,420 0 0 1 420,0" />
            <path d="M 0,380 A 380,380 0 0 1 380,0" />
            <path d="M 0,340 A 340,340 0 0 1 340,0" />
            <path d="M 0,300 A 300,300 0 0 1 300,0" />
            <path d="M 0,260 A 260,260 0 0 1 260,0" />
            <path d="M 0,220 A 220,220 0 0 1 220,0" />
            <path d="M 0,180 A 180,180 0 0 1 180,0" />
            <path d="M 0,140 A 140,140 0 0 1 140,0" />
            <path d="M 0,100 A 100,100 0 0 1 100,0" />
            <path d="M 0,60 A 60,60 0 0 1 60,0" />
        </svg>

        {{-- Concentric Wave Arcs (centered at bottom-right 500,500) --}}
        <svg class="absolute bottom-0 right-0 w-[450px] h-[450px] text-white/[0.14] pointer-events-none z-10" viewBox="0 0 500 500" fill="none" stroke="currentColor" stroke-width="1.2">
            <path d="M 80,500 A 420,420 0 0 1 500,80" />
            <path d="M 120,500 A 380,380 0 0 1 500,120" />
            <path d="M 160,500 A 340,340 0 0 1 500,160" />
            <path d="M 200,500 A 300,300 0 0 1 500,200" />
            <path d="M 240,500 A 260,260 0 0 1 500,240" />
            <path d="M 280,500 A 220,220 0 0 1 500,280" />
            <path d="M 320,500 A 180,180 0 0 1 500,320" />
            <path d="M 360,500 A 140,140 0 0 1 500,360" />
            <path d="M 400,500 A 100,100 0 0 1 500,400" />
            <path d="M 440,500 A 60,60 0 0 1 500,440" />
        </svg>

        {{-- Grid of Dots --}}
        <div class="absolute right-12 top-[42%] -translate-y-1/2 grid grid-cols-6 gap-x-3.5 gap-y-3.5 opacity-30 pointer-events-none z-10">
            @for ($i = 0; $i < 24; $i++)
                <div class="w-1 h-1 rounded-full bg-white"></div>
            @endfor
        </div>

        {{-- Top Logo --}}
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 bg-white/10 border border-white/20 rounded-xl flex items-center justify-center backdrop-blur-md shadow-sm">
                <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-white tracking-tight">PresensiKu</span>
        </div>

        {{-- Center Content --}}
        <div class="space-y-10 my-auto relative z-10">
            <div class="space-y-6">
                {{-- System Badge --}}
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    <span class="text-[11px] font-semibold text-white tracking-wide">Sistem Kehadiran Cerdas</span>
                </div>
                
                {{-- Heading --}}
                <h1 class="text-5xl font-extrabold text-white leading-[1.1] tracking-tight">
                    Hadir lebih cerdas &<br>
                    aman.
                </h1>
                
                {{-- Subtitle --}}
                <p class="text-sm text-white/70 max-w-sm leading-relaxed">
                    Verifikasi wajah real-time, geofencing otomatis, dan pelaporan kehadiran terpusat untuk seluruh tim Anda.
                </p>
            </div>

            {{-- Feature list --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded-full bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm text-white/95">Verifikasi wajah & selfie real-time</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded-full bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm text-white/95">Validasi lokasi GPS + geofence</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded-full bg-white/10 border border-white/20 flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm text-white/95">Laporan & audit kehadiran terpusat</span>
                </div>
            </div>
        </div>

        {{-- Bottom Status --}}
        <div class="flex items-center gap-6 text-xs text-white/50 relative z-10">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
                <span>Server aktif</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></span>
                <span>AI Core online</span>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════
         RIGHT PANEL (Login Form)
    ════════════════════════════════════ --}}
    <div class="right-panel flex-1 flex flex-col justify-between p-8 sm:p-12 lg:p-16 min-h-screen lg:min-h-0 relative z-10 bg-white dark:bg-gradient-to-tr dark:from-[#0a1632] dark:to-[#102558]">
        
        {{-- Theme Toggle --}}
        <div class="absolute top-6 right-6 z-50" x-data="{
            isDark: document.documentElement.classList.contains('dark'),
            init() {
                const observer = new MutationObserver(() => {
                    this.isDark = document.documentElement.classList.contains('dark');
                });
                observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
            },
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        }">
            <button @click="toggleTheme()"
                class="w-9 h-9 rounded-full transition-all flex items-center justify-center backdrop-blur-md bg-slate-100 border border-slate-200 text-slate-600 hover:bg-slate-200 dark:bg-white/5 dark:border-white/10 dark:text-slate-300 dark:hover:bg-white/10"
                title="Ganti Tema">
                {{-- Sun icon (visible when dark, switches to light) --}}
                <svg x-show="isDark" class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="4" />
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41" />
                </svg>
                {{-- Moon icon (visible when light, switches to dark) --}}
                <svg x-show="!isDark" class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                </svg>
            </button>
        </div>

        {{-- Top spacer --}}
        <div></div>

        {{-- Center Content --}}
        <div class="w-full max-w-[420px] mx-auto space-y-6">
            
            {{-- Mobile Logo --}}
            <div class="lg:hidden flex flex-col items-center gap-2 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                    <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">PresensiKu</span>
            </div>

            {{-- Heading --}}
            <div class="text-center space-y-1">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Selamat datang</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Masuk ke akun PresensiKu Anda.</p>
            </div>

            {{-- Login Card --}}
            <div class="rounded-2xl p-7 border border-slate-200 dark:border-blue-500/20 bg-white dark:bg-[#0e2046]/80 backdrop-blur-xl shadow-2xl">
                <form wire:submit="login" class="space-y-5">
                    
                    {{-- Email --}}
                    <div class="space-y-2">
                        <label for="email" class="text-xs font-semibold text-slate-600 dark:text-slate-400 tracking-wider">Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3.5 flex items-center text-slate-450 dark:text-slate-500 pointer-events-none">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input wire:model="email" id="email" type="email" required autofocus
                                placeholder="nama@perusahaan.com"
                                class="w-full pl-11 pr-4 py-3 rounded-xl text-sm transition-all bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:bg-[#050c1e] dark:border-blue-500/20 dark:text-white dark:placeholder-slate-600 dark:focus:border-blue-500 dark:focus:ring-blue-500/25 @error('email') border-rose-500/40 @enderror">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-500 dark:text-rose-400 flex items-center gap-1 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 dark:bg-rose-400 flex-shrink-0"></span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-2">
                        <label for="password" class="text-xs font-semibold text-slate-600 dark:text-slate-400 tracking-wider">Kata sandi</label>
                        <div class="relative" x-data="{ show: false }">
                            <span class="absolute inset-y-0 left-3.5 flex items-center text-slate-455 dark:text-slate-500 pointer-events-none">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input wire:model="password" id="password" :type="show ? 'text' : 'password'" required
                                placeholder="••••••••"
                                class="w-full pl-11 pr-11 py-3 rounded-xl text-sm transition-all bg-white border border-slate-300 text-slate-900 placeholder-slate-400 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 dark:bg-[#050c1e] dark:border-blue-500/20 dark:text-white dark:placeholder-slate-600 dark:focus:border-blue-500 dark:focus:ring-blue-500/25 @error('password') border-rose-500/40 @enderror">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-355 transition-colors focus:outline-none">
                                <svg x-show="!show" class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-500 dark:text-rose-400 flex items-center gap-1 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 dark:bg-rose-400 flex-shrink-0"></span>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Remember --}}
                    <label class="flex items-center gap-2.5 cursor-pointer select-none">
                        <input wire:model="remember" id="remember" type="checkbox"
                            class="w-4.5 h-4.5 rounded border-slate-300 dark:border-blue-500/20 bg-white dark:bg-[#050c1e] text-blue-600 focus:ring-blue-500/20 focus:ring-offset-0">
                        <span class="text-xs text-slate-500 dark:text-slate-400">Tetap masuk di perangkat ini</span>
                    </label>

                    {{-- Submit Button --}}
                    <button type="submit" wire:loading.attr="disabled"
                        class="login-btn w-full py-3 rounded-xl text-white font-semibold text-sm transition-all duration-200 flex items-center justify-center gap-2 disabled:opacity-50">
                        <span wire:loading.remove class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Masuk ke akun
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

            {{-- Divider --}}
            <div class="flex items-center gap-3">
                <div class="flex-1 h-px bg-slate-200 dark:bg-white/[0.08]"></div>
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Demo Akun</span>
                <div class="flex-1 h-px bg-slate-200 dark:bg-white/[0.08]"></div>
            </div>

            {{-- Demo Accounts Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                {{-- Super Admin --}}
                <button type="button" wire:click="fillDemo('admin@presensiku.com')"
                    class="demo-card group flex items-center gap-3 p-3 rounded-xl transition-all text-left bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-350 dark:bg-[#09142e] dark:border-blue-500/15 dark:hover:bg-[#0c1b3f] dark:hover:border-blue-500/30">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors bg-blue-50 border border-blue-100 text-blue-600 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold leading-none text-slate-800 dark:text-white">Super Admin</p>
                        <p class="text-[10px] truncate mt-1 text-slate-500 dark:text-slate-400">admin@presensiku.com</p>
                    </div>
                </button>

                {{-- HR Manager --}}
                <button type="button" wire:click="fillDemo('hr@presensiku.com')"
                    class="demo-card group flex items-center gap-3 p-3 rounded-xl transition-all text-left bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-350 dark:bg-[#09142e] dark:border-blue-500/15 dark:hover:bg-[#0c1b3f] dark:hover:border-blue-500/30">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors bg-blue-50 border border-blue-100 text-blue-600 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold leading-none text-slate-800 dark:text-white">HR Manager</p>
                        <p class="text-[10px] truncate mt-1 text-slate-500 dark:text-slate-400">hr@presensiku.com</p>
                    </div>
                </button>

                {{-- Branch Head --}}
                <button type="button" wire:click="fillDemo('manager@presensiku.com')"
                    class="demo-card group flex items-center gap-3 p-3 rounded-xl transition-all text-left bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-350 dark:bg-[#09142e] dark:border-blue-500/15 dark:hover:bg-[#0c1b3f] dark:hover:border-blue-500/30">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors bg-blue-50 border border-blue-100 text-blue-600 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold leading-none text-slate-800 dark:text-white">Branch Head</p>
                        <p class="text-[10px] truncate mt-1 text-slate-500 dark:text-slate-400">manager@presensiku.com</p>
                    </div>
                </button>

                {{-- Employee --}}
                <button type="button" wire:click="fillDemo('employee4@presensiku.com')"
                    class="demo-card group flex items-center gap-3 p-3 rounded-xl transition-all text-left bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-350 dark:bg-[#09142e] dark:border-blue-500/15 dark:hover:bg-[#0c1b3f] dark:hover:border-blue-500/30">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors bg-blue-50 border border-blue-100 text-blue-600 dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2m16-10a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold leading-none text-slate-800 dark:text-white">Employee</p>
                        <p class="text-[10px] truncate mt-1 text-slate-500 dark:text-slate-400">employee4@presensiku.com</p>
                    </div>
                </button>
            </div>

            {{-- Sandi info --}}
            <div class="flex items-center justify-center gap-2 text-xs text-slate-500">
                <span>Sandi default:</span>
                <span class="font-mono font-bold px-2 py-0.5 rounded-lg text-xs bg-slate-100 border border-slate-200 text-slate-700 dark:bg-blue-500/10 dark:border-blue-500/25 dark:text-blue-400">password</span>
            </div>
        </div>

        {{-- Copyright footer --}}
        <div class="text-center mt-6">
            <p class="text-[11px] text-slate-400 dark:text-slate-600">© 2026 PresensiKu. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</div>

<style>
    /* ============================================================
       BASE CLASS DEFINITIONS
       ============================================================ */
    
    .left-panel {
        background: linear-gradient(135deg, #1d4ed8 0%, #153aa6 50%, #0a1f5c 100%);
        position: relative;
    }

    .left-panel::before {
        content: '';
        position: absolute;
        top: -10%;
        left: -10%;
        width: 80%;
        height: 80%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
        pointer-events: none;
        z-index: 1;
    }

    .left-panel::after {
        content: '';
        position: absolute;
        bottom: -10%;
        right: -10%;
        width: 80%;
        height: 80%;
        background: radial-gradient(circle, rgba(13, 110, 253, 0.2) 0%, transparent 70%);
        pointer-events: none;
        z-index: 1;
    }

    .login-btn {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%) !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4) !important;
    }
    
    .login-btn:hover {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%) !important;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.55) !important;
        transform: translateY(-0.5px);
    }
</style>
