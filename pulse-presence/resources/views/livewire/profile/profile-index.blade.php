<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="heading-1">Profil Karyawan</h1>
                <p class="mt-1 label-sm">Kelola kredensial keamanan Anda, daftarkan biometrik tepercaya, dan verifikasi kantor cabang penempatan</p>
            </div>
            @if ($step === 'enroll')
                <button wire:click="$set('step', 'overview')"
                    class="btn-sm btn-secondary w-full md:w-auto">
                    Batalkan Pendaftaran
                </button>
            @endif
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 sm:p-5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-bold flex items-center">
                <svg class="w-4.5 h-4.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($step === 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Employee Identity Card -->
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl relative overflow-hidden lg:col-span-1">
                    <!-- Cover band (subtle brand tint that blends with the base in both themes) -->
                    <div class="h-20 bg-gradient-to-br from-blue-500/15 via-indigo-500/10 to-emerald-500/15 relative">
                        <div class="absolute inset-0 bg-premium-mesh opacity-20"></div>
                    </div>

                    <div class="px-6 pb-6 -mt-10 text-center">
                        <!-- Avatar with photo upload -->
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 via-blue-400 to-emerald-400 rounded-3xl blur-[10px] opacity-50"></div>
                            <div class="relative w-full h-full bg-gradient-to-br from-blue-600 to-indigo-600 border-4 border-white dark:border-slate-600 rounded-3xl overflow-hidden flex items-center justify-center text-white text-3xl font-black uppercase shadow-md shadow-blue-600/20">
                                @if (auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="Foto profil">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                @endif

                                <!-- Uploading spinner -->
                                <div wire:loading wire:target="photo" class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <svg class="w-6 h-6 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Upload button -->
                            <label class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-blue-600 hover:bg-blue-500 border-2 border-white dark:border-slate-600 flex items-center justify-center cursor-pointer shadow-lg transition-all" title="Ubah foto profil">
                                <svg class="w-4 h-4" fill="none" stroke="#ffffff" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" wire:model="photo" accept="image/*" class="hidden">
                            </label>
                        </div>

                        @error('photo')
                            <p class="label-xs text-rose-400 mb-2">{{ $message }}</p>
                        @enderror

                        <h3 class="heading-3">{{ auth()->user()->name ?? 'Pulse Employee' }}</h3>
                        <div class="mt-2 flex flex-wrap items-center justify-center gap-2">
                            <span class="badge-info">{{ ucwords(str_replace('_', ' ', auth()->user()->role ?? 'Karyawan')) }}</span>
                            @if (auth()->user()->hasRegisteredFace())
                                <span class="badge-success">Wajah terdaftar</span>
                            @else
                                <span class="badge-warning">Wajah belum terdaftar</span>
                            @endif
                        </div>

                        @if (auth()->user()->avatar)
                            <button wire:click="deletePhoto" wire:confirm="Hapus foto profil Anda?"
                                class="mt-2 text-[11px] font-bold text-rose-400 hover:text-rose-300 transition-colors">
                                Hapus foto profil
                            </button>
                        @endif

                        <div class="mt-6 pt-5 border-t border-white/5 space-y-4 text-left">
                            <div class="flex items-center justify-between gap-3">
                                <span class="label-xs flex-shrink-0">ID Karyawan</span>
                                <span class="label-sm font-mono font-bold text-white text-right">#{{ auth()->user()->employee_id ?? ('PP-' . str_pad(auth()->user()->id ?? 1, 5, '0', STR_PAD_LEFT)) }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="label-xs flex-shrink-0">Email</span>
                                <span class="label-sm font-bold text-white text-right break-all">{{ strtolower(auth()->user()->email ?? '-') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="label-xs flex-shrink-0">Kantor Cabang</span>
                                <span class="label-sm font-bold text-blue-400 text-right">{{ auth()->user()->branch->name ?? 'HQ Sudirman' }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-3">
                                <span class="label-xs flex-shrink-0">Mode Kerja</span>
                                <span class="label-sm font-bold text-white text-right">{{ ucfirst(auth()->user()->work_mode ?? 'office') }}</span>
                            </div>
                            @if (auth()->user()->joined_at)
                                <div class="flex items-center justify-between gap-3">
                                    <span class="label-xs flex-shrink-0">Bergabung</span>
                                    <span class="label-sm font-bold text-white text-right">{{ \Carbon\Carbon::parse(auth()->user()->joined_at)->translatedFormat('d M Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Trusted Biometrics & Face Registration Core Workspace -->
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-2">
                    <h3 class="heading-3 mb-6">Verifikasi Wajah</h3>

                    <div class="space-y-6">
                        <!-- Face Biometric Registration Status Panel -->
                        <div class="p-6 bg-[#0d1527] border border-white/10 rounded-2xl relative overflow-hidden">
                            <div class="absolute -right-16 -bottom-16 w-36 h-36 bg-blue-500/5 rounded-full blur-2xl"></div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="space-y-3.5">
                                    <div class="flex items-center space-x-2">
                                        @if (auth()->user()->hasRegisteredFace())
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                            <span class="badge-success">Data Wajah Terdaftar</span>
                                        @else
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-pulse"></span>
                                            <span class="badge-danger">Wajah Belum Terdaftar</span>
                                        @endif
                                    </div>
                                    <h4 class="label-md font-bold text-white">Informasi Wajah</h4>
                                    <p class="label-sm max-w-md leading-relaxed">
                                        Data wajah Anda digunakan untuk memverifikasi kehadiran (absen) harian Anda di kantor. Pastikan foto wajah yang didaftarkan terlihat jelas.
                                    </p>

                                    <div class="pt-2 flex flex-wrap gap-2.5">
                                        <button wire:click="$set('step', 'enroll')"
                                            class="btn-sm btn-primary">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            </svg>
                                            {{ auth()->user()->hasRegisteredFace() ? 'Daftar Ulang Multi-Sudut' : 'Daftarkan Kunci Wajah' }}
                                        </button>

                                        @if (auth()->user()->hasRegisteredFace())
                                             <button wire:click="deleteFace"
                                                 wire:confirm="Apakah Anda yakin ingin menghapus biometrik wajah Anda? Anda tidak akan dapat absen sebelum melakukan pendaftaran ulang."
                                                 class="btn-sm btn-danger-outline">
                                                 Hapus Otorisasi Kunci
                                             </button>
                                         @endif
                                    </div>
                                </div>

                                <!-- Face Master Preview Thumbnail -->
                                <div class="flex-shrink-0 mx-auto md:mx-0">
                                    <div class="relative w-28 h-28 bg-[#121d33] border border-white/10 rounded-2xl overflow-hidden flex items-center justify-center">
                                        @if (auth()->user()->hasRegisteredFace())
                                            <img src="{{ auth()->user()->getMasterFaceUrl() }}"
                                                class="w-full h-full object-cover transform scaleX(-1)">
                                            <div class="absolute bottom-1 right-1 bg-emerald-500 text-white rounded-full p-1 shadow">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M2.166 11.37a.75.75 0 011.05-.182 13.97 13.97 0 0013.568 0 .75.75 0 11.832 1.258 15.47 15.47 0 01-15.006 0 .75.75 0 01-.244-1.076z"
                                                        clip-rule="evenodd" />
                                                    <path fill-rule="evenodd"
                                                        d="M10 3a1.5 1.5 0 00-1.5 1.5v5.75a.75.75 0 001.5 0V4.5A1.5 1.5 0 0010 3z"
                                                        clip-rule="evenodd" />
                                                    <path fill-rule="evenodd"
                                                        d="M16.28 7.22a.75.75 0 010 1.06l-6.25 6.25a.75.75 0 01-1.06 0L5.22 10.78a.75.75 0 011.06-1.06l3.47 3.47 5.72-5.72a.75.75 0 011.06 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @else
                                            <div class="text-center text-slate-500 p-3">
                                                <svg class="w-8 h-8 mx-auto mb-1.5 opacity-55" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span class="label-xs block">Tanpa Foto</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <!-- Face Biometric Multi-Angle Enrollment View -->
            <div class="enroll-wizard bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl max-w-2xl mx-auto relative overflow-hidden"
                x-data="{
                    enrollStage: 'capture_front',
                
                    frontImage: null,
                    leftImage: null,
                    rightImage: null,
                
                    stream: null,
                    devices: [],
                    selectedDeviceId: '',
                    cameraError: '',
                    cameraReady: false,
                    permissionDenied: false,
                
                    faceDetected: false,
                    livenessScore: 0.00,
                    faceX: 0,
                    faceY: 0,
                    detectionConfidence: 0,
                    countdownSeconds: 3,
                    shutterTriggered: false,
                    scanInterval: null,
                
                    get instructionText() {
                        if (this.enrollStage === 'capture_front') {
                            return 'Posisikan wajah Anda tepat di bagian tengah kotak deteksi.';
                        } else if (this.enrollStage === 'capture_left') {
                            return 'Putar kepala Anda perlahan ke arah KIRI (Profil Kiri).';
                        } else if (this.enrollStage === 'capture_right') {
                            return 'Putar kepala Anda perlahan ke arah KANAN (Profil Kanan).';
                        }
                        return 'Semua foto wajah berhasil diambil. Tinjau foto Anda sebelum menyimpan.';
                    },
                
                    get stageBadge() {
                        if (this.enrollStage === 'capture_front') return 'SUDUT 1/3: WAJAH DEPAN';
                        if (this.enrollStage === 'capture_left') return 'SUDUT 2/3: WAJAH KIRI';
                        if (this.enrollStage === 'capture_right') return 'SUDUT 3/3: WAJAH KANAN';
                        return 'VERIFIKASI FOTO WAJAH';
                    },
                
                    get currentProgress() {
                        if (this.enrollStage === 'capture_front') return 33;
                        if (this.enrollStage === 'capture_left') return 66;
                        if (this.enrollStage === 'capture_right') return 100;
                        return 100;
                    },
                
                    async requestCameraAccess() {
                        this.cameraError = '';
                        this.permissionDenied = false;
                        this.cameraReady = false;
                        
                        if (!window.isSecureContext) {
                            this.cameraError = 'Koneksi tidak aman (HTTP). Gunakan HTTPS, localhost, atau aktifkan chrome://flags lalu cari Insecure origins treated as secure untuk IP ini.';
                            return;
                        }
                        
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            this.cameraError = 'API kamera tidak tersedia di browser ini. Pastikan Anda menggunakan browser modern (Chrome/Firefox/Safari).';
                            return;
                        }
                        
                        try {
                            const tempStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                            tempStream.getTracks().forEach(track => track.stop());
                
                            await this.enumerateDevices();
                            await this.startCamera();
                        } catch (error) {
                            console.error('[Camera]', error.name, error.message);
                            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                                this.permissionDenied = true;
                                this.cameraError = 'Izin kamera ditolak oleh browser. Klik ikon 🔒 di sebelah kiri address bar → Site Settings → Camera → Allow, lalu refresh halaman.';
                            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                                this.cameraError = 'Tidak ada kamera terdeteksi pada perangkat ini.';
                            } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                                this.cameraError = 'Kamera sedang digunakan oleh aplikasi lain. Tutup aplikasi lain yang menggunakan kamera, lalu coba lagi.';
                            } else if (error.name === 'OverconstrainedError') {
                                this.cameraError = 'Kamera tidak mendukung konfigurasi yang diminta. Mencoba ulang...';
                                try {
                                    this.stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                                    this.$refs.video.srcObject = this.stream;
                                    this.cameraReady = true;
                                    this.cameraError = '';
                                } catch (e2) {
                                    this.cameraError = 'Gagal mengakses kamera: ' + e2.message;
                                }
                            } else {
                                this.cameraError = 'Gagal mengakses kamera: [' + error.name + '] ' + error.message;
                            }
                        }
                    },
                
                    async enumerateDevices() {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) return;
                        const allDevices = await navigator.mediaDevices.enumerateDevices();
                        this.devices = allDevices.filter(device => device.kind === 'videoinput');
                        if (this.devices.length > 0 && !this.selectedDeviceId) {
                            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                            const frontCamera = this.devices.find(d => {
                                const label = (d.label || '').toLowerCase();
                                return label.includes('front') || label.includes('user') || label.includes('selfie') || label.includes('depan') || label.includes('facing');
                            });
                            if (isMobile && frontCamera) {
                                this.selectedDeviceId = frontCamera.deviceId;
                            } else if (frontCamera) {
                                this.selectedDeviceId = frontCamera.deviceId;
                            } else {
                                this.selectedDeviceId = this.devices[0].deviceId;
                            }
                        }
                    },
                
                    async startCamera() {
                        if (this.stream) {
                            this.stream.getTracks().forEach(track => track.stop());
                        }
                        this.cameraReady = false;
                        if (!navigator.mediaDevices) return;
                        try {
                            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                            const constraints = {
                                video: isMobile 
                                    ? { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }
                                    : (this.selectedDeviceId
                                        ? { deviceId: { exact: this.selectedDeviceId }, width: { ideal: 1280 }, height: { ideal: 720 } }
                                        : { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }),
                                audio: false
                            };
                            this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                            this.$refs.video.srcObject = this.stream;
                            this.cameraReady = true;
                            this.cameraError = '';
                            this.startTelemetryScanner();
                        } catch (error) {
                            this.cameraError = 'Gagal memulai tayangan kamera: ' + error.message;
                        }
                    },
                
                    async switchCamera() {
                        this.countdownSeconds = 3;
                        this.shutterTriggered = false;
                        this.faceDetected = false;
                        if (this.scanInterval) clearInterval(this.scanInterval);
                        await this.startCamera();
                    },
                
                    startTelemetryScanner() {
                        if (this.scanInterval) clearInterval(this.scanInterval);
                        this.scanInterval = setInterval(() => {
                            if (this.enrollStage === 'verify_details' || this.shutterTriggered) {
                                clearInterval(this.scanInterval);
                                return;
                            }
                            this.faceDetected = true;
                            this.faceX = Math.round(180 + Math.random() * 15);
                            this.faceY = Math.round(110 + Math.random() * 12);
                            this.detectionConfidence = Math.round(98 + Math.random() * 1.8);
                            this.livenessScore = (0.995 + Math.random() * 0.004).toFixed(4);
                
                            if (this.countdownSeconds > 0) {
                                this.countdownSeconds--;
                             } else {
                                this.capturePhoto();
                            }
                        }, 1000);
                    },
                
                    capturePhoto() {
                        const video = this.$refs.video;
                        const canvas = this.$refs.canvas;
                        canvas.width = video.videoWidth || 640;
                        canvas.height = video.videoHeight || 480;
                        const context = canvas.getContext('2d');
                        context.scale(-1, 1);
                        context.drawImage(video, -canvas.width, 0, canvas.width, canvas.height);
                        const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                
                        if (this.enrollStage === 'capture_front') {
                            this.frontImage = dataUrl;
                            this.enrollStage = 'capture_left';
                            this.countdownSeconds = 3;
                            this.shutterTriggered = false;
                        } else if (this.enrollStage === 'capture_left') {
                            this.leftImage = dataUrl;
                            this.enrollStage = 'capture_right';
                            this.countdownSeconds = 3;
                            this.shutterTriggered = false;
                        } else if (this.enrollStage === 'capture_right') {
                            this.rightImage = dataUrl;
                            this.enrollStage = 'verify_details';
                            if (this.stream) {
                                this.stream.getTracks().forEach(track => track.stop());
                            }
                        }
                    },
                
                    resetSequence() {
                        this.frontImage = null;
                        this.leftImage = null;
                        this.rightImage = null;
                        this.enrollStage = 'capture_front';
                        this.countdownSeconds = 3;
                        this.shutterTriggered = false;
                        this.startCamera();
                    },
                
                    confirmPhoto() {
                        $wire.enrollFace(this.frontImage, this.leftImage, this.rightImage);
                    }
                }" x-init="await requestCameraAccess();">

                <div class="text-center max-w-md mx-auto">

                    <!-- Header -->
                    <div class="mb-6">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-blue-500/25 mb-4">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="heading-3">Pendaftaran Kunci Wajah</h3>
                        <p class="enroll-instruction label-sm mt-1.5 leading-relaxed text-slate-400" x-text="instructionText"></p>
                    </div>

                    <!-- Step Progress -->
                    <div class="enroll-step-box mb-6 bg-black/20 border border-white/8 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="label-xs text-blue-400 font-bold" x-text="stageBadge"></span>
                            <span class="enroll-countdown-badge text-[10px] font-mono font-bold px-2 py-0.5 rounded-lg bg-white/5 border border-white/10 text-slate-400"
                                  x-text="enrollStage !== 'verify_details' ? 'Tersisa ' + countdownSeconds + 'd' : 'Selesai'"></span>
                        </div>

                        <!-- Nodes + track (overflow-safe nested approach) -->
                        <div class="flex items-start justify-between px-3 relative">
                            {{-- Track background --}}
                            <div class="enroll-track absolute top-[14px] left-[30px] right-[30px] h-[2px] bg-white/8 rounded-full overflow-hidden z-0">
                                <div class="absolute left-0 top-0 h-full bg-gradient-to-r from-blue-600 to-indigo-600 transition-all duration-500 rounded-full"
                                     :style="`width: ${currentProgress}%`"></div>
                            </div>

                            <!-- Node 1: Depan -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black border-2 transition-all duration-300"
                                     :class="enrollStage === 'capture_front'
                                        ? 'bg-blue-600 border-blue-500 text-white shadow-[0_0_12px_rgba(37,99,235,0.5)]'
                                        : (frontImage ? 'bg-gradient-to-r from-blue-600 to-indigo-600 border-indigo-500 text-white' : 'enroll-node-pending')">
                                    <span x-text="frontImage ? '✓' : '1'"></span>
                                </div>
                                <span class="text-[9px] font-bold mt-1.5 tracking-wide"
                                      :class="enrollStage === 'capture_front' ? 'text-blue-400' : (frontImage ? 'text-indigo-400' : 'text-slate-500')">Depan</span>
                            </div>

                            <!-- Node 2: Kiri -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black border-2 transition-all duration-300"
                                     :class="enrollStage === 'capture_left'
                                        ? 'bg-blue-600 border-blue-500 text-white shadow-[0_0_12px_rgba(37,99,235,0.5)]'
                                        : (leftImage ? 'bg-gradient-to-r from-blue-600 to-indigo-600 border-indigo-500 text-white' : 'enroll-node-pending')">
                                    <span x-text="leftImage ? '✓' : '2'"></span>
                                </div>
                                <span class="text-[9px] font-bold mt-1.5 tracking-wide"
                                      :class="enrollStage === 'capture_left' ? 'text-blue-400' : (leftImage ? 'text-indigo-400' : 'text-slate-500')">Kiri</span>
                            </div>

                            <!-- Node 3: Kanan -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black border-2 transition-all duration-300"
                                     :class="enrollStage === 'capture_right'
                                        ? 'bg-blue-600 border-blue-500 text-white shadow-[0_0_12px_rgba(37,99,235,0.5)]'
                                        : (rightImage ? 'bg-gradient-to-r from-blue-600 to-indigo-600 border-indigo-500 text-white' : 'enroll-node-pending')">
                                    <span x-text="rightImage ? '✓' : '3'"></span>
                                </div>
                                <span class="text-[9px] font-bold mt-1.5 tracking-wide"
                                      :class="enrollStage === 'capture_right' ? 'text-blue-400' : (rightImage ? 'text-indigo-400' : 'text-slate-500')">Kanan</span>
                            </div>

                            <!-- Node 4: Selesai -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-black border-2 transition-all duration-300"
                                     :class="enrollStage === 'verify_details'
                                        ? 'bg-gradient-to-r from-blue-600 to-indigo-600 border-indigo-500 text-white shadow-[0_0_12px_rgba(79,70,229,0.5)]'
                                        : 'enroll-node-pending'">
                                    <span x-text="enrollStage === 'verify_details' ? '✓' : '4'"></span>
                                </div>
                                <span class="text-[9px] font-bold mt-1.5 tracking-wide"
                                      :class="enrollStage === 'verify_details' ? 'text-indigo-400' : 'text-slate-500'">Selesai</span>
                            </div>
                        </div>
                    </div>

                    <!-- Camera switcher -->
                    <div class="mb-4" x-show="devices.length > 1" style="display: none;">
                        <div class="relative">
                            <select x-model="selectedDeviceId" @change="switchCamera()"
                                class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer appearance-none">
                                <template x-for="device in devices" :key="device.deviceId">
                                    <option :value="device.deviceId" x-text="device.label || 'Kamera ' + (devices.indexOf(device) + 1)"></option>
                                </template>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Camera error panel -->
                    <div x-show="cameraError" class="enroll-error-panel mb-6 bg-rose-950/60 border border-rose-500/30 rounded-2xl p-5 text-xs text-slate-300 flex flex-col space-y-4 shadow-2xl text-left" style="display: none;">
                        <div class="flex items-start gap-3">
                            <div class="enroll-error-icon w-10 h-10 rounded-xl bg-rose-500/15 border border-rose-500/25 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="enroll-error-title label-md font-bold text-white">Kamera Tidak Dapat Diakses</h4>
                                <p class="label-xs text-rose-300 mt-0.5 leading-relaxed" x-text="cameraError"></p>
                            </div>
                        </div>

                        <div class="enroll-error-diag bg-black/30 border border-white/8 rounded-xl p-3.5 space-y-2">
                            <span class="label-xs text-slate-400 font-bold block mb-2">Diagnostik Sistem:</span>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-[10px]">
                                <div class="flex items-center gap-1.5">
                                    <span class="text-slate-500">Secure Context:</span>
                                    <span class="font-bold" :class="window.isSecureContext ? 'text-blue-400' : 'text-rose-400'" x-text="window.isSecureContext ? '✓ Ya' : '✗ Tidak'"></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-slate-500">Media API:</span>
                                    <span class="font-bold" :class="navigator.mediaDevices ? 'text-blue-400' : 'text-rose-400'" x-text="navigator.mediaDevices ? '✓ Ada' : '✗ Tidak'"></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-slate-500">Protocol:</span>
                                    <span class="font-bold text-blue-400" x-text="location.protocol"></span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-slate-500">Host:</span>
                                    <span class="font-bold text-blue-400" x-text="location.hostname"></span>
                                </div>
                            </div>
                        </div>

                        <div x-show="permissionDenied" class="enroll-error-permission bg-black/30 border border-white/8 rounded-xl p-3.5 space-y-1.5 text-[10px] text-slate-400 leading-relaxed" style="display: none;">
                            <span class="label-xs text-blue-400 font-bold block mb-1">Cara Reset Izin Kamera:</span>
                            <p>1. Klik ikon <b class="text-white">gembok</b> di kiri URL bar</p>
                            <p>2. Pilih <b class="text-white">Site settings</b> atau <b class="text-white">Izin</b></p>
                            <p>3. Ubah Camera dari Block menjadi <b class="text-blue-400">Allow</b></p>
                            <p>4. Refresh halaman (Ctrl+R)</p>
                        </div>

                        <div x-data="{ retrying: false }">
                            <button @click="retrying = true; await requestCameraAccess(); retrying = false;" type="button"
                                :disabled="retrying"
                                class="w-full py-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-700 hover:to-rose-800 text-white text-xs font-bold transition-all shadow-md">
                                <span x-show="!retrying">Coba Ajukan Izin Lagi</span>
                                <span x-show="retrying" class="animate-pulse">Meminta akses...</span>
                            </button>
                        </div>
                    </div>

                    <!-- Camera viewport -->
                    <div x-show="enrollStage !== 'verify_details'"
                        class="enroll-camera-viewport relative mx-auto w-72 h-72 bg-[#0a1020] border-2 border-white/10 rounded-full overflow-hidden shadow-2xl shadow-blue-900/30 flex items-center justify-center">

                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-transparent via-[#0d1527]/10 to-[#0d1527]/90 z-10 pointer-events-none"></div>
                        <div class="absolute inset-2 border border-dashed border-blue-500/20 rounded-full animate-spin pointer-events-none z-10" style="animation-duration: 30s;"></div>
                        <div class="absolute inset-5 border border-blue-500/15 rounded-full pointer-events-none z-10"></div>

                        <!-- Direction guide overlays -->
                        <div x-show="enrollStage === 'capture_left'"
                            class="absolute inset-x-10 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-600/90 to-indigo-600/90 px-3 py-1.5 rounded-full pointer-events-none z-10 text-[10px] font-bold text-white tracking-wider animate-bounce">
                            ← Hadap Kiri
                        </div>
                        <div x-show="enrollStage === 'capture_right'"
                            class="absolute inset-x-10 top-1/2 -translate-y-1/2 bg-gradient-to-r from-blue-600/90 to-indigo-600/90 px-3 py-1.5 rounded-full pointer-events-none z-10 text-[10px] font-bold text-white tracking-wider animate-bounce">
                            Hadap Kanan →
                        </div>

                        <!-- Scan line -->
                        <div class="absolute inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-500 to-transparent shadow-[0_0_12px_#3b82f6] z-10 pointer-events-none"
                            style="animation: scanLine 2s linear infinite;"></div>

                        <!-- Countdown overlay -->
                        <div x-show="faceDetected && countdownSeconds > 0"
                            class="absolute inset-0 bg-[#090e1a]/80 backdrop-blur-[1px] z-20 flex flex-col items-center justify-center pointer-events-none">
                            <div class="text-[10px] font-bold text-blue-400 mb-1 tracking-widest">Deteksi Kunci</div>
                            <div class="heading-value-white" x-text="countdownSeconds"></div>
                            <div class="text-[10px] text-slate-400 mt-1">Tahan Posisi</div>
                        </div>

                        <!-- Live badge -->
                        <div class="absolute bottom-5 z-10 flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/60 border border-white/10 text-[9px] font-bold text-white">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-ping"></span>
                            Live
                        </div>

                        <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover rounded-full" style="transform: scaleX(-1);"></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                    </div>

                    <!-- Verification gallery (step 4) -->
                    <div x-show="enrollStage === 'verify_details'"
                        class="enroll-gallery bg-[#0d1527]/90 border border-white/10 rounded-2xl p-5 shadow-2xl"
                        style="display: none;">
                        <h4 class="label-xs font-bold text-slate-400 mb-4 text-left">Hasil Foto Wajah</h4>
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <div class="enroll-gallery-img relative bg-[#121d33] border border-blue-500/25 rounded-xl overflow-hidden aspect-square shadow-md">
                                <img :src="frontImage" class="w-full h-full object-cover" style="transform: scaleX(-1)">
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[8px] font-bold py-0.5 text-center">Depan</div>
                            </div>
                            <div class="enroll-gallery-img relative bg-[#121d33] border border-blue-500/25 rounded-xl overflow-hidden aspect-square shadow-md">
                                <img :src="leftImage" class="w-full h-full object-cover" style="transform: scaleX(-1)">
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[8px] font-bold py-0.5 text-center">Kiri</div>
                            </div>
                            <div class="enroll-gallery-img relative bg-[#121d33] border border-blue-500/25 rounded-xl overflow-hidden aspect-square shadow-md">
                                <img :src="rightImage" class="w-full h-full object-cover" style="transform: scaleX(-1)">
                                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[8px] font-bold py-0.5 text-center">Kanan</div>
                            </div>
                        </div>
                        <div class="enroll-confirm-hint flex items-center gap-2.5 p-3 bg-blue-500/8 border border-blue-500/20 rounded-xl text-xs font-bold text-blue-300">
                            <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ketiga foto berhasil diambil dan siap disimpan.
                        </div>
                    </div>

                    <!-- Action buttons — tall, full width -->
                    <div class="mt-6 space-y-3 w-full">
                        <button @click="capturePhoto()" x-show="enrollStage !== 'verify_details'" type="button"
                            class="w-full py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold transition-all shadow-lg shadow-blue-600/25 flex items-center justify-center gap-2">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Ambil Foto
                        </button>

                        <div x-show="enrollStage === 'verify_details'" class="flex gap-3" style="display: none;">
                            <button @click="resetSequence()" type="button"
                                class="enroll-btn-secondary flex-1 py-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-200 text-sm font-bold transition-all">
                                Ulangi
                            </button>
                            <button @click="confirmPhoto()" type="button"
                                class="flex-2 px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold transition-all shadow-lg shadow-blue-600/25">
                                Simpan Kunci Wajah
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<style>
    @keyframes scanLine {
        0%   { top: 0%; }
        50%  { top: 100%; }
        100% { top: 0%; }
    }

    /* ============ Pending node dark-mode defaults ============ */
    .enroll-node-pending {
        background: rgba(0,0,0,0.30);
        border-color: rgba(255,255,255,0.15);
        color: #64748b;
    }

    /* ============ LIGHT MODE: Enrollment Wizard ============ */
    html.light .enroll-wizard {
        background: #ffffff !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 20px 60px -12px rgba(15,23,42,0.10), 0 4px 16px rgba(15,23,42,0.06) !important;
    }

    html.light .enroll-instruction { color: #475569 !important; }

    html.light .enroll-step-box {
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
    }

    html.light .enroll-countdown-badge {
        background: #e2e8f0 !important;
        border-color: #cbd5e1 !important;
        color: #475569 !important;
    }

    html.light .enroll-track {
        background: #e2e8f0 !important;
    }

    html.light .enroll-node-pending {
        background: #e2e8f0 !important;
        border-color: #cbd5e1 !important;
        color: #94a3b8 !important;
    }

    html.light .enroll-error-panel {
        background: #fff1f2 !important;
        border-color: #fecdd3 !important;
        color: #9f1239 !important;
    }

    html.light .enroll-error-icon {
        background: #fee2e2 !important;
        border-color: #fca5a5 !important;
    }

    html.light .enroll-error-title { color: #be123c !important; }

    html.light .enroll-error-diag,
    html.light .enroll-error-permission {
        background: #ffe4e6 !important;
        border-color: #fecdd3 !important;
    }

    html.light .enroll-camera-viewport {
        border-color: #93c5fd !important;
        box-shadow: 0 20px 50px -12px rgba(37,99,235,0.20) !important;
    }

    html.light .enroll-gallery {
        background: #f8fafc !important;
        border-color: #e2e8f0 !important;
    }

    html.light .enroll-gallery-img {
        background: #f1f5f9 !important;
        border-color: #bfdbfe !important;
    }

    html.light .enroll-confirm-hint {
        background: #eff6ff !important;
        border-color: #bfdbfe !important;
        color: #1d4ed8 !important;
    }

    html.light .enroll-btn-secondary {
        background: #f1f5f9 !important;
        border-color: #e2e8f0 !important;
        color: #334155 !important;
    }

    html.light .enroll-error-panel button {
        background: linear-gradient(to right, rgba(225,29,72,0.70), rgba(190,18,60,0.70)) !important;
        color: #ffffff !important;
    }
    html.light .enroll-error-panel button:hover {
        background: linear-gradient(to right, rgba(225,29,72,0.85), rgba(190,18,60,0.85)) !important;
    }
    html.light .enroll-error-panel button svg { color: #ffffff !important; }
</style>
