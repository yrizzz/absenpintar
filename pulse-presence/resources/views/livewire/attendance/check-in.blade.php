<div class="py-6 min-h-screen text-slate-100 bg-transparent" x-data="checkInApp()" x-init="init()" x-on:destroy="destroy()">

    <!-- Inject Leaflet Assets directly to avoid bundle overhead -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
                class="btn-back mb-3 inline-flex items-center text-xs font-bold text-slate-400 hover:text-white transition-colors bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Dasbor
            </a>
            <h1 class="text-2xl font-extrabold text-white flex items-center">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 mr-2.5 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                Presensi Masuk Biometrik Live
            </h1>
            <p class="text-xs text-slate-400 mt-1">Dekatkan wajah dan pastikan Anda berada di dalam area kantor untuk melakukan absen masuk</p>
        </div>

        <!-- System Alerts (Flash messages from backend) -->
        @if (session()->has('error'))
            <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-[11px] font-medium flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div x-show="errorMessage" class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-[11px] font-medium flex items-center gap-2 shadow-md" style="display: none;">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span x-text="errorMessage"></span>
        </div>

        <!-- Layout Order: On mobile, Telemetry Matrix is placed on TOP (order-first) so user gets instant status feedback -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Telemetry Status Column: Order 1 on mobile, 2 on desktop -->
            <div class="lg:col-span-5 order-1 lg:order-2 space-y-6">
                
                <!-- Telemetry Dashboard Card -->
                <div class="bg-[#121c32]/85 border border-white/10 rounded-xl p-5 shadow-2xl space-y-5 backdrop-blur-md">
                    
                    <div class="flex items-center justify-between border-b border-b-white/5 pb-2.5">
                        <span class="text-[10px] font-extrabold text-blue-400 uppercase tracking-wider">MATRIX VALIDASI KEHADIRAN</span>
                        <span class="text-[10px] font-mono text-slate-400 font-bold">SHIFT: PAGI</span>
                    </div>

                    <!-- Auto-detect alert -->
                    <div x-show="$wire.locationValid && $wire.faceValid" class="p-3 bg-emerald-500/15 border border-emerald-500/25 text-emerald-300 rounded-xl text-xs font-bold text-center animate-pulse flex items-center justify-center space-x-2">
                        <svg class="animate-spin h-3.5 w-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Parameter terpenuhi. Mengirim otomatis...</span>
                    </div>

                    <!-- 1. Location Telemetry Card -->
                    <div class="bg-[#090e1c] border border-white/5 rounded-xl p-4 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-white flex items-center">
                                <svg class="w-4 h-4 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Geofence Lokasi Kantor
                            </span>
                            
                            <span x-show="!$wire.latitude" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-slate-800 text-slate-400 animate-pulse">Melacak...</span>
                            <span x-show="$wire.latitude && $wire.locationValid" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30" style="display:none;">DALAM RADIUS</span>
                            <span x-show="$wire.latitude && !$wire.locationValid" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-rose-500/20 text-rose-400 border border-rose-500/30 animate-pulse" style="display:none;">DI LUAR RADIUS</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-xs font-mono">
                            <div>
                                <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Jarak Anda</span>
                                <span x-show="$wire.latitude" class="font-bold text-white text-xs sm:text-sm" x-text="$wire.distanceFromBranch + ' meter'" style="display:none;"></span>
                                <span x-show="!$wire.latitude" class="font-bold text-white text-xs sm:text-sm">Mencari GPS...</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-slate-500 uppercase tracking-wider">Radius Batas</span>
                                <span class="font-bold text-white text-xs sm:text-sm" x-text="$wire.maxRadius + ' meter'"></span>
                            </div>
                        </div>

                        <div class="text-[10px] text-slate-400 bg-white/5 border border-white/5 rounded-lg px-3 py-2 flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0" :class="$wire.locationValid ? 'bg-emerald-400' : 'bg-rose-400'"></span>
                            <p class="font-medium" x-text="$wire.locationMessage"></p>
                        </div>

                        <div x-show="$wire.resolvedAddress" class="text-[10px] text-slate-400 bg-blue-500/5 border border-blue-500/10 rounded-lg px-3 py-2 flex items-start gap-2" style="display: none;">
                            <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <span class="block text-[8px] font-bold text-blue-400 uppercase tracking-wider mb-0.5">Alamat Terdeteksi</span>
                                <p class="font-medium text-slate-300 leading-relaxed" x-text="$wire.resolvedAddress"></p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Biometric Telemetry Card -->
                    <div class="bg-[#090e1c] border border-white/5 rounded-xl p-4 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-white flex items-center">
                                <svg class="w-4 h-4 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Verifikasi Biometrik Wajah
                            </span>

                            <span x-show="!$wire.selfieData && !cameraActive" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-slate-800 text-slate-400 animate-pulse">Menunggu...</span>
                            <span x-show="!$wire.selfieData && cameraActive" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-cyan-500/20 text-cyan-400 border border-cyan-500/30" style="display:none;">KAMERA AKTIF</span>
                            <span x-show="$wire.selfieData && $wire.faceValid" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-emerald-500/20 text-emerald-400 border border-emerald-500/30" style="display:none;">WAJAH COCOK</span>
                            <span x-show="$wire.selfieData && !$wire.faceValid" class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-rose-500/20 text-rose-400 border border-rose-500/30 animate-pulse" style="display:none;">TIDAK COCOK</span>
                        </div>

                        <!-- Similarity bar -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-slate-500 uppercase tracking-wider">Tingkat Kemiripan</span>
                                <span class="font-mono font-bold" :class="$wire.faceValid ? 'text-emerald-400' : 'text-rose-400'" x-text="$wire.faceSimilarity + '%'"></span>
                            </div>
                            <div class="w-full bg-[#121c32] rounded-full h-1.5 overflow-hidden border border-white/5">
                                <div class="h-full rounded-full transition-all duration-500" :class="$wire.faceValid ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-rose-500 to-amber-500'" :style="'width: ' + $wire.faceSimilarity + '%'"></div>
                            </div>
                        </div>

                        <div class="text-[10px] text-slate-400 bg-white/5 border border-white/5 rounded-lg px-3 py-2 flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0" :class="$wire.faceValid ? 'bg-emerald-400' : ($wire.selfieData ? 'bg-rose-400' : 'bg-slate-500')"></span>
                            <p class="font-medium" x-text="cameraActive && !$wire.selfieData ? 'Kamera aktif. Sistem memindai wajah secara otomatis...' : $wire.faceStatusMessage"></p>
                        </div>
                    </div>

                    <!-- Submission Action -->
                    <div class="pt-3 border-t border-t-white/5 flex justify-center">
                        <button x-show="$wire.locationValid && $wire.faceValid" wire:click="submit" type="button"
                            class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs rounded-lg shadow-lg shadow-emerald-500/5 transition-all flex items-center justify-center gap-1.5" style="display:none;">
                            <svg class="w-4 h-4 text-white animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Kirim Kehadiran Masuk
                        </button>
                        <button x-show="!($wire.locationValid && $wire.faceValid)" disabled type="button"
                            class="w-full py-2.5 bg-slate-800 text-slate-500 font-bold text-xs rounded-lg border border-white/5 flex items-center justify-center gap-1.5 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5 text-slate-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Menunggu Validasi AI & GPS</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Inputs Column (Camera & Map): Order 2 on mobile, 1 on desktop -->
            <div class="lg:col-span-7 order-2 lg:order-1 space-y-6">
                <!-- Camera Feed Card -->
                <div class="overflow-hidden rounded-xl border border-white/10 bg-[#121c32]/85 shadow-2xl relative">
                    <!-- Scanner Header -->
                    <div class="px-4 py-3.5 border-b border-b-white/5 bg-[#17243e]/50 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-blue-400 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-cyan-500 mr-2 animate-ping"></span>
                            PEMINDAI BIOMETRIK AKTIF
                        </span>
                        <span class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-blue-500/10 text-blue-400 border border-blue-500/20">
                            LIVE
                        </span>
                    </div>

                    <!-- Camera Feed Canvas -->
                    <div class="relative bg-slate-950 aspect-video flex items-center justify-center overflow-hidden">
                        <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover transform -scale-x-100" :class="cameraActive ? '' : 'hidden'"></video>
                        
                        <!-- Camera Placeholder -->
                        <div x-show="!cameraActive" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 space-y-3 z-10">
                            <div class="w-12 h-12 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-white">Mengaktifkan Kamera...</h4>
                                <p class="text-[10px] text-slate-500 max-w-xs mt-1">Berikan akses izin kamera agar sistem dapat memverifikasi wajah Anda</p>
                            </div>
                        </div>

                        <!-- Face Outline Overlay -->
                        <div x-show="cameraActive && !isAnalyzing" class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-10">
                            <!-- Center Tech Crosshair -->
                            <div class="w-12 h-12 border border-cyan-500/20 rounded-full flex items-center justify-center relative animate-pulse-subtle">
                                <div class="w-2 h-2 bg-cyan-400/40 rounded-full"></div>
                                <div class="absolute inset-0 border border-cyan-400/10 rounded-full scale-125"></div>
                            </div>
                            <div class="absolute bottom-4 text-[9px] font-extrabold text-cyan-300 tracking-widest uppercase bg-slate-950/85 px-3.5 py-1.5 rounded-full border border-cyan-500/30 shadow-lg shadow-cyan-950/40">
                                Posisikan Wajah Di Sini
                            </div>
                        </div>

                        <!-- Holographic Target Grid -->
                        <div x-show="cameraActive && !isAnalyzing" class="absolute inset-0 border-[3px] border-cyan-500/20 pointer-events-none z-10 m-4 rounded-xl flex items-center justify-center">
                            <!-- Glowing Scanner line in idle -->
                            <div class="absolute inset-x-4 h-[1.5px] bg-gradient-to-r from-transparent via-cyan-400/30 to-transparent animate-scan z-10"></div>
                            <!-- Bounding corners -->
                            <div class="absolute top-0 left-0 w-6 h-6 border-t-[3px] border-l-[3px] border-cyan-400 rounded-tl-sm"></div>
                            <div class="absolute top-0 right-0 w-6 h-6 border-t-[3px] border-r-[3px] border-cyan-400 rounded-tr-sm"></div>
                            <div class="absolute bottom-0 left-0 w-6 h-6 border-b-[3px] border-l-[3px] border-cyan-400 rounded-bl-sm"></div>
                            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-[3px] border-r-[3px] border-cyan-400 rounded-br-sm"></div>
                        </div>

                        <!-- Analyzing Scanner Overlay -->
                        <div x-show="isAnalyzing" class="absolute inset-0 bg-[#090f1d]/75 backdrop-blur-sm flex flex-col items-center justify-center z-20 space-y-4" style="display: none;">
                            <!-- Glowing Scanner line -->
                            <div class="absolute inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-scan z-20"></div>
                            <!-- Double spinning rings for scanning -->
                            <div class="w-16 h-16 relative">
                                <div class="absolute inset-0 rounded-full border-4 border-t-cyan-500 border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
                                <div class="absolute inset-2 rounded-full border-4 border-t-blue-400 border-r-transparent border-b-transparent border-l-transparent animate-spin-reverse" style="animation-duration: 1s;"></div>
                            </div>
                            <div class="text-center z-10">
                                <h4 class="text-xs font-extrabold text-cyan-400 tracking-wider uppercase animate-pulse">Memindai Wajah...</h4>
                                <p class="text-[10px] text-slate-400 mt-1">Mengukur koordinat biometrik wajah Anda</p>
                            </div>
                        </div>
                    </div>

                    <!-- Manual Verification Trigger Panel -->
                    <div class="p-4 bg-[#17243e]/30 border-t border-t-white/5 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-[10px] text-slate-400 font-medium text-center sm:text-left">
                            <span class="block text-white font-bold text-xs mb-0.5">Petunjuk Biometrik:</span>
                            Pastikan pencahayaan cukup dan wajah terlihat jelas tanpa aksesoris berlebih.
                        </div>
                        <div class="flex-shrink-0 w-full sm:w-auto">
                            <!-- Automatic Scanning Badge/Status Indicator -->
                            <div 
                                x-show="cameraActive" 
                                class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-cyan-600/10 to-blue-600/10 border border-cyan-500/30 text-cyan-400 font-extrabold text-xs rounded-xl shadow-lg flex items-center justify-center gap-2 select-none">
                                <span class="relative flex h-2 w-2">
                                    <span :class="isAnalyzing ? 'animate-ping' : ''" class="absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2" :class="isAnalyzing ? 'bg-cyan-400' : 'bg-cyan-500'"></span>
                                </span>
                                <span x-text="isAnalyzing ? 'Menganalisis Wajah...' : 'Mendeteksi Wajah Otomatis...'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leaflet OpenStreetMap Map Card (Google Maps Look & Feel) -->
                <div wire:ignore class="overflow-hidden rounded-xl border border-white/10 bg-[#121c32]/85 shadow-2xl relative">
                    <div class="px-4 py-3.5 border-b border-b-white/5 bg-[#17243e]/50 flex items-center justify-between">
                        <span class="text-[10px] font-bold text-blue-400 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            Peta Lokasi Presensi (Google Maps View)
                        </span>
                        <span class="text-[9px] px-2 py-0.5 font-bold uppercase rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                            GPS AKTIF
                        </span>
                    </div>
                    <div id="leaflet-map" wire:ignore class="h-60 w-full relative z-0"></div>
                </div>
            </div>

        </div>

    </div>

    <!-- Full-screen Auto-Submission Processing Overlay -->
    <div x-show="$wire.isSubmitting" class="fixed inset-0 bg-[#090f1d]/90 backdrop-blur-md flex flex-col items-center justify-center z-50 transition-all duration-300" style="display: none;">
        <div class="w-20 h-20 relative mb-6">
            <!-- Double spinning rings -->
            <div class="absolute inset-0 rounded-full border-4 border-t-emerald-500 border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
            <div class="absolute inset-2.5 rounded-full border-4 border-t-teal-400 border-r-transparent border-b-transparent border-l-transparent animate-spin-reverse" style="animation-duration: 1.2s;"></div>
            <!-- Pulsing central indicator -->
            <div class="absolute inset-5 bg-emerald-500 rounded-full animate-pulse"></div>
        </div>
        <h3 class="text-lg font-extrabold text-white tracking-tight">Presensi Berhasil Terdeteksi!</h3>
        <p class="text-xs text-slate-400 mt-1.5 animate-pulse">Mengirim data kehadiran masuk Anda ke sistem...</p>
    </div>

    <!-- Script Block for Alpine.js Component -->
    <script>
    function checkInApp() {
        return {
            stream: null,
            cameraActive: false,
            errorMessage: '',
            isAnalyzing: false,
            gpsWatcher: null,
            scanInterval: null,

            // Leaflet map objects
            mapInstance: null,
            userMarker: null,
            officeMarker: null,
            geofenceCircle: null,

            init() {
                if (this._initialized) return;
                this._initialized = true;
                
                if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                    this.errorMessage = 'Peringatan: Browser memblokir Kamera & GPS pada koneksi tidak aman (HTTP). Silakan gunakan HTTPS://' + window.location.host + window.location.pathname;
                    return;
                }
                this.$nextTick(() => {
                    this.initCamera();
                    this.initGPS();
                });
            },

            destroy() {
                this.stopCamera();
                this.stopGPS();
            },

            async initCamera() {
                try {
                    this.errorMessage = '';
                    const constraints = {
                        video: {
                            width: { ideal: 640 },
                            height: { ideal: 480 },
                            facingMode: 'user'
                        },
                        audio: false
                    };

                    try {
                        this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                    } catch (e) {
                        console.warn('Retrying camera access with fallback constraints:', e);
                        this.stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                    }

                    // Double check if ref is bound, wait if necessary
                    if (!this.$refs.video) {
                        await new Promise(resolve => setTimeout(resolve, 150));
                    }
                    
                    if (this.$refs.video) {
                        this.$refs.video.srcObject = this.stream;
                        this.cameraActive = true;
                        this.$refs.video.play().catch(e => console.log("Video play interrupted:", e));
                        this.startScanning();
                    } else {
                        throw new Error('Elemen video (x-ref="video") belum terikat di DOM.');
                    }
                } catch (err) {
                    console.error('Camera access failed:', err);
                    this.errorMessage = 'Gagal mengakses kamera: ' + err.message + '. Pastikan izin kamera diberikan dan gunakan koneksi HTTPS.';
                }
            },

            stopCamera() {
                this.stopScanning();
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                }
                this.cameraActive = false;
            },

            startScanning() {
                if (this.scanInterval) clearInterval(this.scanInterval);
                this.scanInterval = setInterval(() => {
                    if (this.cameraActive && !this.isAnalyzing && !this.$wire.faceValid && !this.$wire.isSubmitting) {
                        this.verifyFace();
                    } else if (this.$wire.faceValid || this.$wire.isSubmitting) {
                        this.stopScanning();
                    }
                }, 2000);
            },

            stopScanning() {
                if (this.scanInterval) {
                    clearInterval(this.scanInterval);
                    this.scanInterval = null;
                }
            },

            async verifyFace() {
                if (this.isAnalyzing || !this.cameraActive) return;
                this.isAnalyzing = true;
                this.errorMessage = '';
                
                try {
                    const video = this.$refs.video;
                    const canvas = document.createElement('canvas');
                    // Higher resolution capture for OpenCV face recognition accuracy
                    canvas.width = 640;
                    canvas.height = 480;
                    const ctx = canvas.getContext('2d');
                    
                    // Flip image horizontally on canvas to match user preview mirror
                    ctx.translate(canvas.width, 0);
                    ctx.scale(-1, 1);
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    const base64Data = canvas.toDataURL('image/jpeg', 0.90);
                    await this.$wire.compareLiveFace(base64Data);
                } catch (err) {
                    console.error('Face verification failed:', err);
                    this.errorMessage = 'Gagal memproses analisis wajah. Silakan coba lagi.';
                } finally {
                    this.isAnalyzing = false;
                }
            },

            initGPS() {
                if (!navigator.geolocation) {
                    this.errorMessage = 'Browser Anda tidak mendukung pelacakan lokasi GPS.';
                    return;
                }

                this.initMap();

                // 1. Initial quick getPosition to resolve 'Waiting' immediately
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        const accuracy = pos.coords.accuracy;
                        this.$wire.updateLocation(lat, lng, accuracy);
                        this.updateMapMarker(lat, lng);
                    },
                    (err) => {
                        console.log('Initial location resolution missed:', err.message);
                    },
                    {
                        enableHighAccuracy: false,
                        timeout: 6000,
                        maximumAge: 120000 // Allow up to 2 minutes cached position
                    }
                );

                // 2. Setup robust geolocation watcher with high-accuracy auto-fallback
                const gpsOptions = {
                    enableHighAccuracy: true,
                    timeout: 15000,   // 15s timeout
                    maximumAge: 3000   // 3s cached data allowance
                };

                const startWatcher = (options) => {
                    if (this.gpsWatcher) {
                        navigator.geolocation.clearWatch(this.gpsWatcher);
                    }
                    
                    this.gpsWatcher = navigator.geolocation.watchPosition(
                        (pos) => {
                            this.errorMessage = '';
                            const lat = pos.coords.latitude;
                            const lng = pos.coords.longitude;
                            const accuracy = pos.coords.accuracy;
                            this.$wire.updateLocation(lat, lng, accuracy);
                            this.updateMapMarker(lat, lng);
                        },
                        (err) => {
                            console.error('GPS watch error:', err);
                            
                            if (err.code === 3 && options.enableHighAccuracy) {
                                console.warn('High-accuracy GPS timeout. Attempting standard accuracy fallback...');
                                startWatcher({
                                    enableHighAccuracy: false,
                                    timeout: 12000,
                                    maximumAge: 8000
                                });
                            } else if (err.code === 1) {
                                this.errorMessage = 'Izin lokasi ditolak. Silakan aktifkan izin GPS di browser Anda.';
                            } else {
                                this.errorMessage = 'Gagal mendeteksi sinyal GPS. Posisikan perangkat Anda ke area terbuka.';
                            }
                        },
                        options
                    );
                };

                startWatcher(gpsOptions);
            },

            stopGPS() {
                if (this.gpsWatcher) {
                    navigator.geolocation.clearWatch(this.gpsWatcher);
                    this.gpsWatcher = null;
                }
            },

            initMap() {
                try {
                    const officeLat = {{ $branchLatitude }};
                    const officeLng = {{ $branchLongitude }};
                    const radiusMeters = {{ $maxRadius }};

                    this.mapInstance = L.map('leaflet-map', {
                        zoomControl: true,
                        attributionControl: true
                    }).setView([officeLat, officeLng], 17);

                    // Classic Light Street map design similar to standard Google Maps
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(this.mapInstance);

                    // Custom office branch red location pin
                    const officeIcon = L.divIcon({
                        className: 'custom-map-pin',
                        html: `<div class="w-8 h-8 rounded-full bg-rose-500/25 border border-rose-500 flex items-center justify-center text-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.4)] animate-pulse">
                                 <svg class="w-5.5 h-5.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                 </svg>
                               </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    });

                    this.officeMarker = L.marker([officeLat, officeLng], { icon: officeIcon }).addTo(this.mapInstance);

                    // Geofence perimeter styling (Clean blue border, standard light transparency)
                    this.geofenceCircle = L.circle([officeLat, officeLng], {
                        color: '#3b82f6',
                        fillColor: '#3b82f6',
                        fillOpacity: 0.12,
                        weight: 2,
                        radius: radiusMeters
                    }).addTo(this.mapInstance);

                } catch (e) {
                    console.error('Failed to initialize map:', e);
                }
            },

            updateMapMarker(lat, lng) {
                if (!this.mapInstance) return;

                const userCoords = [lat, lng];
                const officeCoords = [{{ $branchLatitude }}, {{ $branchLongitude }}];

                if (!this.userMarker) {
                    // Google Maps style blue pulsing location dot
                    const userIcon = L.divIcon({
                        className: 'custom-user-dot',
                        html: `<div class="relative flex items-center justify-center">
                                 <!-- Pulse outer ring -->
                                 <div class="absolute w-8 h-8 rounded-full bg-blue-500/35 animate-ping"></div>
                                 <!-- Solid inner blue dot with outline -->
                                 <div class="relative w-3.5 h-3.5 bg-blue-600 rounded-full border-2 border-white shadow-[0_0_8px_rgba(37,99,235,0.7)]"></div>
                               </div>`,
                        iconSize: [24, 24],
                        iconAnchor: [12, 12]
                    });

                    this.userMarker = L.marker(userCoords, { icon: userIcon }).addTo(this.mapInstance);
                } else {
                    this.userMarker.setLatLng(userCoords);
                }

                try {
                    const bounds = L.latLngBounds([userCoords, officeCoords]);
                    this.mapInstance.fitBounds(bounds, { padding: [50, 50] });
                } catch (e) {
                    this.mapInstance.setView(userCoords, 17);
                }
            }
        };
    }
    </script>
</div>
