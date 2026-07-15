<div class="py-6 min-h-screen" x-data="checkInApp()" x-init="init()" x-on:destroy="destroy()">

    <!-- Inject CSS for Face ID scanning neon animation -->
    <style>
        @keyframes scan {
            0%, 100% { transform: translateY(0); opacity: 0.8; }
            50% { transform: translateY(280px); opacity: 1; }
        }
        .animate-scan {
            animation: scan 3s infinite ease-in-out;
        }
    </style>

    <!-- Inject Leaflet Assets directly to avoid bundle overhead -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="btn-secondary btn-sm mb-3 inline-flex items-center gap-2 active:scale-95 transition-transform">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dasbor
            </a>
            <h1 class="heading-1 flex items-center gap-2.5">
                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                Presensi Masuk Live
            </h1>
            <p class="label-sm mt-1">Posisikan wajah Anda pada lingkaran kamera dan pastikan berada di area radius kantor.</p>
        </div>

        {{-- Flash / runtime errors --}}
        @if (session()->has('error'))
            <div class="mb-4 flex items-center gap-2 rounded-xl border border-danger/30 bg-danger-soft p-3 text-sm font-medium text-danger">
                <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div x-show="errorMessage" x-cloak class="mb-4 flex items-center gap-2 rounded-xl border border-danger/30 bg-danger-soft p-3 text-sm font-medium text-danger">
            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <span x-text="errorMessage"></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left Column (Desktop: Map, Geofence Details; Mobile: Second position) --}}
            <div class="lg:col-span-5 order-2 lg:order-1 space-y-6">
                
                {{-- Map card --}}
                <div wire:ignore class="card overflow-hidden shadow-md">
                    <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
                        <span class="label-xs uppercase tracking-wide text-primary flex items-center gap-2">
                            <svg class="h-4 w-4 text-info" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            Peta Lokasi Kantor
                        </span>
                        <span class="badge-success">GPS Aktif</span>
                    </div>
                    <div id="leaflet-map" wire:ignore class="h-60 w-full relative z-0"></div>
                </div>

                {{-- Location telemetry details --}}
                <div class="card p-5 space-y-4 shadow-md">
                    <div class="flex items-center justify-between border-b border-border pb-2.5">
                        <span class="label-xs uppercase tracking-wide text-primary font-bold">Detail Geofence & Koordinat</span>
                        <span class="label-xs font-mono text-fg-muted">Shift: Pagi</span>
                    </div>

                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-medium text-fg flex items-center gap-2">
                                <svg class="h-4 w-4 text-info" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Area Kantor
                            </span>
                            <span x-show="!$wire.latitude" class="badge-neutral">Melacak…</span>
                            <span x-show="$wire.latitude && $wire.locationValid" x-cloak class="badge-success">Dalam radius</span>
                            <span x-show="$wire.latitude && !$wire.locationValid" x-cloak class="badge-danger">Di luar radius</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="label-xs uppercase tracking-wide block text-fg-muted">Jarak Anda</span>
                                <span x-show="$wire.latitude" x-cloak class="font-mono font-bold text-fg text-sm" x-text="$wire.distanceFromBranch + ' meter'"></span>
                                <span x-show="!$wire.latitude" class="font-mono font-medium text-fg text-sm">Mencari GPS…</span>
                            </div>
                            <div>
                                <span class="label-xs uppercase tracking-wide block text-fg-muted">Batas Maksimal</span>
                                <span class="font-mono font-bold text-fg text-sm" x-text="$wire.maxRadius + ' meter'"></span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 rounded-lg border border-border bg-surface-muted px-3 py-2 text-xs text-fg-muted">
                            <span class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full" :class="$wire.locationValid ? 'bg-success' : 'bg-danger'"></span>
                            <p x-text="$wire.locationMessage"></p>
                        </div>

                        <div x-show="$wire.resolvedAddress" x-cloak class="flex items-start gap-2 rounded-lg border border-info/20 bg-info-soft px-3 py-2 text-xs text-fg-muted">
                            <svg class="h-3.5 w-3.5 flex-shrink-0 mt-0.5 text-info" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <div>
                                <span class="block text-xs font-semibold text-info mb-0.5">Alamat GPS Terdeteksi</span>
                                <p class="text-[11px] leading-relaxed text-fg" x-text="$wire.resolvedAddress"></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column (Desktop: Camera, Verification, Submit Button; Mobile: First position at the very top!) --}}
            <div class="lg:col-span-7 order-1 lg:order-2 space-y-6">
                
                {{-- Camera card --}}
                <div class="card overflow-hidden shadow-md">
                    <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
                        <span class="label-xs uppercase tracking-wide text-primary flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-ping"></span>
                            Pemindai Wajah AI Aktif
                        </span>
                        <span class="badge-success">Live Scan</span>
                    </div>

                    {{-- Main Viewport --}}
                    <div class="p-6 bg-surface flex flex-col items-center">
                        
                        {{-- Circular Camera Container --}}
                        <div class="relative w-48 sm:w-56 rounded-2xl border-4 border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden bg-slate-950" style="aspect-ratio: 3/4;">
                            
                            {{-- Scanning Neon Line --}}
                            <div x-show="cameraActive && !isAnalyzing && !$wire.faceValid" x-cloak class="absolute inset-x-0 h-1 z-20 bg-gradient-to-r from-transparent via-primary to-transparent animate-scan shadow-[0_0_8px_rgba(59,130,246,0.8)]"></div>

                            {{-- Live Video --}}
                            <video x-ref="video" autoplay playsinline muted class="absolute inset-0 w-full h-full object-cover transform -scale-x-100" :class="cameraActive ? '' : 'hidden'"></video>

                            {{-- Camera Off State --}}
                            <div x-show="!cameraActive" class="absolute inset-0 flex flex-col items-center justify-center text-center p-4 space-y-2.5 z-10">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/5 border border-white/10 text-slate-400">
                                    <svg class="h-5.5 w-5.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-white">Mengaktifkan kamera…</h4>
                                    <p class="text-[10px] text-slate-400 max-w-[180px] mx-auto mt-0.5">Izinkan akses kamera untuk verifikasi wajah.</p>
                                </div>
                            </div>

                            {{-- Face Mask Guide --}}
                            <div x-show="cameraActive && !isAnalyzing && !$wire.faceValid" x-cloak class="absolute inset-4 rounded-xl border-2 border-dashed border-white/30 pointer-events-none z-10 animate-pulse"></div>

                            {{-- AI Analisis Overlay --}}
                            <div x-show="isAnalyzing" x-cloak class="absolute inset-0 flex items-center justify-center bg-black/60 z-25 pointer-events-none">
                                <div class="flex flex-col items-center gap-2 text-white">
                                    <svg class="h-7 w-7 animate-spin text-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <span class="text-[11px] font-semibold tracking-wider uppercase">Menganalisis wajah…</span>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Status Badges --}}
                        <div class="flex items-center gap-3 mt-5">
                            <!-- GPS Status Badge -->
                            <div class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border text-[11px] font-bold shadow-sm"
                                :class="$wire.locationValid ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-500/20'">
                                <template x-if="$wire.locationValid">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </template>
                                <template x-if="!$wire.locationValid">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                </template>
                                GPS: <span x-text="$wire.locationValid ? 'OK' : 'Luar Area'"></span>
                            </div>

                            <!-- Face Match Status Badge -->
                            <div class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-full border text-[11px] font-bold shadow-sm"
                                :class="$wire.faceValid ? 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-500/20' : 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-950/20 dark:text-rose-400 dark:border-rose-500/20'">
                                <template x-if="$wire.faceValid">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                </template>
                                <template x-if="!$wire.faceValid">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                                </template>
                                Wajah: <span x-text="$wire.faceValid ? 'Cocok' : 'Memindai…'"></span>
                            </div>
                        </div>

                        {{-- Face similarity bar --}}
                        <div class="w-full max-w-sm mt-5 space-y-1.5 bg-surface-muted p-3.5 rounded-xl border border-border">
                            <div class="flex justify-between items-center text-[11px] font-semibold">
                                <span class="text-fg-subtle uppercase tracking-wider">Akurasi Kemiripan Wajah</span>
                                <span class="font-mono text-sm font-bold" :class="$wire.faceValid ? 'text-success' : 'text-danger'" x-text="$wire.faceSimilarity + '%'"></span>
                            </div>
                            <div class="w-full bg-surface rounded-full h-2 overflow-hidden border border-border">
                                <div class="h-full rounded-full transition-all duration-300" :class="$wire.faceValid ? 'bg-success' : 'bg-danger'" :style="'width: ' + $wire.faceSimilarity + '%'"></div>
                            </div>
                            <p class="text-[10px] text-fg-muted mt-1 leading-relaxed" x-text="cameraActive && !$wire.selfieData ? 'Posisikan wajah Anda dengan jelas di depan kamera.' : $wire.faceStatusMessage"></p>
                        </div>

                        {{-- Auto-detect processing message --}}
                        <div x-show="$wire.locationValid && $wire.faceValid" x-cloak class="w-full max-w-sm mt-4 flex items-center justify-center gap-2 rounded-xl border border-success/30 bg-success-soft p-3 text-xs font-semibold text-success shadow-sm">
                            <svg class="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <span>AI &amp; GPS Valid. Mengirim otomatis…</span>
                        </div>

                        {{-- Big Submit / Action Button --}}
                        <div class="w-full max-w-sm mt-5">
                            <button x-show="$wire.locationValid && $wire.faceValid" x-cloak wire:click="submit" type="button" 
                                class="w-full flex items-center justify-center gap-2.5 rounded-2xl py-4 text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 active:scale-95 transition-all shadow-lg shadow-emerald-500/20 cursor-pointer">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                KIRIM ABSEN MASUK
                            </button>
                            <button x-show="!($wire.locationValid && $wire.faceValid)" disabled type="button" 
                                class="w-full flex items-center justify-center gap-2.5 rounded-2xl py-4 text-xs font-bold text-fg-muted bg-surface-muted border border-border cursor-not-allowed">
                                <svg class="h-4 w-4 animate-spin text-fg-muted" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                MENUNGGU VALIDASI GPS &amp; WAJAH
                            </button>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Processing overlay --}}
    <div x-show="$wire.isSubmitting" x-cloak class="modal-overlay flex flex-col items-center justify-center z-50 p-4">
        <!-- Captured selfie preview -->
        <template x-if="$wire.selfieData">
            <div class="mb-5 relative w-44 h-44 rounded-full overflow-hidden border-4 border-emerald-500 shadow-lg shadow-emerald-500/25">
                <img :src="$wire.selfieData" class="w-full h-full object-cover transform -scale-x-100">
                <div class="absolute inset-0 bg-emerald-500/10 flex items-center justify-center pointer-events-none">
                    <svg class="h-12 w-12 text-emerald-400 drop-shadow-[0_2px_8px_rgba(16,185,129,0.5)] animate-pulse" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </template>
        
        <div class="relative mb-4 h-12 w-12">
            <div class="absolute inset-0 rounded-full border-4 border-t-emerald-500 border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
        </div>
        <h3 class="text-lg font-bold text-white">Presensi Berhasil Terverifikasi!</h3>
        <p class="text-sm text-slate-300 mt-1.5 text-center max-w-xs">Mengirim data kehadiran masuk Anda ke sistem…</p>
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
                            width: { ideal: 480 },
                            height: { ideal: 640 },
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
                    // Portrait resolution capture for OpenCV face recognition accuracy
                    canvas.width = 480;
                    canvas.height = 640;
                    const ctx = canvas.getContext('2d');

                    // Flip image horizontally on canvas to match user preview mirror
                    ctx.translate(canvas.width, 0);
                    ctx.scale(-1, 1);
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Reset transform so overlay text is not mirrored
                    ctx.setTransform(1, 0, 0, 1, 0, 0);

                    // Clean frame for face recognition (un-watermarked, best accuracy)
                    const cleanData = canvas.toDataURL('image/jpeg', 0.90);

                    // Stamped frame for the stored audit selfie (location + timestamp watermark)
                    this.drawAttendanceWatermark(ctx, canvas.width, canvas.height);
                    const stampedData = canvas.toDataURL('image/jpeg', 0.90);

                    await this.$wire.compareLiveFace(cleanData, stampedData);
                } catch (err) {
                    console.error('Face verification failed:', err);
                    this.errorMessage = 'Gagal memproses analisis wajah. Silakan coba lagi.';
                } finally {
                    this.isAnalyzing = false;
                }
            },

            // Stamps a GPS-camera style watermark (date/time + coordinates + address) onto the bottom of the canvas.
            drawAttendanceWatermark(ctx, w, h) {
                const pad = n => String(n).padStart(2, '0');
                const now = new Date();
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const dateStr = days[now.getDay()] + ', ' + pad(now.getDate()) + '/' + pad(now.getMonth() + 1) + '/' + now.getFullYear()
                    + '  ' + pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());

                const lat = this.$wire.latitude;
                const lng = this.$wire.longitude;
                const acc = this.$wire.accuracy;
                const coordStr = (lat && lng)
                    ? 'Lat ' + Number(lat).toFixed(6) + ', Lng ' + Number(lng).toFixed(6) + ' (Akurasi: ' + Math.round(acc) + 'm)'
                    : 'Lokasi GPS tidak tersedia';
                
                const dist = this.$wire.distanceFromBranch;
                const maxR = this.$wire.maxRadius;
                const distStr = (dist || dist === 0)
                    ? 'Jarak dari kantor: ' + dist + ' m (Maks: ' + maxR + ' m)'
                    : '';

                const addr = this.$wire.resolvedAddress;
                const addrStr = addr ? 'Alamat: ' + addr : '';

                // Calculate band height dynamically
                let lineCount = 2; // Date + Coordinates
                if (distStr) lineCount++;
                if (addrStr) lineCount += 2; // Give address 2 lines for wrapping

                const bandH = 20 + (lineCount * 18);
                const x = 12;
                let y = h - bandH + 15;

                // Semi-transparent dark background band
                ctx.save();
                ctx.fillStyle = 'rgba(0, 0, 0, 0.65)';
                ctx.fillRect(0, h - bandH, w, bandH);

                ctx.textBaseline = 'middle';
                ctx.shadowColor = 'rgba(0,0,0,0.9)';
                ctx.shadowBlur = 3;

                // Draw Date & Time
                ctx.fillStyle = '#ffffff';
                ctx.font = 'bold 15px Arial, sans-serif';
                ctx.fillText('🕒 ' + dateStr, x, y);
                y += 18;

                // Draw Coordinates
                ctx.font = '12px "Courier New", monospace';
                ctx.fillStyle = '#f8fafc';
                ctx.fillText('📍 ' + coordStr, x, y);
                y += 18;

                // Draw Distance
                if (distStr) {
                    ctx.fillStyle = '#cbd5e1';
                    ctx.fillText('🏢 ' + distStr, x, y);
                    y += 18;
                }

                // Draw Address with simple wrapping
                if (addrStr) {
                    ctx.fillStyle = '#94a3b8';
                    const maxW = w - 24;
                    // Simple word wrap
                    const words = addrStr.split(' ');
                    let line = '';
                    let lines = [];
                    ctx.font = '11px Arial, sans-serif';
                    
                    for (let n = 0; n < words.length; n++) {
                        let testLine = line + words[n] + ' ';
                        let metrics = ctx.measureText(testLine);
                        if (metrics.width > maxW && n > 0) {
                            lines.push(line);
                            line = words[n] + ' ';
                        } else {
                            line = testLine;
                        }
                    }
                    lines.push(line);
                    
                    // Draw maximum 2 lines of address to avoid drawing off the image
                    for (let i = 0; i < Math.min(lines.length, 2); i++) {
                        ctx.fillText(lines[i], x, y);
                        y += 15;
                    }
                }
                ctx.restore();
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
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 30000 // Max 30s cached position for initial fix
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

                    // Google Maps Road tile layer
                    L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                        maxZoom: 20,
                        subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                        attribution: '© Google Maps'
                    }).addTo(this.mapInstance);

                    // Custom office branch red location pin
                    const officeIcon = L.divIcon({
                        className: 'custom-map-pin',
                        html: `<div class="w-8 h-8 rounded-full bg-danger-soft border border-danger flex items-center justify-center text-danger">
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
                                 <div class="absolute w-8 h-8 rounded-full bg-primary/30 animate-ping"></div>
                                 <!-- Solid inner blue dot with outline -->
                                 <div class="relative w-3.5 h-3.5 bg-primary rounded-full border-2 border-white"></div>
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
