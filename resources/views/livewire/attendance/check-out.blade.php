<div class="py-6 min-h-screen" x-data="checkOutApp()" x-init="init()" x-on:destroy="destroy()">

    <!-- Inject Leaflet Assets directly to avoid bundle overhead -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="btn-secondary btn-sm mb-3">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Dasbor
            </a>
            <h1 class="heading-1 flex items-center gap-2.5">
                <span class="h-2.5 w-2.5 rounded-full bg-info"></span>
                Presensi Keluar Live
            </h1>
            <p class="label-sm mt-1">Dekatkan wajah dan pastikan Anda berada di dalam area kantor untuk melakukan absen keluar.</p>
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

            {{-- Telemetry column --}}
            <div class="lg:col-span-5 order-1 lg:order-2 space-y-6">
                <div class="card p-5 space-y-5">
                    <div class="flex items-center justify-between border-b border-border pb-2.5">
                        <span class="label-xs uppercase tracking-wide text-primary">Matrix Validasi Kehadiran</span>
                        <span class="label-xs font-mono">Absen Keluar</span>
                    </div>

                    {{-- Auto-detect alert --}}
                    <div x-show="$wire.locationValid && $wire.faceValid" x-cloak class="flex items-center justify-center gap-2 rounded-xl border border-success/30 bg-success-soft p-3 text-sm font-medium text-success">
                        <svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                        <span>Parameter terpenuhi. Mengirim otomatis…</span>
                    </div>

                    {{-- Location telemetry --}}
                    <div class="rounded-xl border border-border bg-surface-muted p-4 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-fg flex items-center gap-2">
                                <svg class="h-4 w-4 text-info" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Geofence Lokasi Kantor
                            </span>
                            <span x-show="!$wire.latitude" class="badge-neutral">Melacak…</span>
                            <span x-show="$wire.latitude && $wire.locationValid" x-cloak class="badge-success">Dalam radius</span>
                            <span x-show="$wire.latitude && !$wire.locationValid" x-cloak class="badge-danger">Di luar radius</span>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="label-xs uppercase tracking-wide block">Jarak Anda</span>
                                <span x-show="$wire.latitude" x-cloak class="font-mono font-medium text-fg text-sm" x-text="$wire.distanceFromBranch + ' meter'"></span>
                                <span x-show="!$wire.latitude" class="font-mono font-medium text-fg text-sm">Mencari GPS…</span>
                            </div>
                            <div>
                                <span class="label-xs uppercase tracking-wide block">Radius Batas</span>
                                <span class="font-mono font-medium text-fg text-sm" x-text="$wire.maxRadius + ' meter'"></span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 rounded-lg border border-border bg-surface px-3 py-2 text-xs text-fg-muted">
                            <span class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full" :class="$wire.locationValid ? 'bg-success' : 'bg-danger'"></span>
                            <p x-text="$wire.locationMessage"></p>
                        </div>

                        <div x-show="$wire.resolvedAddress" x-cloak class="flex items-start gap-2 rounded-lg border border-info/20 bg-info-soft px-3 py-2 text-xs text-fg-muted">
                            <svg class="h-3.5 w-3.5 flex-shrink-0 mt-0.5 text-info" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <div>
                                <span class="block text-xs font-medium text-info mb-0.5">Alamat Terdeteksi</span>
                                <p x-text="$wire.resolvedAddress"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Biometric telemetry --}}
                    <div class="rounded-xl border border-border bg-surface-muted p-4 space-y-3.5">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-fg flex items-center gap-2">
                                <svg class="h-4 w-4 text-info" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Verifikasi Wajah
                            </span>
                            <span x-show="!$wire.selfieData && !cameraActive" class="badge-neutral">Menunggu…</span>
                            <span x-show="!$wire.selfieData && cameraActive" x-cloak class="badge-info">Kamera aktif</span>
                            <span x-show="$wire.selfieData && $wire.faceValid" x-cloak class="badge-success">Wajah cocok</span>
                            <span x-show="$wire.selfieData && !$wire.faceValid" x-cloak class="badge-danger">Tidak cocok</span>
                        </div>

                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center text-xs">
                                <span class="label-xs uppercase tracking-wide">Tingkat Kemiripan</span>
                                <span class="font-mono font-semibold" :class="$wire.faceValid ? 'text-success' : 'text-danger'" x-text="$wire.faceSimilarity + '%'"></span>
                            </div>
                            <div class="w-full bg-surface rounded-full h-1.5 overflow-hidden border border-border">
                                <div class="h-full rounded-full transition-all duration-500" :class="$wire.faceValid ? 'bg-success' : 'bg-danger'" :style="'width: ' + $wire.faceSimilarity + '%'"></div>
                            </div>
                        </div>

                        <div class="flex items-start gap-2 rounded-lg border border-border bg-surface px-3 py-2 text-xs text-fg-muted">
                            <span class="mt-1.5 h-1.5 w-1.5 flex-shrink-0 rounded-full" :class="$wire.faceValid ? 'bg-success' : ($wire.selfieData ? 'bg-danger' : 'bg-fg-subtle')"></span>
                            <p x-text="cameraActive && !$wire.selfieData ? 'Kamera aktif. Sistem memindai wajah secara otomatis…' : $wire.faceStatusMessage"></p>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="pt-3 border-t border-border flex justify-center">
                        <button x-show="$wire.locationValid && $wire.faceValid" x-cloak wire:click="submit" type="button" class="btn-primary w-full">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Kirim Kehadiran Keluar
                        </button>
                        <button x-show="!($wire.locationValid && $wire.faceValid)" disabled type="button" class="btn-disabled w-full">
                            <svg class="h-3.5 w-3.5 animate-spin" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Menunggu validasi AI &amp; GPS</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Camera & map column --}}
            <div class="lg:col-span-7 order-2 lg:order-1 space-y-6">
                {{-- Camera card --}}
                <div class="card overflow-hidden">
                    <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
                        <span class="label-xs uppercase tracking-wide text-primary flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-info animate-ping"></span>
                            Pemindai Verifikasi Wajah Aktif
                        </span>
                        <span class="badge-info">Live</span>
                    </div>

                    {{-- Camera viewport (intentionally dark in both themes) --}}
                    <div class="relative bg-slate-950 aspect-video flex items-center justify-center overflow-hidden">
                        <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover transform -scale-x-100" :class="cameraActive ? '' : 'hidden'"></video>

                        <div x-show="!cameraActive" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 space-y-3 z-10">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/5 border border-white/10 text-slate-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-white">Mengaktifkan kamera…</h4>
                                <p class="text-xs text-slate-400 max-w-xs mt-1">Berikan akses izin kamera agar sistem dapat memverifikasi wajah Anda.</p>
                            </div>
                        </div>

                        {{-- Face guide overlay --}}
                        <div x-show="cameraActive && !isAnalyzing" x-cloak class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-10">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-white/25">
                                <div class="h-2 w-2 rounded-full bg-white/50"></div>
                            </div>
                            <div class="absolute bottom-4 rounded-full border border-white/15 bg-black/50 px-3.5 py-1.5 text-xs font-medium uppercase tracking-wider text-white">
                                Posisikan wajah di sini
                            </div>
                        </div>

                        <div x-show="cameraActive && !isAnalyzing" x-cloak class="absolute inset-4 rounded-xl border-2 border-white/25 pointer-events-none z-10">
                            <div class="absolute top-0 left-0 h-5 w-5 border-t-2 border-l-2 border-white/70 rounded-tl"></div>
                            <div class="absolute top-0 right-0 h-5 w-5 border-t-2 border-r-2 border-white/70 rounded-tr"></div>
                            <div class="absolute bottom-0 left-0 h-5 w-5 border-b-2 border-l-2 border-white/70 rounded-bl"></div>
                            <div class="absolute bottom-0 right-0 h-5 w-5 border-b-2 border-r-2 border-white/70 rounded-br"></div>
                        </div>

                        <div x-show="isAnalyzing" x-cloak class="absolute inset-0 flex items-start justify-end p-3 z-20 pointer-events-none">
                            <div class="flex items-center gap-1.5 rounded-md border border-white/15 bg-black/50 px-2.5 py-1 text-xs font-medium tracking-wider text-white">
                                <svg class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Analisis…
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-surface-muted border-t border-border flex flex-col sm:flex-row items-center justify-between gap-3">
                        <div class="text-xs text-fg-muted text-center sm:text-left">
                            <span class="block text-sm font-medium text-fg mb-0.5">Petunjuk wajah</span>
                            Pastikan pencahayaan cukup dan wajah terlihat jelas tanpa aksesoris berlebih.
                        </div>
                        <div x-show="cameraActive" x-cloak class="w-full sm:w-auto">
                            <div class="flex items-center justify-center gap-2 rounded-xl border border-info/30 bg-info-soft px-5 py-2.5 text-sm font-medium text-info select-none">
                                <span class="relative flex h-2 w-2">
                                    <span :class="isAnalyzing ? 'animate-ping' : ''" class="absolute inline-flex h-full w-full rounded-full bg-info opacity-75"></span>
                                    <span class="relative inline-flex h-2 w-2 rounded-full bg-info"></span>
                                </span>
                                <span x-text="isAnalyzing ? 'Menganalisis wajah…' : 'Mendeteksi wajah otomatis…'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Map card --}}
                <div wire:ignore class="card overflow-hidden">
                    <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
                        <span class="label-xs uppercase tracking-wide text-primary flex items-center gap-2">
                            <svg class="h-4 w-4 text-info" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                            Peta Lokasi Presensi
                        </span>
                        <span class="badge-success">GPS aktif</span>
                    </div>
                    <div id="leaflet-map" wire:ignore class="h-60 w-full relative z-0"></div>
                </div>
            </div>

        </div>
    </div>

    {{-- Processing overlay --}}
    <div x-show="$wire.isSubmitting" x-cloak class="modal-overlay flex flex-col items-center justify-center z-50">
        <div class="relative mb-6 h-20 w-20">
            <div class="absolute inset-0 rounded-full border-4 border-t-success border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
            <div class="absolute inset-5 rounded-full bg-success animate-pulse"></div>
        </div>
        <h3 class="text-lg font-semibold text-white">Presensi berhasil terdeteksi!</h3>
        <p class="text-sm text-slate-300 mt-1.5">Mengirim data kehadiran keluar Anda ke sistem…</p>
    </div>

    <!-- Script Block for Alpine.js Component -->
    <script>
    function checkOutApp() {
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
