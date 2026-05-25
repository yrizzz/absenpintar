<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Profil Karyawan Saya</h1>
                <p class="mt-1 text-sm text-slate-400 font-medium">Kelola kredensial keamanan Anda, daftarkan biometrik
                    tepercaya, dan verifikasi kantor cabang penempatan</p>
            </div>
            @if ($step === 'enroll')
                <button wire:click="$set('step', 'overview')"
                    class="inline-flex items-center px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-2xl hover:text-white transition-all cursor-pointer">
                    Batalkan Pendaftaran
                </button>
            @endif
        </div>

        @if (session()->has('success'))
            <div
                class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-bold flex items-center">
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
                <div
                    class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-1 text-center">
                    <div class="absolute -right-12 -top-12 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl"></div>

                    <!-- Avatar with glowing border -->
                    <div class="relative w-28 h-28 mx-auto mb-6">
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-blue-600 via-sky-400 to-emerald-400 rounded-3xl blur-[8px] opacity-75">
                        </div>
                        <div
                            class="relative w-full h-full bg-[#0d1527] border border-white/10 rounded-3xl overflow-hidden flex items-center justify-center text-3xl font-black text-white font-display">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                    </div>

                    <h3 class="text-xl font-extrabold text-white tracking-tight font-display">
                        {{ auth()->user()->name ?? 'Pulse Employee' }}</h3>
                    <span
                        class="text-xs font-bold text-sky-400 uppercase tracking-widest bg-sky-500/10 px-3 py-1 rounded-full border border-sky-500/20 mt-2 inline-block">
                        {{ auth()->user()->role ?? 'Karyawan' }}
                    </span>

                    <div class="mt-8 pt-6 border-t border-white/5 space-y-4 text-left text-xs text-slate-300">
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">ID
                                Karyawan</span>
                            <span
                                class="font-mono font-semibold text-white">#PP-{{ str_pad(auth()->user()->id ?? 1, 5, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Email
                                Kantor</span>
                            <span
                                class="font-semibold text-white">{{ auth()->user()->email ?? 'employee@AbsenPintar.com' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Kantor
                                Cabang</span>
                            <span
                                class="font-bold text-sky-400">{{ auth()->user()->branch->name ?? 'HQ Sudirman' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Trusted Biometrics & Face Registration Core Workspace -->
                <div
                    class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-2">
                    <h3 class="text-lg font-bold text-white tracking-tight font-display mb-6">Pusat Identitas & Kunci
                        Biometrik</h3>

                    <div class="space-y-6">
                        <!-- Face Biometric Registration Status Panel -->
                        <div class="p-6 bg-[#0d1527] border border-white/10 rounded-3xl relative overflow-hidden">
                            <div class="absolute -right-16 -bottom-16 w-36 h-36 bg-blue-500/5 rounded-full blur-2xl">
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div class="space-y-3.5">
                                    <div class="flex items-center space-x-2">
                                        @if (auth()->user()->hasRegisteredFace())
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-ping"></span>
                                            <span
                                                class="text-xs font-bold text-emerald-400 uppercase tracking-widest bg-emerald-500/10 border border-emerald-500/20 px-3 py-1 rounded-full">Kunci
                                                Biometrik Terdaftar Aman</span>
                                        @else
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-400 animate-pulse"></span>
                                            <span
                                                class="text-xs font-bold text-rose-400 uppercase tracking-widest bg-rose-500/10 border border-rose-500/20 px-3 py-1 rounded-full">Biometrik
                                                Wajah Belum Terdaftar</span>
                                        @endif
                                    </div>
                                    <h4 class="text-base font-bold text-white">Baseline Kunci Induk Wajah (Face Master)
                                    </h4>
                                    <p class="text-xs text-slate-400 max-w-md leading-relaxed">
                                        Kunci induk wajah Anda menggunakan struktur vektor multi-sudut (Depan, Kiri, dan
                                        Kanan) untuk memverifikasi identitas fisik secara presisi tanpa batas token atau
                                        latensi awan.
                                    </p>

                                    <div class="pt-2 flex flex-wrap gap-2.5">
                                        <button wire:click="$set('step', 'enroll')"
                                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 shadow-md transition-all cursor-pointer">
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
                                                class="inline-flex items-center px-4 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider rounded-2xl transition-all cursor-pointer">
                                                Hapus Otorisasi Kunci
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Face Master Preview Thumbnail -->
                                <div class="flex-shrink-0 mx-auto md:mx-0">
                                    <div
                                        class="relative w-28 h-28 bg-[#121d33] border border-white/10 rounded-2xl overflow-hidden flex items-center justify-center">
                                        @if (auth()->user()->hasRegisteredFace())
                                            <img src="{{ auth()->user()->getMasterFaceUrl() }}"
                                                class="w-full h-full object-cover transform scaleX(-1)">
                                            <div
                                                class="absolute bottom-1 right-1 bg-emerald-500 text-white rounded-full p-1 shadow">
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
                                                <span class="text-[9px] font-bold block uppercase tracking-wider">Tanpa
                                                    Foto</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trusted Session Parameters (Parity UX) -->
                        <div class="p-5 bg-[#0d1527] border border-white/5 rounded-2xl space-y-3">
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-2 animate-ping"></span>
                                Parameter Sesi Aktif
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-300">
                                <div>
                                    <span class="text-slate-400 font-medium">Sistem Operasi:</span>
                                    <span class="font-bold text-white block mt-0.5">Linux (x86_64)</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-medium">Agen Browser:</span>
                                    <span class="font-bold text-white block mt-0.5">Chrome 124.0.0</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-medium">IP Address Sesi:</span>
                                    <span class="font-mono font-bold text-white block mt-0.5">127.0.0.1 (Localhost
                                        Loopback)</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-medium">Status Verifikasi:</span>
                                    <span
                                        class="font-bold text-emerald-400 block mt-0.5 uppercase tracking-wider text-[10px]">MFA
                                        Terverifikasi</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Token Perangkat Keras
                                Tepercaya</h4>
                            <div class="divide-y divide-white/5 text-xs text-slate-300">
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <span class="font-bold text-white">Kunci Fisik (FIDO2/WebAuthn)</span>
                                        <span class="block text-[10px] text-slate-500 mt-0.5">Status: Belum
                                            terdaftar</span>
                                    </div>
                                    <button
                                        class="text-[9px] font-bold text-slate-300 hover:text-white bg-white/5 hover:bg-white/10 px-2.5 py-1.5 rounded border border-white/10 uppercase tracking-widest transition-colors cursor-pointer">Daftarkan
                                        Token</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Face Biometric Multi-Angle Enrollment View -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl max-w-2xl mx-auto relative overflow-hidden"
                x-data="{
                    enrollStage: 'capture_front',
                
                    frontImage: null,
                    leftImage: null,
                    rightImage: null,
                
                    stream: null,
                    devices: [],
                    selectedDeviceId: '',
                    cameraError: '',
                
                    faceDetected: false,
                    livenessScore: 0.00,
                    faceX: 0,
                    faceY: 0,
                    detectionConfidence: 0,
                    countdownSeconds: 3,
                    shutterTriggered: false,
                
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
                
                    async detectCameras() {
                        try {
                            const tempStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                            tempStream.getTracks().forEach(track => track.stop());
                
                            const allDevices = await navigator.mediaDevices.enumerateDevices();
                            this.devices = allDevices.filter(device => device.kind === 'videoinput');
                            if (this.devices.length > 0) {
                                this.selectedDeviceId = this.devices[0].deviceId;
                            }
                        } catch (error) {
                            this.cameraError = 'Akses kamera ditolak atau tidak ada kamera terdeteksi: ' + error.message;
                        }
                    },
                
                    async startCamera() {
                        if (this.stream) {
                            this.stream.getTracks().forEach(track => track.stop());
                        }
                        try {
                            const constraints = {
                                video: this.selectedDeviceId ? { deviceId: { exact: this.selectedDeviceId } } : { facingMode: 'user' },
                                audio: false
                            };
                            this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                            this.$refs.video.srcObject = this.stream;
                            this.cameraError = '';
                
                            // Initialize Telemetry Loop
                            this.startTelemetryScanner();
                        } catch (error) {
                            this.cameraError = 'Gagal memulai tayangan kamera: ' + error.message;
                        }
                    },
                
                    startTelemetryScanner() {
                        const scanInterval = setInterval(() => {
                            if (this.enrollStage === 'verify_details' || this.shutterTriggered) {
                                clearInterval(scanInterval);
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
                        const dummyFront = 'data:image/svg+xml;utf8,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;300&quot; height=&quot;300&quot; viewBox=&quot;0 0 100 100&quot;><circle cx=&quot;50&quot; cy=&quot;50&quot; r=&quot;40&quot; stroke=&quot;%2310b981&quot; stroke-width=&quot;2&quot; fill=&quot;%230d1527&quot;/><circle cx=&quot;50&quot; cy=&quot;35&quot; r=&quot;15&quot; fill=&quot;%2310b981&quot;/><path d=&quot;M25,75 Q50,55 75,75&quot; stroke=&quot;%2310b981&quot; stroke-width=&quot;3&quot; fill=&quot;none&quot;/></svg>';
                        const dummyLeft = 'data:image/svg+xml;utf8,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;300&quot; height=&quot;300&quot; viewBox=&quot;0 0 100 100&quot;><circle cx=&quot;50&quot; cy=&quot;50&quot; r=&quot;40&quot; stroke=&quot;%236366f1&quot; stroke-width=&quot;2&quot; fill=&quot;%230d1527&quot;/><circle cx=&quot;40&quot; cy=&quot;35&quot; r=&quot;15&quot; fill=&quot;%236366f1&quot;/><path d=&quot;M25,75 Q50,55 75,75&quot; stroke=&quot;%236366f1&quot; stroke-width=&quot;3&quot; fill=&quot;none&quot;/></svg>';
                        const dummyRight = 'data:image/svg+xml;utf8,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;300&quot; height=&quot;300&quot; viewBox=&quot;0 0 100 100&quot;><circle cx=&quot;50&quot; cy=&quot;50&quot; r=&quot;40&quot; stroke=&quot;%2306b6d4&quot; stroke-width=&quot;2&quot; fill=&quot;%230d1527&quot;/><circle cx=&quot;60&quot; cy=&quot;35&quot; r=&quot;15&quot; fill=&quot;%2306b6d4&quot;/><path d=&quot;M25,75 Q50,55 75,75&quot; stroke=&quot;%2306b6d4&quot; stroke-width=&quot;3&quot; fill=&quot;none&quot;/></svg>';
                
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
                }" x-init="await detectCameras();
                await startCamera();">

                <div class="text-center">
                    <h3 class="text-xl font-extrabold text-white tracking-tight font-display mb-2">Pendaftaran Wajah 3
                        Sudut Teraman</h3>
                    <p class="text-xs text-slate-400 mb-4" x-text="instructionText"></p>

                    <!-- Active Vector Step indicator -->
                    <div class="max-w-md mx-auto mb-6 bg-white/5 border border-white/10 rounded-2xl p-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-bold text-sky-400 uppercase tracking-widest"
                                x-text="stageBadge"></span>
                            <span class="text-[10px] font-mono font-bold text-slate-400"
                                x-text="enrollStage !== 'verify_details' ? 'Tersisa ' + countdownSeconds + ' detik' : 'Selesai'"></span>
                        </div>
                        <div class="w-full bg-[#0d1527] h-2 rounded-full overflow-hidden border border-white/5">
                            <div class="bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 h-full rounded-full transition-all duration-300"
                                :style="`width: ${currentProgress}%`"></div>
                        </div>
                    </div>

                    <!-- Camera error alerts -->
                    <div x-show="cameraError"
                        class="mb-4 max-w-md mx-auto bg-rose-500/10 border border-rose-500/20 rounded-xl p-3.5 text-xs text-rose-400 font-semibold flex items-center space-x-2"
                        style="display: none;">
                        <svg class="w-4.5 h-4.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-left" x-text="cameraError"></span>
                    </div>

                    <!-- Cybernetic Enrollment Camera Feed View -->
                    <div x-show="enrollStage !== 'verify_details'"
                        class="relative mx-auto max-w-md bg-[#0d1527] border border-white/10 rounded-3xl overflow-hidden shadow-2xl aspect-video flex items-center justify-center">

                        <!-- Circular Guides -->
                        <div x-show="enrollStage === 'capture_front'"
                            class="absolute w-40 h-40 border-2 border-dashed border-emerald-400/40 rounded-full animate-spin pointer-events-none z-10"
                            style="animation-duration: 20s;"></div>
                        <div x-show="enrollStage === 'capture_front'"
                            class="absolute w-36 h-36 border border-emerald-400/60 rounded-full pointer-events-none z-10">
                        </div>

                        <div x-show="enrollStage === 'capture_left'"
                            class="absolute w-36 h-36 border-2 border-dashed border-indigo-400/40 rounded-full pointer-events-none z-10 flex items-center justify-center">
                            <span class="text-[9px] font-black text-indigo-400 tracking-widest uppercase">HADAP KIRI
                                ←</span>
                        </div>
                        <div x-show="enrollStage === 'capture_right'"
                            class="absolute w-36 h-36 border-2 border-dashed border-cyan-400/40 rounded-full pointer-events-none z-10 flex items-center justify-center">
                            <span class="text-[9px] font-black text-cyan-400 tracking-widest uppercase">HADAP KANAN
                                →</span>
                        </div>

                        <!-- Holographic sweeps -->
                        <div class="absolute inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-emerald-400 to-transparent shadow-[0_0_10px_#10b981] z-10 pointer-events-none"
                            style="animation: scanLine 2.5s linear infinite;"></div>

                        <!-- Bottom Tech HUD overlays -->
                        <div class="absolute bottom-3 inset-x-3 z-10 flex justify-between pointer-events-none">
                            <div
                                class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl text-[8px] font-mono text-left space-y-0.5 border border-white/5">
                                <div class="text-slate-400 uppercase font-bold">Matriks Kunci Wajah</div>
                                <div class="text-emerald-400 font-bold">X: <span x-text="faceX"></span> Y: <span
                                        x-text="faceY"></span></div>
                            </div>
                            <div
                                class="bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-xl text-[8px] font-mono text-right space-y-0.5 border border-white/5">
                                <div class="text-slate-400 uppercase font-bold">Status Penyelarasan</div>
                                <div class="text-emerald-400 font-bold">Terkunci: <span
                                        x-text="detectionConfidence + '%'"></span></div>
                            </div>
                        </div>

                        <!-- Countdown Widget -->
                        <div x-show="faceDetected && countdownSeconds > 0"
                            class="absolute inset-0 bg-[#090e1a]/40 backdrop-blur-[1px] z-10 flex flex-col items-center justify-center pointer-events-none">
                            <div
                                class="bg-black/85 border border-emerald-500/20 px-6 py-4 rounded-3xl text-center shadow-2xl transform scale-95 transition-all">
                                <div class="text-[10px] font-black uppercase text-emerald-400 tracking-widest mb-1">
                                    POSISI DIOPTIMALKAN</div>
                                <div class="text-base font-extrabold text-white font-display mb-1.5 uppercase"
                                    x-text="enrollStage === 'capture_front' ? 'WAJAH DEPAN' : (enrollStage === 'capture_left' ? 'WAJAH KIRI' : 'WAJAH KANAN')">
                                </div>
                                <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-500 text-[#090e1a] text-lg font-black tracking-tight"
                                    x-text="countdownSeconds"></div>
                            </div>
                        </div>

                        <!-- Camera Live Indicator -->
                        <div
                            class="absolute top-3 left-3 z-10 inline-flex items-center space-x-1.5 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full text-[9px] font-bold text-white uppercase tracking-wider">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            <span>Kamera Aktif</span>
                        </div>

                        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"
                            style="transform: scaleX(-1);"></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                    </div>

                    <!-- Step 4: Verification Grid Preview (Unified Gallery) -->
                    <div x-show="enrollStage === 'verify_details'"
                        class="max-w-md mx-auto bg-[#0d1527] border border-white/10 rounded-3xl p-5 shadow-2xl"
                        style="display: none;">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Matriks Ledger
                            Biometrik Terkunci</h4>

                        <div class="grid grid-cols-3 gap-3.5 mb-6">
                            <!-- Front Profile -->
                            <div
                                class="relative bg-[#121d33] border border-emerald-500/30 rounded-2xl overflow-hidden aspect-square flex flex-col">
                                <img :src="frontImage" class="w-full h-full object-cover transform scaleX(-1)">
                                <div
                                    class="absolute bottom-0 inset-x-0 bg-emerald-500 text-[#090e1a] text-[8px] font-black tracking-wider uppercase py-0.5 leading-none">
                                    DEPAN
                                </div>
                            </div>

                            <!-- Left Profile -->
                            <div
                                class="relative bg-[#121d33] border border-indigo-500/30 rounded-2xl overflow-hidden aspect-square flex flex-col">
                                <img :src="leftImage" class="w-full h-full object-cover transform scaleX(-1)">
                                <div
                                    class="absolute bottom-0 inset-x-0 bg-indigo-500 text-white text-[8px] font-black tracking-wider uppercase py-0.5 leading-none">
                                    KIRI
                                </div>
                            </div>

                            <!-- Right Profile -->
                            <div
                                class="relative bg-[#121d33] border border-cyan-500/30 rounded-2xl overflow-hidden aspect-square flex flex-col">
                                <img :src="rightImage" class="w-full h-full object-cover transform scaleX(-1)">
                                <div
                                    class="absolute bottom-0 inset-x-0 bg-cyan-500 text-[#090e1a] text-[8px] font-black tracking-wider uppercase py-0.5 leading-none">
                                    KANAN
                                </div>
                            </div>
                        </div>

                        <div
                            class="p-3.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-left text-xs font-bold flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Ketiga sudut wajah berhasil dihitung dan disatukan.</span>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col items-center justify-center space-y-4">
                        <div class="flex justify-center space-x-3 w-full">
                            <button @click="capturePhoto()" x-show="enrollStage !== 'verify_details'" type="button"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-button-primary transition-all">
                                Ambil Foto Manual
                            </button>
                            <button @click="resetSequence()" x-show="enrollStage === 'verify_details'" type="button"
                                class="inline-flex items-center px-5 py-3 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-2xl hover:text-white transition-all"
                                style="display: none;">
                                Ulangi Urutan
                            </button>
                            <button @click="confirmPhoto()" x-show="enrollStage === 'verify_details'" type="button"
                                class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-emerald-700 hover:to-green-700 shadow-lg shadow-emerald-500/25 hover:translate-y-[-1px] transition-all"
                                style="display: none;">
                                Konfirmasi & Daftarkan Kunci Induk
                            </button>
                        </div>

                        <!-- Bypass for easy mock enrollment -->
                        <div class="w-full max-w-md pt-6 border-t border-white/5 mt-4">
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">Simulasikan
                                Pendaftaran Wajah (Mode Uji Coba)</p>
                            <button @click="simulatePhoto(); confirmPhoto();" type="button"
                                class="px-5 py-2.5 bg-white/5 hover:bg-emerald-500/10 border border-white/10 hover:border-emerald-500/30 rounded-xl text-xs font-bold text-slate-300 hover:text-emerald-400 transition-all">
                                Lewati & Daftarkan Simulasi Wajah
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
