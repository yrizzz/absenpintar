<div class="min-h-screen flex flex-col lg:flex-row bg-[#090e1a] relative overflow-hidden font-sans text-slate-100">
    <!-- Decorative Ambient Glows -->
    <div
        class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-blue-500/10 blur-[120px] pointer-events-none animate-pulse-subtle">
    </div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] rounded-full bg-emerald-500/10 blur-[100px] pointer-events-none animate-pulse-subtle"
        style="animation-delay: 2s;"></div>

    <!-- Left Side - Showcase (Deep Premium Royal Blue Background with High-End Glassmorphic Floating Cards) -->
    <div
        class="hidden lg:flex lg:w-[55%] p-16 flex-col justify-between relative overflow-hidden bg-premium-mesh border-r border-slate-800">
        <!-- Abstract Decorative Light Grids on Blue -->
        <div
            class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-sky-400/15 via-transparent to-transparent pointer-events-none">
        </div>
        <div
            class="absolute top-[-20%] left-[-20%] w-[500px] h-[500px] rounded-full bg-sky-500/10 blur-[120px] pointer-events-none">
        </div>
        <div
            class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] rounded-full bg-indigo-500/15 blur-[120px] pointer-events-none">
        </div>

        <!-- Floating showcase card 1: Liveness scanner (Translucent Blue Glass Card) -->
        <div
            class="absolute top-1/4 right-[10%] w-72 h-72 rounded-3xl bg-[#121d33]/50 backdrop-blur-xl border border-white/10 shadow-2xl animate-float p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex space-x-2">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                </div>
                <span
                    class="text-[10px] font-bold text-sky-400 bg-sky-950/60 px-2.5 py-0.5 rounded-full uppercase tracking-wider">Mesin
                    Liveness</span>
            </div>
            <!-- Mock face scanning illustration -->
            <div class="my-auto py-4 flex flex-col items-center">
                <div class="relative">
                    <div class="w-20 h-20 rounded-full border-2 border-dashed border-sky-400/40 flex items-center justify-center p-1.5 animate-spin"
                        style="animation-duration: 20s;">
                        <div class="w-full h-full rounded-full border border-sky-400/20"></div>
                    </div>
                    <svg class="w-8 h-8 text-sky-400 absolute inset-0 m-auto" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-white mt-3 tracking-wide">Verifikasi Biometrik</span>
                <span class="text-[10px] text-sky-200/70">Skor Risiko: 0.02 (Optimal)</span>
            </div>
            <div class="flex items-center justify-between border-t border-white/5 pt-3 text-[10px] text-sky-200/50">
                <span>Akurasi: 99.8%</span>
                <span class="text-emerald-400 font-bold flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1 animate-pulse"></span> Aman
                </span>
            </div>
        </div>

        <!-- Floating showcase card 2: Geofence status (Translucent Blue Glass Card) -->
        <div class="absolute bottom-16 right-[15%] w-60 h-36 rounded-2xl bg-[#121d33]/50 backdrop-blur-lg border border-white/10 shadow-2xl p-5 flex flex-col justify-between"
            style="animation: float 8s ease-in-out infinite; animation-delay: 1.5s;">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-sky-950/60 flex items-center justify-center text-sky-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-white">Status Geofence</h4>
                    <p class="text-[10px] text-sky-200/60">Perimeter Kantor Pusat</p>
                </div>
            </div>
            <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                <div class="h-full bg-sky-400 rounded-full w-[85%]"></div>
            </div>
            <div class="flex items-center justify-between text-[10px] text-sky-200/70">
                <span>Jarak: 12 meter</span>
                <span class="text-sky-400 font-semibold">Zona Valid</span>
            </div>
        </div>

        <!-- Brand Logo Header -->
        <div class="relative z-10">
            <div class="flex items-center space-x-3.5 mb-16">
                <div class="w-11 h-11 bg-white rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <span class="text-2xl font-black tracking-tight text-white font-display">Absen<span
                            class="text-sky-400">Pintar</span></span>
                    <span class="block text-[9px] text-sky-300 font-black tracking-widest uppercase">Sistem
                        Verifikasi</span>
                </div>
            </div>

            <!-- Features Headline -->
            <div class="space-y-8 max-w-lg mt-6">
                <div class="space-y-4">
                    <div
                        class="inline-flex items-center space-x-2 bg-white/5 border border-white/10 px-3 py-1 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-sky-200 uppercase tracking-wider">Edisi Premium
                            v5.0</span>
                    </div>
                    <h1
                        class="text-4xl font-extrabold text-white leading-[1.15] font-display tracking-tight lg:text-5xl">
                        Cepat Tepat Dan Pintar<br><span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-sky-300 to-emerald-400">Verifikasi
                            Karyawan.</span>
                    </h1>
                    <p class="text-base text-slate-300 font-normal leading-relaxed">
                        Sistem validasi kehadiran mutakhir menggunakan pembatasan GPS multi-layer,
                        foto selfie biometrik real-time, dan telemetri anti-manipulasi instan.
                    </p>
                </div>

                <!-- Feature List -->
                <div class="space-y-4 pt-6 max-w-md">
                    <!-- Feature item -->
                    <div
                        class="flex items-start space-x-4 p-4 rounded-2xl hover:bg-white/5 transition-all duration-300">
                        <div class="p-2.5 bg-white/5 border border-white/5 rounded-xl text-sky-400 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-sm">Geofencing & Pencocokan Akurasi</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Validasi otomatis perimeter fisik menggunakan
                                verifikasi sinyal GPS tingkat lanjut.</p>
                        </div>
                    </div>

                    <!-- Feature item -->
                    <div
                        class="flex items-start space-x-4 p-4 rounded-2xl hover:bg-white/5 transition-all duration-300">
                        <div class="p-2.5 bg-white/5 border border-white/5 rounded-xl text-sky-400 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-sm">Pengambilan Biometrik & Analisis Wajah</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Deteksi keaslian (liveness) memeriksa input kamera
                                untuk memblokir manipulasi media virtual secara penuh.</p>
                        </div>
                    </div>

                    <!-- Feature item -->
                    <div
                        class="flex items-start space-x-4 p-4 rounded-2xl hover:bg-white/5 transition-all duration-300">
                        <div class="p-2.5 bg-white/5 border border-white/5 rounded-xl text-sky-400 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white font-bold text-sm">Perlindungan Keamanan 7 Lapis</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Analisis berkelanjutan terhadap ketidakcocokan
                                zona waktu, kecepatan perjalanan mustahil, dan rute VPN.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Showcase Footer -->
        <div class="relative z-10 text-xs text-slate-400 flex items-center space-x-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
            <span>© 2026 AbsenPintar Systems. Aman, patuh, dan terverifikasi.</span>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="flex-1 flex flex-col justify-center items-center p-6 sm:p-12 relative z-10">
        <div class="w-full max-w-[430px]">

            <!-- Mobile Brand Header (Visible on smaller screens only) -->
            <div class="lg:hidden text-center mb-10">
                <div class="inline-flex items-center space-x-3 mb-2">
                    <div
                        class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-sky-500 rounded-xl flex items-center justify-center shadow-md shadow-blue-600/10">
                        <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black text-white font-display">Absen<span
                            class="text-blue-600">Pintar</span></span>
                </div>
                <p class="text-slate-400 text-xs uppercase tracking-widest font-semibold">Verifikasi Kehadiran
                    Perusahaan</p>
            </div>

            <!-- Login Card (Premium Midnight Frosted Glass Card) -->
            <div
                class="bg-[#121d33]/85 backdrop-blur-xl border border-white/10 rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl pointer-events-none">
                </div>

                <div class="mb-8 relative">
                    <h2 class="text-3xl font-extrabold text-white tracking-tight font-display">Masuk</h2>
                    <p class="mt-2 text-sm text-slate-400">Akses ruang kerja dan log absensi Anda.</p>
                </div>

                <form wire:submit="login" class="space-y-6">
                    <!-- Email Input -->
                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400">
                            Alamat Email
                        </label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </span>
                            <input wire:model="email" id="email" type="email" required autofocus
                                class="w-full pl-11 pr-4 py-3 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all duration-200 @error('email') border-red-500/40 focus:border-red-500 focus:ring-red-500/10 @enderror"
                                placeholder="you@company.com">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-400 flex items-center space-x-1">
                                <span class="w-1 h-1 rounded-full bg-red-400"></span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password"
                                class="block text-xs font-bold uppercase tracking-wider text-slate-400">
                                Kata Sandi
                            </label>
                            <a href="#"
                                class="text-xs font-semibold text-sky-400 hover:text-sky-300 transition-colors">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <div class="relative" x-data="{ showPassword: false }">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input wire:model="password" id="password" :type="showPassword ? 'text' : 'password'" required
                                class="w-full pl-11 pr-12 py-3 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all duration-200 @error('password') border-red-500/40 focus:border-red-500 focus:ring-red-500/10 @enderror"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200 transition-colors focus:outline-none cursor-pointer">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-400 flex items-center space-x-1">
                                <span class="w-1 h-1 rounded-full bg-red-400"></span>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox"
                            class="w-4.5 h-4.5 rounded-lg border-white/10 bg-[#0d1527] text-blue-600 focus:ring-blue-600/30 focus:ring-offset-0">
                        <label for="remember"
                            class="ml-2.5 block text-xs font-semibold text-slate-400 hover:text-slate-200 select-none cursor-pointer transition-colors">
                            Tetap masuk selama 30 hari
                        </label>
                    </div>

                    <!-- Submit Button with perfect centering flex classes matching the gradient design -->
                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled"
                            class="w-full px-5 py-3.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-sm font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 focus:outline-none focus:ring-4 focus:ring-blue-600/20 shadow-button-primary hover:shadow-button-primary-hover hover:translate-y-[-1px] active:translate-y-[0px] flex items-center justify-center transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span wire:loading.remove class="flex items-center justify-center w-full">Masuk ke Ruang
                                Absensi</span>
                            <span wire:loading.flex class="items-center justify-center w-full space-x-2"
                                style="display: none;">
                                <svg class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span>Mengautentikasi dengan aman...</span>
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span
                            class="px-4 bg-[#121d33] text-[10px] font-black text-slate-400 uppercase tracking-widest">Kredensial
                            Ruang Kerja</span>
                    </div>
                </div>

                <!-- Demo Credentials Grid -->
                <div class="space-y-3.5">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="py-2.5 px-3 bg-[#0d1527]/80 border border-white/10 rounded-xl hover:border-blue-500/40 hover:bg-blue-500/5 transition-all cursor-pointer group"
                            onclick="document.getElementById('email').value = 'admin@AbsenPintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                            <p
                                class="text-[9px] font-black text-slate-400 uppercase tracking-wider group-hover:text-sky-400 transition-colors">
                                Super Admin</p>
                            <p class="text-xs text-slate-300 font-mono mt-0.5 truncate">admin@AbsenPintar.com</p>
                        </div>
                        <div class="py-2.5 px-3 bg-[#0d1527]/80 border border-white/10 rounded-xl hover:border-blue-500/40 hover:bg-blue-500/5 transition-all cursor-pointer group"
                            onclick="document.getElementById('email').value = 'hr@AbsenPintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                            <p
                                class="text-[9px] font-black text-slate-400 uppercase tracking-wider group-hover:text-sky-400 transition-colors">
                                HR Manager</p>
                            <p class="text-xs text-slate-300 font-mono mt-0.5 truncate">hr@AbsenPintar.com</p>
                        </div>
                        <div class="py-2.5 px-3 bg-[#0d1527]/80 border border-white/10 rounded-xl hover:border-blue-500/40 hover:bg-blue-500/5 transition-all cursor-pointer group"
                            onclick="document.getElementById('email').value = 'manager@AbsenPintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                            <p
                                class="text-[9px] font-black text-slate-400 uppercase tracking-wider group-hover:text-sky-400 transition-colors">
                                Branch Head</p>
                            <p class="text-xs text-slate-300 font-mono mt-0.5 truncate">manager@AbsenPintar.com</p>
                        </div>
                        <div class="py-2.5 px-3 bg-[#0d1527]/80 border border-white/10 rounded-xl hover:border-blue-500/40 hover:bg-blue-500/5 transition-all cursor-pointer group"
                            onclick="document.getElementById('email').value = 'employee4@AbsenPintar.com'; document.getElementById('email').dispatchEvent(new Event('input'))">
                            <p
                                class="text-[9px] font-black text-slate-400 uppercase tracking-wider group-hover:text-sky-400 transition-colors">
                                Employee</p>
                            <p class="text-xs text-slate-300 font-mono mt-0.5 truncate">employee4@AbsenPintar.com</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center space-x-2 pt-1">
                        <span class="text-xs text-slate-400">Kata Sandi:</span>
                        <span
                            class="text-xs font-mono font-bold text-sky-400 bg-sky-950/60 border border-sky-850 px-2.5 py-0.5 rounded-lg select-all">password</span>
                    </div>
                </div>
            </div>

            <!-- Mobile Footer -->
            <div class="lg:hidden text-center mt-8">
                <p class="text-xs text-slate-400">© 2026 AbsenPintar. Hak Cipta Dilindungi.</p>
            </div>

        </div>
    </div>
</div>
