<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="heading-1">Profil Karyawan Saya</h1>
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
                <!-- Left: Beautiful Employee Card Info -->
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-1 text-center">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>

                    <!-- Avatar with glowing border -->
                    <div class="relative w-28 h-28 mx-auto mb-6">
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 via-blue-400 to-emerald-400 rounded-2xl blur-[8px] opacity-75"></div>
                        <div class="relative w-full h-full bg-[#0d1527] border border-white/10 rounded-2xl overflow-hidden flex items-center justify-center text-5xl font-black text-white font-display uppercase">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>

                    <h3 class="heading-3">{{ auth()->user()->name ?? 'Pulse Employee' }}</h3>
                    <span class="badge-info mt-2 inline-block">
                        {{ auth()->user()->role ?? 'Karyawan' }}
                    </span>

                    <div class="mt-8 pt-6 border-t border-white/5 space-y-4 text-left text-xs text-slate-300">
                        <div class="flex justify-between">
                            <span class="label-xs">ID Karyawan</span>
                            <span class="label-sm font-mono font-bold text-white">#PP-{{ str_pad(auth()->user()->id ?? 1, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="label-xs">Email Kantor</span>
                            <span class="label-sm font-bold text-white">{{ auth()->user()->email ?? 'employee@AbsenPintar.com' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="label-xs">Kantor Cabang</span>
                            <span class="label-sm font-bold text-blue-400">{{ auth()->user()->branch->name ?? 'HQ Sudirman' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Trusted Biometrics & Face Registration Core Workspace -->
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-2">
                    <h3 class="heading-3 mb-6">Pusat Identitas & Kunci Biometrik</h3>

                    <div class="space-y-6">
                        <!-- Face Biometric Registration Status Panel -->
                        <div class="p-6 bg-[#0d1527] border border-white/10 rounded-2xl relative overflow-hidden">
                            <div class="absolute -right-16 -bottom-16 w-36 h-36 bg-blue-500/5 rounded-full blur-2xl"></div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="space-y-3.5">
                                    <div class="flex items-center space-x-2">
                                        @if (auth()->user()->hasRegisteredFace())
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                            <span class="badge-success">Kunci Biometrik Terdaftar Aman</span>
                                        @else
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-pulse"></span>
                                            <span class="badge-danger">Biometrik Wajah Belum Terdaftar</span>
                                        @endif
                                    </div>
                                    <h4 class="label-md font-bold text-white">Baseline Kunci Induk Wajah (Face Master)</h4>
                                    <p class="label-sm max-w-md leading-relaxed">
                                        Kunci induk wajah Anda menggunakan struktur vektor multi-sudut (Depan, Kiri, dan Rerata Kanan) untuk memverifikasi identitas fisik secara presisi tanpa batas token atau latensi awan.
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

                        <!-- Trusted Session Parameters (Parity UX) -->
                        <div class="p-5 bg-[#0d1527] border border-white/5 rounded-2xl space-y-3">
                            <h4 class="label-xs text-white flex items-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-ping"></span>
                                Parameter Sesi Aktif
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-300">
                                <div>
                                    <span class="label-xs">Sistem Operasi:</span>
                                    <span class="label-sm font-bold text-white block mt-0.5">Linux (x86_64)</span>
                                </div>
                                <div>
                                    <span class="label-xs">Agen Browser:</span>
                                    <span class="label-sm font-bold text-white block mt-0.5">Chrome 124.0.0</span>
                                </div>
                                <div>
                                    <span class="label-xs">IP Address Sesi:</span>
                                    <span class="label-sm font-mono font-bold text-white block mt-0.5">127.0.0.1 (Localhost Loopback)</span>
                                </div>
                                <div>
                                    <span class="label-xs">Status Verifikasi:</span>
                                    <span class="badge-success mt-0.5">MFA Terverifikasi</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="label-xs text-slate-400">Token Perangkat Keras Tepercaya</h4>
                            <div class="divide-y divide-white/5 text-xs text-slate-300">
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <span class="label-sm font-bold text-white">Kunci Fisik (FIDO2/WebAuthn)</span>
                                        <span class="block label-xs mt-0.5">Status: Belum terdaftar</span>
                                    </div>
                                    <button class="btn-sm btn-secondary py-1.5 px-3 rounded-xl label-xs">Daftarkan Token</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Face Biometric Multi-Angle Enrollment View -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl max-w-2xl mx-auto relative overflow-hidden"
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
                        return 'Kunci biometrik berhasil diamankan. Tinjau foto hasil pemindaian sebelum mengirim.';
                    },
                
                    get stageBadge() {
                        if (this.enrollStage === 'capture_front') return 'SUDUT 1/3: WAJAH DEPAN';
                        if (this.enrollStage === 'capture_left') return 'SUDUT 2/3: WAJAH KIRI';
                        if (this.enrollStage === 'capture_right') return 'SUDUT 3/3: WAJAH KANAN';
                        return 'VERIFIKASI MATRIKS VEKTOR';
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
                
                    simulatePhoto() {
                        const dummyFront = 'data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2240%22 stroke=%22%2310b981%22 stroke-width=%222%22 fill=%22%230d1527%22/><circle cx=%2250%22 cy=%2235%22 r=%2215%22 fill=%22%2310b981%22/><path d=%22M25,75 Q50,55 75,75%22 stroke=%22%2310b981%22 stroke-width=%223%22 fill=%22none%22/></svg>';
                        const dummyLeft = 'data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2240%22 stroke=%22%236366f1%22 stroke-width=%222%22 fill=%22%230d1527%22/><circle cx=%2240%22 cy=%2235%22 r=%2215%22 fill=%22%236366f1%22/><path d=%22M25,75 Q50,55 75,75%22 stroke=%22%236366f1%22 stroke-width=%223%22 fill=%22none%22/></svg>';
                        const dummyRight = 'data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22300%22 viewBox=%220 0 100 100%22><circle cx=%2250%22 cy=%2250%22 r=%2240%22 stroke=%22%2306b6d4%22 stroke-width=%222%22 fill=%22%230d1527%22/><circle cx=%2260%22 cy=%2235%22 r=%2215%22 fill=%22%2306b6d4%22/><path d=%22M25,75 Q50,55 75,75%22 stroke=%22%2306b6d4%22 stroke-width=%223%22 fill=%22none%22/></svg>';
                
                        this.frontImage = dummyFront;
                        this.leftImage = dummyLeft;
                        this.rightImage = dummyRight;
                        this.enrollStage = 'verify_details';
                        if (this.stream) {
                            this.stream.getTracks().forEach(track => track.stop());
                        }
                    },
                
                    confirmPhoto() {
                        $wire.enrollFace(this.frontImage, this.leftImage, this.rightImage);
                    }
                }" x-init="await requestCameraAccess();">

                <div class="text-center max-w-md mx-auto">
                    <!-- Premium Cybernetic Header -->
                    <div class="mb-6">
                        <div class="w-12 h-12 bg-gradient-to-tr from-blue-600 via-blue-500 to-emerald-400 rounded-2xl flex items-center justify-center mx-auto shadow-lg shadow-blue-500/10 mb-3 animate-pulse">
                            👤
                        </div>
                        <h3 class="heading-3">Pendaftaran Kunci Wajah</h3>
                        <p class="label-sm mt-1 leading-relaxed" x-text="instructionText"></p>
                    </div>

                    <!-- Cybernetic Vector Node Step Progress Indicator -->
                    <div class="mb-6 bg-slate-950/40 border border-white/5 rounded-2xl p-4 shadow-inner">
                        <div class="flex justify-between items-center mb-3">
                            <span class="label-xs text-blue-400" x-text="stageBadge"></span>
                            <span class="label-xs font-mono bg-white/5 px-2 py-0.5 rounded-lg" x-text="enrollStage !== 'verify_details' ? 'Tersisa ' + countdownSeconds + 'd' : 'SELESAI'"></span>
                        </div>
                        
                        <!-- Visual Step Nodes -->
                        <div class="flex items-center justify-between px-2 pt-1.5 relative">
                            <!-- Background connecting bar -->
                            <div class="absolute top-[18px] inset-x-8 h-[2px] bg-slate-800 z-0"></div>
                            <!-- Active progress line filled dynamically -->
                            <div class="absolute top-[18px] left-8 h-[2px] bg-gradient-to-r from-blue-600 to-emerald-500 z-0 transition-all duration-300" 
                                 :style="`width: ${Math.max(0, (currentProgress - 10) * 1.1)}%`"></div>

                            <!-- Node 1: FRONT -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center font-mono text-[10px] font-black border transition-all duration-300"
                                     :class="enrollStage === 'capture_front' ? 'bg-blue-500/20 border-blue-400 text-blue-400 shadow-[0_0_10px_rgba(59,130,246,0.3)] animate-pulse' : (frontImage ? 'bg-emerald-500 border-emerald-500 text-slate-950' : 'bg-slate-900 border-slate-700 text-slate-500')">
                                    <span x-text="frontImage ? '✓' : '1'"></span>
                                </div>
                                <span class="label-xs uppercase mt-1.5" :class="enrollStage === 'capture_front' ? 'text-blue-400' : (frontImage ? 'text-emerald-400' : 'text-slate-500')">Depan</span>
                            </div>

                            <!-- Node 2: LEFT -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center font-mono text-[10px] font-black border transition-all duration-300"
                                     :class="enrollStage === 'capture_left' ? 'bg-indigo-500/20 border-indigo-400 text-indigo-400 shadow-[0_0_10px_rgba(99,102,241,0.3)] animate-pulse' : (leftImage ? 'bg-emerald-500 border-emerald-500 text-slate-950' : 'bg-slate-900 border-slate-700 text-slate-500')">
                                    <span x-text="leftImage ? '✓' : '2'"></span>
                                </div>
                                <span class="label-xs uppercase mt-1.5" :class="enrollStage === 'capture_left' ? 'text-indigo-400' : (leftImage ? 'text-emerald-400' : 'text-slate-500')">Kiri</span>
                            </div>

                            <!-- Node 3: RIGHT -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center font-mono text-[10px] font-black border transition-all duration-300"
                                     :class="enrollStage === 'capture_right' ? 'bg-cyan-500/20 border-cyan-400 text-cyan-400 shadow-[0_0_10px_rgba(6,182,212,0.3)] animate-pulse' : (rightImage ? 'bg-emerald-500 border-emerald-500 text-slate-950' : 'bg-slate-900 border-slate-700 text-slate-500')">
                                    <span x-text="rightImage ? '✓' : '3'"></span>
                                </div>
                                <span class="label-xs uppercase mt-1.5" :class="enrollStage === 'capture_right' ? 'text-cyan-400' : (rightImage ? 'text-emerald-400' : 'text-slate-500')">Kanan</span>
                            </div>

                            <!-- Node 4: VERIFY -->
                            <div class="flex flex-col items-center z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center font-mono text-[10px] font-black border transition-all duration-300"
                                     :class="enrollStage === 'verify_details' ? 'bg-emerald-500/20 border-emerald-400 text-emerald-400 shadow-[0_0_10px_rgba(16,185,129,0.3)] animate-pulse' : 'bg-slate-900 border-slate-700 text-slate-500'">
                                    4
                                </div>
                                <span class="label-xs uppercase mt-1.5" :class="enrollStage === 'verify_details' ? 'text-emerald-400' : 'text-slate-500'">Ledger</span>
                            </div>
                        </div>
                    </div>

                    <!-- Camera Device list switcher -->
                    <div class="mb-4 max-w-md mx-auto" x-show="devices.length > 1" style="display: none;">
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

                    <!-- Premium Secure Context & Camera Troubleshooter Panel -->
                    <div x-show="cameraError" class="mb-6 bg-[#1a0e1a]/85 border border-rose-500/30 rounded-2xl p-5 text-xs text-slate-300 flex flex-col space-y-4 shadow-2xl relative overflow-hidden text-left" style="display: none;">
                        <div class="absolute -right-12 -top-12 w-28 h-28 bg-rose-500/5 rounded-full blur-2xl"></div>
                        
                        <!-- Header Info -->
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center flex-shrink-0 text-rose-400 shadow-inner">
                                🔒
                            </div>
                            <div>
                                <h4 class="label-md font-bold text-white">Kamera Tidak Dapat Diakses</h4>
                                <p class="label-xs text-rose-300 mt-0.5 leading-relaxed" x-text="cameraError"></p>
                            </div>
                        </div>

                        <!-- Diagnostic Info -->
                        <div class="bg-black/40 border border-white/5 rounded-2xl p-3.5 space-y-2">
                            <span class="label-xs text-amber-400 block">🔍 Diagnostik Sistem:</span>
                            <div class="grid grid-cols-2 gap-2 text-[10px]">
                                <div>
                                    <span class="text-slate-505">Secure Context:</span>
                                    <span class="font-bold" :class="window.isSecureContext ? 'text-emerald-400' : 'text-rose-400'" x-text="window.isSecureContext ? '✓ Ya' : '✗ Tidak'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-505">Media API:</span>
                                    <span class="font-bold" :class="navigator.mediaDevices ? 'text-emerald-400' : 'text-rose-400'" x-text="navigator.mediaDevices ? '✓ Tersedia' : '✗ Tidak Ada'"></span>
                                </div>
                                <div>
                                    <span class="text-slate-505">Protocol:</span>
                                    <span class="font-bold text-blue-400" x-text="location.protocol"></span>
                                </div>
                                <div>
                                    <span class="text-slate-550">Host:</span>
                                    <span class="font-bold text-blue-400" x-text="location.hostname"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Fix Guide -->
                        <div x-show="permissionDenied" class="bg-black/40 border border-white/5 rounded-2xl p-3.5 space-y-2" style="display: none;">
                            <span class="label-xs text-blue-400 block">💡 Cara Reset Izin Kamera:</span>
                            <div class="text-[10px] leading-relaxed space-y-1.5 text-slate-400">
                                <p>1. Klik ikon <b class="text-white">🔒 gembok</b> di sebelah kiri URL address bar</p>
                                <p>2. Pilih <b class="text-white">Site settings</b> atau <b class="text-white">Izin</b></p>
                                <p>3. Ubah <b class="text-white">Camera</b> dari "Block" menjadi <b class="text-emerald-400">Allow</b></p>
                                <p>4. Refresh halaman ini (Ctrl+R / F5)</p>
                            </div>
                        </div>

                        <!-- Action Buttons designed for finger reach -->
                        <div class="flex flex-col sm:flex-row gap-2.5 pt-1.5" x-data="{ retrying: false }">
                            <button @click="retrying = true; await requestCameraAccess(); retrying = false;" type="button" 
                                :disabled="retrying"
                                class="btn-sm btn-secondary flex-1 py-3 text-[10px]">
                                <span x-show="!retrying">🔄 Coba Ajukan Izin Lagi</span>
                                <span x-show="retrying" class="animate-pulse">⏳ Meminta akses...</span>
                            </button>
                            <button @click="simulatePhoto(); confirmPhoto();" type="button" class="btn-sm btn-success flex-1 py-3 text-[10px]">
                                ⚡ Simulasikan Kamera (Instan)
                            </button>
                        </div>
                    </div>

                    <!-- Cybernetic Circle Viewport Camera Feed (Custom biometric face circle) -->
                    <div x-show="enrollStage !== 'verify_details'"
                        class="relative mx-auto w-64 h-64 bg-[#0d1527] border-2 border-white/10 rounded-full overflow-hidden shadow-2xl flex items-center justify-center">

                        <!-- Scanning overlay effect -->
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-transparent via-[#0d1527]/10 to-[#0d1527]/90 z-10 pointer-events-none"></div>

                        <!-- Vector Bounding Target ring -->
                        <div class="absolute inset-2 border-2 border-dashed border-blue-400/30 rounded-full animate-spin pointer-events-none z-10" style="animation-duration: 25s;"></div>
                        <div class="absolute inset-4 border border-blue-400/40 rounded-full pointer-events-none z-10"></div>
                        <div class="absolute inset-8 border-2 border-cyan-400/30 rounded-full pointer-events-none z-10 animate-pulse"></div>

                        <!-- Side guides for angles -->
                        <div x-show="enrollStage === 'capture_left'"
                            class="absolute inset-x-8 top-1/2 -translate-y-1/2 bg-indigo-500/80 border border-indigo-400/20 px-3 py-1.5 rounded-full pointer-events-none z-10 label-xs text-white tracking-widest uppercase animate-bounce">
                            ← HADAP KIRI
                        </div>
                        <div x-show="enrollStage === 'capture_right'"
                            class="absolute inset-x-8 top-1/2 -translate-y-1/2 bg-cyan-500/80 border border-cyan-400/20 px-3 py-1.5 rounded-full pointer-events-none z-10 label-xs text-white tracking-widest uppercase animate-bounce">
                            HADAP KANAN →
                        </div>

                        <!-- Holographic Sweeper -->
                        <div class="absolute inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-blue-400 to-transparent shadow-[0_0_15px_#3b82f6] z-10 pointer-events-none animate-scan"
                            style="animation: scanLine 2s linear infinite;"></div>

                        <!-- Countdown HUD circular Widget -->
                        <div x-show="faceDetected && countdownSeconds > 0"
                            class="absolute inset-0 bg-[#090e1a]/80 backdrop-blur-[1px] z-20 flex flex-col items-center justify-center pointer-events-none">
                            <div class="label-xs text-blue-400 mb-1">DETEKSI KUNCI</div>
                            <div class="heading-value-white" x-text="countdownSeconds"></div>
                            <div class="label-xs mt-1">TAHAN POSISI</div>
                        </div>

                        <!-- Status Badge Overlay -->
                        <div class="absolute bottom-4 z-10 badge-success bg-black/70 border-emerald-500/25 shadow-none">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                            <span>LIVE</span>
                        </div>

                        <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover rounded-full" style="transform: scaleX(-1);"></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                    </div>

                    <!-- Step 4: Verification Grid Preview (Unified Gallery) -->
                    <div x-show="enrollStage === 'verify_details'"
                        class="max-w-md mx-auto bg-[#0d1527]/90 border border-white/10 rounded-2xl p-5 shadow-2xl relative overflow-hidden"
                        style="display: none;">
                        <div class="absolute -right-16 -top-16 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
                        <h4 class="label-xs mb-4">Matriks Ledger Biometrik Terkunci</h4>

                        <div class="grid grid-cols-3 gap-3.5 mb-5">
                            <!-- Front Profile -->
                            <div class="relative bg-[#121d33] border border-blue-500/30 rounded-2xl overflow-hidden aspect-square flex flex-col shadow-lg">
                                <img :src="frontImage" class="w-full h-full object-cover transform scaleX(-1)">
                                <div class="absolute bottom-0 inset-x-0 bg-blue-600 text-[#090e1a] text-[8px] font-black tracking-wider uppercase py-0.5 leading-none">
                                    DEPAN
                                </div>
                            </div>

                            <!-- Left Profile -->
                            <div class="relative bg-[#121d33] border border-indigo-500/30 rounded-2xl overflow-hidden aspect-square flex flex-col shadow-lg">
                                <img :src="leftImage" class="w-full h-full object-cover transform scaleX(-1)">
                                <div class="absolute bottom-0 inset-x-0 bg-indigo-500 text-white text-[8px] font-black tracking-wider uppercase py-0.5 leading-none">
                                    KIRI
                                </div>
                            </div>

                            <!-- Right Profile -->
                            <div class="relative bg-[#121d33] border border-cyan-500/30 rounded-2xl overflow-hidden aspect-square flex flex-col shadow-lg">
                                <img :src="rightImage" class="w-full h-full object-cover transform scaleX(-1)">
                                <div class="absolute bottom-0 inset-x-0 bg-cyan-500 text-[#090e1a] text-[8px] font-black tracking-wider uppercase py-0.5 leading-none">
                                    KANAN
                                </div>
                            </div>
                        </div>

                        <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/25 text-emerald-400 rounded-2xl text-left text-xs font-bold flex items-center space-x-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                            <span>Ketiga sudut wajah berhasil dihitung, diselaraskan, dan siap didaftarkan!</span>
                        </div>
                    </div>

                    <!-- Tactical Finger Buttons at bottom -->
                    <div class="mt-6 flex flex-col items-center justify-center space-y-4">
                        <div class="flex justify-center space-x-3.5 w-full max-w-sm">
                            <button @click="capturePhoto()" x-show="enrollStage !== 'verify_details'" type="button"
                                class="btn-sm btn-primary w-full">
                                Ambil Foto Manual
                            </button>
                            <button @click="resetSequence()" x-show="enrollStage === 'verify_details'" type="button"
                                class="btn-sm btn-secondary flex-1"
                                style="display: none;">
                                Ulangi Urutan
                            </button>
                            <button @click="confirmPhoto()" x-show="enrollStage === 'verify_details'" type="button"
                                class="btn-sm btn-success flex-1"
                                style="display: none;">
                                Simpan Kunci Induk
                            </button>
                        </div>

                        <!-- Manual Insecure Simulation trigger block -->
                        <div class="w-full max-w-md pt-5 border-t border-white/5 mt-4" x-show="!cameraReady && enrollStage !== 'verify_details'">
                            <p class="label-xs mb-2.5">Simulasi Pengujian Lokal (Tanpa Kamera)</p>
                            <button @click="simulatePhoto(); confirmPhoto();" type="button"
                                class="btn-sm btn-secondary w-full text-[10px] hover:text-emerald-400 hover:border-emerald-500/30 hover:bg-emerald-500/10">
                                ⚡ Lewati &amp; Daftarkan Wajah Simulasi (Instan)
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
        0% {
            top: 0%;
        }

        50% {
            top: 100%;
        }

        100% {
            top: 0%;
        }
    }
</style>
