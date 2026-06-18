<div class="py-8 min-h-screen" x-data="{
    stream: null,
    cameraActive: false,
    errorMessage: '',
    scanInterval: null,
    isScanning: false,

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
            this.stream = await navigator.mediaDevices.getUserMedia(constraints);
            this.$refs.video.srcObject = this.stream;
            this.cameraActive = true;
            this.startScanning();
        } catch (err) {
            console.error('Camera access failed:', err);
            this.errorMessage = 'Gagal mengakses kamera. Pastikan izin kamera telah diberikan di browser.';
        }
    },

    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
        this.cameraActive = false;
        this.stopScanning();
    },

    startScanning() {
        if (this.scanInterval) clearInterval(this.scanInterval);
        this.isScanning = true;
        this.scanInterval = setInterval(() => {
            this.captureFrame();
        }, 1500);
    },

    stopScanning() {
        if (this.scanInterval) {
            clearInterval(this.scanInterval);
            this.scanInterval = null;
        }
        this.isScanning = false;
    },

    captureFrame() {
        if (!this.cameraActive) return;
        const video = this.$refs.video;
        const canvas = document.createElement('canvas');
        canvas.width = 320;
        canvas.height = 240;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        const base64Data = canvas.toDataURL('image/jpeg', 0.85);
        $wire.compareLiveFace(base64Data);
    }
}" x-init="initCamera()" x-on:destroy="stopCamera()">

    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
            <div>
                <a href="{{ route('dashboard') }}" class="btn-secondary btn-sm mb-3">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Kembali ke Dasbor
                </a>
                <h1 class="heading-1 flex items-center gap-2.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-info"></span>
                    Lab Kalibrasi Live Absensi Wajah
                </h1>
                <p class="label-sm mt-1">Uji coba kecocokan wajah real-time server-side menggunakan OpenCV Engine.</p>
            </div>
            <button @click="cameraActive ? stopCamera() : initCamera()" type="button"
                class="btn-sm"
                :class="cameraActive ? 'btn-danger-outline' : 'btn-primary'">
                <span x-text="cameraActive ? 'Matikan Kamera' : 'Aktifkan Kamera'"></span>
            </button>
        </div>

        <div x-show="errorMessage" x-cloak class="mb-6 flex items-center gap-2.5 rounded-xl border border-danger/30 bg-danger-soft p-4 text-sm font-medium text-danger">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            <span x-text="errorMessage"></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Live feed --}}
            <div class="lg:col-span-7 space-y-6">
                <div class="card overflow-hidden">
                    <div class="px-5 py-4 border-b border-border bg-surface-muted flex items-center justify-between">
                        <span class="label-xs uppercase tracking-wide text-primary flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-info animate-ping"></span>
                            Video Live Scanner Aktif
                        </span>
                        <span class="badge-info">FPS ~15</span>
                    </div>

                    {{-- Camera viewport (intentionally dark) --}}
                    <div class="relative bg-slate-950 aspect-video flex items-center justify-center overflow-hidden">
                        <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover transform -scale-x-100" :class="cameraActive ? '' : 'hidden'"></video>

                        <div x-show="!cameraActive" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 space-y-3">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-white/5 border border-white/10 text-slate-400">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-white">Kamera nonaktif</h4>
                                <p class="text-xs text-slate-400 max-w-xs mt-1">Nyalakan kamera untuk memulai analisis biometrik live wajah.</p>
                            </div>
                        </div>

                        <div x-show="cameraActive" x-cloak class="absolute inset-6 rounded-2xl border-2 border-white/20 pointer-events-none z-10">
                            <div class="absolute top-0 left-0 h-7 w-7 border-t-2 border-l-2 border-white/70 rounded-tl-md"></div>
                            <div class="absolute top-0 right-0 h-7 w-7 border-t-2 border-r-2 border-white/70 rounded-tr-md"></div>
                            <div class="absolute bottom-0 left-0 h-7 w-7 border-b-2 border-l-2 border-white/70 rounded-bl-md"></div>
                            <div class="absolute bottom-0 right-0 h-7 w-7 border-b-2 border-r-2 border-white/70 rounded-br-md"></div>
                        </div>
                    </div>
                </div>

                {{-- Control panel --}}
                <div class="card p-5 space-y-4">
                    <h3 class="label-xs uppercase tracking-wide">Pilih Profil Kalibrasi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="label">Karyawan Target</label>
                            <select wire:model.live="selectedUserId">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} (ID: {{ $u->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Ambang Batas Keaktifan</label>
                            <div class="flex h-10 items-center rounded-lg border border-border bg-surface-muted px-3 text-sm font-mono text-fg-muted">
                                {{ round((float) cache()->get('settings.biometric_liveness_threshold', 0.95) * 100.0, 1) }}% (Admin Settings)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Telemetry --}}
            <div class="lg:col-span-5 space-y-6">
                <div class="card p-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="label-xs uppercase tracking-wide text-primary">Matrix Telemetri</span>
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full inline-block" :class="isScanning ? 'bg-success animate-ping' : 'bg-fg-subtle'"></span>
                            <span class="text-xs font-mono text-fg-muted" x-text="isScanning ? 'SCANNING' : 'STOPPED'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Master face --}}
                        <div class="text-center space-y-2">
                            <span class="label-xs uppercase tracking-wide block">Kunci Induk Wajah</span>
                            <div class="aspect-square bg-surface-muted border border-border rounded-xl overflow-hidden flex items-center justify-center">
                                @if($selectedUser && $selectedUser->hasRegisteredFace())
                                    <img src="{{ $selectedUser->getMasterFaceUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-fg-subtle text-xs font-medium">Tidak ada wajah</div>
                                @endif
                            </div>
                            <span class="block text-xs font-medium text-fg truncate">{{ $selectedUser ? $selectedUser->name : 'N/A' }}</span>
                        </div>

                        {{-- Result --}}
                        <div class="flex flex-col items-center justify-center text-center space-y-3">
                            <span class="label-xs uppercase tracking-wide block">Hasil Evaluasi</span>
                            <div x-show="!cameraActive" class="flex h-28 w-28 items-center justify-center rounded-full border border-border bg-surface-muted text-fg-subtle font-mono text-xs">IDLE</div>
                            <div x-show="cameraActive && $wire.verified" x-cloak class="flex h-28 w-28 flex-col items-center justify-center rounded-full border-2 border-success/30 bg-success-soft text-success">
                                <svg class="h-8 w-8 mb-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-xs font-semibold tracking-wide uppercase">Cocok</span>
                            </div>
                            <div x-show="cameraActive && !$wire.verified" x-cloak class="flex h-28 w-28 flex-col items-center justify-center rounded-full border-2 border-danger/30 bg-danger-soft text-danger">
                                <svg class="h-8 w-8 mb-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-xs font-semibold tracking-wide uppercase">Tolak</span>
                            </div>
                        </div>
                    </div>

                    {{-- Similarity bar --}}
                    <div class="space-y-2 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="label-xs uppercase tracking-wide">Tingkat Kemiripan</span>
                            <span class="text-sm font-mono font-semibold" :class="$wire.verified ? 'text-success' : 'text-danger'">{{ $similarity }}%</span>
                        </div>
                        <div class="w-full bg-surface-muted rounded-full h-2.5 border border-border overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300" :class="$wire.verified ? 'bg-success' : 'bg-danger'" style="width: {{ $similarity }}%"></div>
                        </div>
                    </div>

                    {{-- Diagnostics --}}
                    <div class="border-t border-border pt-4 space-y-1 text-xs font-mono">
                        @foreach ([
                            ['Status Mesin', $statusMessage],
                            ['Nilai Jarak (Distance)', $distance],
                            ['Waktu Latensi Eksekusi', $scanLatency . ' ms'],
                            ['Pemindaian Terakhir', $lastScanTime ?? 'Belum ada'],
                        ] as [$k, $v])
                            <div class="flex justify-between items-center py-1">
                                <span class="text-fg-muted">{{ $k }}:</span>
                                <span class="font-medium text-fg">{{ $v }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
