<div class="py-8 min-h-screen">
    <div class="px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="heading-1">Profil Karyawan</h1>
                <p class="mt-1 label-sm">Kelola kredensial keamanan Anda, daftarkan biometrik tepercaya, dan verifikasi kantor cabang penempatan.</p>
            </div>
            @if ($step === 'enroll')
                <button wire:click="$set('step', 'overview')" class="btn-secondary btn-sm w-full md:w-auto">Batalkan Pendaftaran</button>
            @endif
        </div>

        @if (session()->has('success'))
            <div class="mb-6 flex items-center gap-2 rounded-xl border border-success/30 bg-success-soft p-4 text-sm font-medium text-success">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        @if ($step === 'overview')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Identity card --}}
                <div class="card overflow-hidden lg:col-span-1">
                    <div class="h-20 bg-primary/10"></div>
                    <div class="px-6 pb-6 -mt-10 text-center">
                        {{-- Avatar --}}
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            <div class="relative h-full w-full overflow-hidden rounded-3xl border-4 border-surface bg-primary text-primary-fg flex items-center justify-center text-3xl font-semibold uppercase">
                                @if (auth()->user()->avatar)
                                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-full h-full object-cover" alt="Foto profil">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                @endif
                                <div wire:loading wire:target="photo" class="absolute inset-0 bg-black/60 flex items-center justify-center">
                                    <svg class="w-6 h-6 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </div>
                            </div>
                            <label class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border-2 border-surface bg-primary text-primary-fg hover:bg-[var(--primary-hover)] transition-colors" title="Ubah foto profil">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <input type="file" wire:model="photo" accept="image/*" class="hidden">
                            </label>
                        </div>

                        @error('photo') <p class="text-xs text-danger mb-2">{{ $message }}</p> @enderror

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
                            <button wire:click="deletePhoto" wire:confirm="Hapus foto profil Anda?" class="mt-2 text-xs font-medium text-danger hover:opacity-80 transition-opacity">Hapus foto profil</button>
                        @endif

                        <div class="mt-6 pt-5 border-t border-border space-y-4 text-left">
                            @php
                                $rows = [
                                    ['ID Karyawan', '#' . (auth()->user()->employee_id ?? ('PP-' . str_pad(auth()->user()->id ?? 1, 5, '0', STR_PAD_LEFT))), 'font-mono'],
                                    ['Email', strtolower(auth()->user()->email ?? '-'), 'break-all'],
                                    ['Kantor Cabang', auth()->user()->branch->name ?? 'HQ Sudirman', 'text-primary'],
                                    ['Mode Kerja', ucfirst(auth()->user()->work_mode ?? 'office'), ''],
                                ];
                                if (auth()->user()->joined_at) {
                                    $rows[] = ['Bergabung', \Carbon\Carbon::parse(auth()->user()->joined_at)->translatedFormat('d M Y'), ''];
                                }
                            @endphp
                            @foreach ($rows as [$k, $v, $cls])
                                <div class="flex items-center justify-between gap-3">
                                    <span class="label-xs uppercase tracking-wide flex-shrink-0">{{ $k }}</span>
                                    <span class="label-sm font-medium text-right {{ $cls ?: 'text-fg' }}">{{ $v }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Sign out (also gives mobile users a logout entry point) --}}
                        <form method="POST" action="{{ route('logout') }}" class="mt-6 pt-5 border-t border-border">
                            @csrf
                            <button type="submit" class="btn-danger-outline btn-sm w-full">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Keluar Sesi
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Biometrics workspace --}}
                <div class="card p-6 sm:p-8 lg:col-span-2">
                    <h3 class="heading-3 mb-6">Verifikasi Wajah</h3>
                    <div class="rounded-xl border border-border bg-surface-muted p-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="space-y-3.5">
                                <div class="flex items-center gap-2">
                                    @if (auth()->user()->hasRegisteredFace())
                                        <span class="h-2.5 w-2.5 rounded-full bg-success"></span>
                                        <span class="badge-success">Data Wajah Terdaftar</span>
                                    @else
                                        <span class="h-2.5 w-2.5 rounded-full bg-danger"></span>
                                        <span class="badge-danger">Wajah Belum Terdaftar</span>
                                    @endif
                                </div>
                                <h4 class="label-lg">Informasi Wajah</h4>
                                <p class="label-sm max-w-md leading-relaxed">Data wajah Anda digunakan untuk memverifikasi kehadiran (absen) harian Anda di kantor. Pastikan foto wajah yang didaftarkan terlihat jelas.</p>

                                <div class="pt-2 flex flex-wrap gap-2.5">
                                    <button wire:click="$set('step', 'enroll')" class="btn-primary btn-sm">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ auth()->user()->hasRegisteredFace() ? 'Daftar Ulang Multi-Sudut' : 'Daftarkan Kunci Wajah' }}
                                    </button>
                                    @if (auth()->user()->hasRegisteredFace())
                                        <button wire:click="deleteFace" wire:confirm="Apakah Anda yakin ingin menghapus biometrik wajah Anda? Anda tidak akan dapat absen sebelum melakukan pendaftaran ulang." class="btn-danger-outline btn-sm">Hapus Otorisasi Kunci</button>
                                    @endif
                                </div>
                            </div>

                            <div class="flex-shrink-0 mx-auto md:mx-0">
                                <div class="relative flex h-28 w-28 items-center justify-center overflow-hidden rounded-xl border border-border bg-surface">
                                    @if (auth()->user()->hasRegisteredFace())
                                        <img src="{{ auth()->user()->getMasterFaceUrl() }}" class="w-full h-full object-cover" style="transform: scaleX(-1);">
                                        <div class="absolute bottom-1 right-1 rounded-full bg-success p-1 text-white">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.28 7.22a.75.75 0 010 1.06l-6.25 6.25a.75.75 0 01-1.06 0L5.22 10.78a.75.75 0 011.06-1.06l3.47 3.47 5.72-5.72a.75.75 0 011.06 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                    @else
                                        <div class="text-center text-fg-subtle p-3">
                                            <svg class="w-8 h-8 mx-auto mb-1.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                            <span class="label-xs block">Tanpa Foto</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Face enrollment wizard --}}
            <div class="card p-6 sm:p-8 max-w-2xl mx-auto"
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

                    {{-- Header --}}
                    <div class="mb-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-primary text-primary-fg mx-auto mb-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="heading-3">Pendaftaran Kunci Wajah</h3>
                        <p class="label-sm mt-1.5 leading-relaxed" x-text="instructionText"></p>
                    </div>

                    {{-- Step progress --}}
                    <div class="mb-6 rounded-xl border border-border bg-surface-muted p-4">
                        <div class="flex justify-between items-center mb-4">
                            <span class="label-xs uppercase tracking-wide text-primary" x-text="stageBadge"></span>
                            <span class="rounded-md border border-border bg-surface px-2 py-0.5 text-xs font-mono text-fg-muted" x-text="enrollStage !== 'verify_details' ? 'Tersisa ' + countdownSeconds + 'd' : 'Selesai'"></span>
                        </div>

                        <div class="flex items-start justify-between px-3 relative">
                            <div class="absolute top-[14px] left-[30px] right-[30px] h-[2px] bg-border rounded-full overflow-hidden z-0">
                                <div class="absolute left-0 top-0 h-full bg-primary transition-all duration-500 rounded-full" :style="`width: ${currentProgress}%`"></div>
                            </div>

                            @php
                                $nodes = [
                                    ['capture_front', 'frontImage', '1', 'Depan'],
                                    ['capture_left', 'leftImage', '2', 'Kiri'],
                                    ['capture_right', 'rightImage', '3', 'Kanan'],
                                ];
                            @endphp
                            @foreach ($nodes as [$stage, $img, $num, $lbl])
                                <div class="flex flex-col items-center z-10">
                                    <div class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold border-2 transition-all duration-300"
                                         :class="enrollStage === '{{ $stage }}' ? 'bg-primary border-primary text-primary-fg' : ({{ $img }} ? 'bg-primary border-primary text-primary-fg' : 'border-border text-fg-subtle')">
                                        <span x-text="{{ $img }} ? '✓' : '{{ $num }}'"></span>
                                    </div>
                                    <span class="text-xs font-medium mt-1.5" :class="enrollStage === '{{ $stage }}' || {{ $img }} ? 'text-primary' : 'text-fg-subtle'">{{ $lbl }}</span>
                                </div>
                            @endforeach
                            <div class="flex flex-col items-center z-10">
                                <div class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold border-2 transition-all duration-300" :class="enrollStage === 'verify_details' ? 'bg-primary border-primary text-primary-fg' : 'border-border text-fg-subtle'">
                                    <span x-text="enrollStage === 'verify_details' ? '✓' : '4'"></span>
                                </div>
                                <span class="text-xs font-medium mt-1.5" :class="enrollStage === 'verify_details' ? 'text-primary' : 'text-fg-subtle'">Selesai</span>
                            </div>
                        </div>
                    </div>

                    {{-- Camera switcher --}}
                    <div class="mb-4" x-show="devices.length > 1" x-cloak>
                        <select x-model="selectedDeviceId" @change="switchCamera()" class="cursor-pointer">
                            <template x-for="device in devices" :key="device.deviceId">
                                <option :value="device.deviceId" x-text="device.label || 'Kamera ' + (devices.indexOf(device) + 1)"></option>
                            </template>
                        </select>
                    </div>

                    {{-- Camera error panel --}}
                    <div x-show="cameraError" x-cloak class="mb-6 rounded-xl border border-danger/30 bg-danger-soft p-5 text-xs text-fg-muted flex flex-col gap-4 text-left">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-danger-soft border border-danger/30 text-danger">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <div>
                                <h4 class="label-md">Kamera Tidak Dapat Diakses</h4>
                                <p class="text-xs text-danger mt-0.5 leading-relaxed" x-text="cameraError"></p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-border bg-surface p-3.5 space-y-2">
                            <span class="label-xs uppercase tracking-wide block mb-2">Diagnostik Sistem</span>
                            <div class="grid grid-cols-2 gap-x-4 gap-y-1.5 text-xs">
                                <div class="flex items-center gap-1.5"><span class="text-fg-subtle">Secure Context:</span><span class="font-medium" :class="window.isSecureContext ? 'text-primary' : 'text-danger'" x-text="window.isSecureContext ? '✓ Ya' : '✗ Tidak'"></span></div>
                                <div class="flex items-center gap-1.5"><span class="text-fg-subtle">Media API:</span><span class="font-medium" :class="navigator.mediaDevices ? 'text-primary' : 'text-danger'" x-text="navigator.mediaDevices ? '✓ Ada' : '✗ Tidak'"></span></div>
                                <div class="flex items-center gap-1.5"><span class="text-fg-subtle">Protocol:</span><span class="font-medium text-primary" x-text="location.protocol"></span></div>
                                <div class="flex items-center gap-1.5"><span class="text-fg-subtle">Host:</span><span class="font-medium text-primary" x-text="location.hostname"></span></div>
                            </div>
                        </div>

                        <div x-show="permissionDenied" x-cloak class="rounded-lg border border-border bg-surface p-3.5 space-y-1.5 text-xs text-fg-muted leading-relaxed">
                            <span class="label-xs uppercase tracking-wide text-primary block mb-1">Cara Reset Izin Kamera</span>
                            <p>1. Klik ikon <b class="text-fg">gembok</b> di kiri URL bar</p>
                            <p>2. Pilih <b class="text-fg">Site settings</b> atau <b class="text-fg">Izin</b></p>
                            <p>3. Ubah Camera dari Block menjadi <b class="text-primary">Allow</b></p>
                            <p>4. Refresh halaman (Ctrl+R)</p>
                        </div>

                        <div x-data="{ retrying: false }">
                            <button @click="retrying = true; await requestCameraAccess(); retrying = false;" type="button" :disabled="retrying" class="btn-danger w-full">
                                <span x-show="!retrying">Coba Ajukan Izin Lagi</span>
                                <span x-show="retrying" x-cloak>Meminta akses…</span>
                            </button>
                        </div>
                    </div>

                    {{-- Camera viewport (intentionally dark) --}}
                    <div x-show="enrollStage !== 'verify_details'" class="relative mx-auto w-72 h-72 overflow-hidden rounded-full border-2 border-border bg-slate-950 flex items-center justify-center">
                        <div class="absolute inset-2 border border-dashed border-white/20 rounded-full animate-spin pointer-events-none z-10" style="animation-duration: 30s;"></div>
                        <div class="absolute inset-5 border border-white/15 rounded-full pointer-events-none z-10"></div>

                        <div x-show="enrollStage === 'capture_left'" x-cloak class="absolute inset-x-10 top-1/2 -translate-y-1/2 rounded-full bg-primary px-3 py-1.5 pointer-events-none z-10 text-xs font-medium text-primary-fg tracking-wider animate-bounce">← Hadap Kiri</div>
                        <div x-show="enrollStage === 'capture_right'" x-cloak class="absolute inset-x-10 top-1/2 -translate-y-1/2 rounded-full bg-primary px-3 py-1.5 pointer-events-none z-10 text-xs font-medium text-primary-fg tracking-wider animate-bounce">Hadap Kanan →</div>

                        <div class="absolute inset-x-0 h-[2px] bg-gradient-to-r from-transparent via-white/60 to-transparent z-10 pointer-events-none" style="animation: scanLine 2s linear infinite;"></div>

                        <div x-show="faceDetected && countdownSeconds > 0" x-cloak class="absolute inset-0 bg-slate-950/80 z-20 flex flex-col items-center justify-center pointer-events-none">
                            <div class="text-xs font-medium text-white mb-1 tracking-widest">Deteksi Kunci</div>
                            <div class="text-5xl font-semibold text-white" x-text="countdownSeconds"></div>
                            <div class="text-xs text-slate-400 mt-1">Tahan Posisi</div>
                        </div>

                        <div class="absolute bottom-5 z-10 flex items-center gap-1.5 rounded-full bg-black/60 border border-white/10 px-3 py-1 text-xs font-medium text-white">
                            <span class="h-1.5 w-1.5 rounded-full bg-white animate-ping"></span>
                            Live
                        </div>

                        <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover rounded-full" style="transform: scaleX(-1);"></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                    </div>

                    {{-- Verification gallery --}}
                    <div x-show="enrollStage === 'verify_details'" x-cloak class="rounded-xl border border-border bg-surface-muted p-5">
                        <h4 class="label-xs uppercase tracking-wide mb-4 text-left">Hasil Foto Wajah</h4>
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            @foreach (['frontImage' => 'Depan', 'leftImage' => 'Kiri', 'rightImage' => 'Kanan'] as $imgVar => $lbl)
                                <div class="relative overflow-hidden rounded-lg border border-border bg-surface aspect-square">
                                    <img :src="{{ $imgVar }}" class="w-full h-full object-cover" style="transform: scaleX(-1)">
                                    <div class="absolute bottom-0 inset-x-0 bg-primary text-primary-fg text-xs font-medium py-0.5 text-center">{{ $lbl }}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-2.5 rounded-lg border border-info/20 bg-info-soft p-3 text-xs font-medium text-info">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Ketiga foto berhasil diambil dan siap disimpan.
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-6 space-y-3 w-full">
                        <button @click="capturePhoto()" x-show="enrollStage !== 'verify_details'" type="button" class="btn-primary btn-lg w-full">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Ambil Foto
                        </button>

                        <div x-show="enrollStage === 'verify_details'" x-cloak class="flex gap-3">
                            <button @click="resetSequence()" type="button" class="btn-secondary btn-lg flex-1">Ulangi</button>
                            <button @click="confirmPhoto()" type="button" class="btn-primary btn-lg flex-1">Simpan Kunci Wajah</button>
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
</style>
