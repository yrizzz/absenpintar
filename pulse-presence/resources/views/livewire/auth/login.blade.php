<div class="min-h-screen flex flex-col lg:flex-row bg-[#090f1d] font-sans text-slate-200 relative overflow-hidden">
    <!-- Ambient background light glows -->
    <div class="absolute top-[-10%] left-[-10%] w-[550px] h-[550px] rounded-full bg-blue-600/10 blur-[110px] pointer-events-none animate-pulse-subtle"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[550px] h-[550px] rounded-full bg-indigo-600/10 blur-[110px] pointer-events-none animate-pulse-subtle" style="animation-delay: 3s;"></div>

    <!-- Fine Tech Grid Overlay -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.01)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.01)_1px,transparent_1px)] bg-[size:2rem_2rem] pointer-events-none"></div>

    <!-- Left Column: Informative System Flow & Status -->
    <div class="hidden lg:flex lg:w-[45%] bg-[#0e1629]/90 border-r border-white/5 p-10 flex-col justify-between relative overflow-y-auto">
        <!-- Logo -->
        <div class="relative z-10 flex items-center space-x-3">
            <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/15">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <span class="text-lg font-black tracking-tight text-white font-display block leading-none">Absen<span class="text-blue-500">Pintar</span></span>
                <span class="text-[8px] text-blue-400 font-extrabold tracking-widest uppercase mt-0.5 block">Enterprise Presence</span>
            </div>
        </div>

        <!-- Info Center / Tech Guide -->
        <div class="relative z-10 my-auto pr-6 space-y-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-blue-500/10 border border-blue-500/20 px-2.5 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    <span class="text-[9px] font-bold text-blue-300 uppercase tracking-wider">Sistem Validasi Multi-Tahap</span>
                </div>
                <h1 class="text-4xl lg:text-4.5xl font-black text-white leading-[1.15] font-display tracking-tight">
                    Verifikasi Kehadiran<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-blue-200 to-indigo-300">Aman & Terdesentralisasi</span>
                </h1>
                <p class="text-xs text-slate-400 leading-relaxed max-w-sm mt-2">
                    Akses aman ke dashboard administrasi dan manajemen absensi karyawan terintegrasi.
                </p>
            </div>

            <!-- Workflow Flowchart (Compact & Elegant) -->
            <div class="space-y-3.5 max-w-sm">
                <!-- Step 1 -->
                <div class="flex items-start space-x-3 p-3 bg-white/5 border border-white/5 rounded-xl hover:bg-white/10 transition-colors">
                    <div class="w-6 h-6 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center font-bold text-[11px] flex-shrink-0">
                        1
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Autentikasi Akun</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Memeriksa kredensial terdaftar & hak akses berdasarkan peran sistem.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex items-start space-x-3 p-3 bg-white/5 border border-white/5 rounded-xl hover:bg-white/10 transition-colors">
                    <div class="w-6 h-6 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center font-bold text-[11px] flex-shrink-0">
                        2
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Pencocokan Geofence</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Memastikan koordinat GPS perangkat berada di dalam perimeter cabang aktif.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex items-start space-x-3 p-3 bg-white/5 border border-white/5 rounded-xl hover:bg-white/10 transition-colors">
                    <div class="w-6 h-6 rounded bg-blue-500/10 text-blue-400 flex items-center justify-center font-bold text-[11px] flex-shrink-0">
                        3
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Liveness & Biometrik</h4>
                        <p class="text-[11px] text-slate-400 mt-0.5">Pencocokan foto selfie real-time dengan model anti-manipulasi media virtual.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Indicators -->
        <div class="relative z-10 flex items-center space-x-5 text-[10px] text-slate-500">
            <div class="flex items-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Server API Aktif</span>
            </div>
            <div class="flex items-center space-x-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>AI Core Online</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Compact & Sleek Login Form -->
    <div class="flex-1 flex flex-col justify-center items-center p-6 relative">
        <div class="w-full max-w-[360px]">
            <!-- Mobile Header Logo -->
            <div class="lg:hidden text-center mb-6">
                <div class="inline-flex items-center space-x-2.5 mb-1">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-xl font-black text-white font-display">Absen<span class="text-blue-500">Pintar</span></span>
                </div>
                <p class="text-[9px] text-blue-400 font-extrabold tracking-wider uppercase">Sistem Kehadiran Biometrik</p>
            </div>

            <!-- Login Form Container (Sharp, Compact, and Elegant) -->
            <div class="bg-[#121c32]/80 border border-white/10 rounded-xl p-6 shadow-2xl backdrop-blur-xl">
                <div class="mb-5">
                    <h2 class="text-xl font-bold text-white tracking-tight font-display">Sign In</h2>
                    <p class="text-xs text-slate-400 mt-1">Akses panel verifikasi ruang kerja Anda.</p>
                </div>

                <form wire:submit="login" class="space-y-4">
                    <!-- Email -->
                    <div class="space-y-1">
                        <label for="email" class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </span>
                            <input wire:model="email" id="email" type="email" required autofocus
                                class="w-full pl-9 pr-3.5 py-2 bg-[#090e1c] border border-white/10 rounded-lg text-xs text-white placeholder-slate-650 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('email') border-red-500/40 focus:border-red-500 @enderror"
                                placeholder="nama@perusahaan.com">
                        </div>
                        @error('email')
                            <p class="mt-1 text-[10px] text-red-400 flex items-center space-x-1">
                                <span class="w-1 h-1 rounded-full bg-red-400"></span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">
                                Kata Sandi
                            </label>
                            <a href="#" class="text-[9px] font-bold text-blue-400 hover:text-blue-300 transition-colors">
                                Lupa sandi?
                            </a>
                        </div>
                        <div class="relative" x-data="{ showPassword: false }">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input wire:model="password" id="password" :type="showPassword ? 'text' : 'password'" required
                                class="w-full pl-9 pr-10 py-2 bg-[#090e1c] border border-white/10 rounded-lg text-xs text-white placeholder-slate-650 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 @error('password') border-red-500/40 focus:border-red-500 @enderror"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-200 transition-colors focus:outline-none cursor-pointer">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-[10px] text-red-400 flex items-center space-x-1">
                                <span class="w-1 h-1 rounded-full bg-red-400"></span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label class="flex items-center cursor-pointer select-none">
                            <input wire:model="remember" id="remember" type="checkbox"
                                class="w-3.5 h-3.5 rounded border-white/10 bg-[#090e1c] text-blue-500 focus:ring-blue-500/20 focus:ring-offset-0 transition-colors">
                            <span class="ml-2 text-[11px] text-slate-400 hover:text-slate-200 transition-colors">Tetap masuk</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1.5">
                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs rounded-lg shadow-lg shadow-blue-500/5 hover:shadow-blue-500/15 focus:outline-none focus:ring-4 focus:ring-blue-500/15 transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove>Masuk ke Akun</span>
                            <span wire:loading.flex class="items-center justify-center space-x-2" style="display: none;">
                                <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Mengautentikasi...</span>
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Simulator Account Tabs (Ultra Compact Horizontal selector) -->
                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/5"></div>
                    </div>
                    <div class="relative flex justify-center text-[8px] uppercase font-black tracking-wider">
                        <span class="px-2 bg-[#121c32] text-slate-500">Pilih Role Simulasi</span>
                    </div>
                </div>

                <!-- Small Grid of Clickable Badge Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                    <button type="button" 
                        onclick="document.getElementById('email').value = 'admin@absenpintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="text-[10px] py-1.5 px-2 bg-[#090e1c] border border-white/5 hover:border-blue-500/40 hover:bg-blue-500/5 rounded-md text-left transition-all flex flex-col justify-between group">
                        <span class="font-bold text-slate-400 group-hover:text-blue-400">Super Admin</span>
                        <span class="text-[9px] text-slate-500 font-mono mt-0.5 truncate">admin@absenpintar.com</span>
                    </button>

                    <button type="button" 
                        onclick="document.getElementById('email').value = 'hr@absenpintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="text-[10px] py-1.5 px-2 bg-[#090e1c] border border-white/5 hover:border-blue-500/40 hover:bg-blue-500/5 rounded-md text-left transition-all flex flex-col justify-between group">
                        <span class="font-bold text-slate-400 group-hover:text-blue-400">HR Manager</span>
                        <span class="text-[9px] text-slate-500 font-mono mt-0.5 truncate">hr@absenpintar.com</span>
                    </button>

                    <button type="button" 
                        onclick="document.getElementById('email').value = 'manager@absenpintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="text-[10px] py-1.5 px-2 bg-[#090e1c] border border-white/5 hover:border-blue-500/40 hover:bg-blue-500/5 rounded-md text-left transition-all flex flex-col justify-between group">
                        <span class="font-bold text-slate-400 group-hover:text-blue-400">Branch Head</span>
                        <span class="text-[9px] text-slate-500 font-mono mt-0.5 truncate">manager@absenpintar.com</span>
                    </button>

                    <button type="button" 
                        onclick="document.getElementById('email').value = 'employee4@absenpintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))"
                        class="text-[10px] py-1.5 px-2 bg-[#090e1c] border border-white/5 hover:border-blue-500/40 hover:bg-blue-500/5 rounded-md text-left transition-all flex flex-col justify-between group">
                        <span class="font-bold text-slate-400 group-hover:text-blue-400">Employee</span>
                        <span class="text-[9px] text-slate-500 font-mono mt-0.5 truncate">employee4@absenpintar.com</span>
                    </button>
                </div>

                <div class="flex items-center justify-center space-x-1.5 pt-3 border-t border-white/5 mt-3 text-[10px]">
                    <span class="text-slate-500 font-medium">Sandi default:</span>
                    <span class="font-mono font-bold text-blue-400 bg-blue-500/10 border border-blue-500/25 px-1.5 py-0.5 rounded select-all">password</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-[9px] text-slate-500">© 2026 AbsenPintar. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>
</div>
