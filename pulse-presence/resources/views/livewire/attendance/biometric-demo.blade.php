<div class="py-8 min-h-screen text-slate-100 bg-transparent" x-data="{
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
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-blue-400 transition-colors duration-150 mb-3">
                    <svg class="w-4.5 h-4.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Dasbor
                </a>
                <h1 class="text-3xl font-extrabold text-white tracking-tight font-display flex items-center">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 mr-3 animate-pulse shadow-[0_0_8px_#22d3ee]"></span>
                    Lab Kalibrasi Live Absensi Wajah
                </h1>
                <p class="mt-1 text-sm text-slate-400 font-medium">Uji coba kecocokan wajah real-time server-side menggunakan OpenCV Engine</p>
            </div>
            
            <div class="flex items-center gap-3">
                <button @click="cameraActive ? stopCamera() : initCamera()" type="button"
                    class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest border transition-all"
                    :class="cameraActive ? 'bg-rose-500/10 border-rose-500/30 text-rose-400 hover:bg-rose-500/20' : 'bg-cyan-500/10 border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20'">
                    <span x-text="cameraActive ? 'Matikan Kamera' : 'Aktifkan Kamera'"></span>
                </button>
            </div>
        </div>

        <div x-show="errorMessage" class="mb-6 p-4.5 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-xs font-bold flex items-center shadow-lg animate-shake" style="display: none;">
            <svg class="w-5 h-5 mr-2.5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span x-text="errorMessage"></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Side: Live Feed -->
            <div class="lg:col-span-7 space-y-6">
                <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#0d1527]/55 shadow-2xl relative">
                    <!-- Scanner Header -->
                    <div class="px-5 py-4 border-b border-white/5 bg-[#121d33]/40 flex items-center justify-between">
                        <span class="text-xs font-black tracking-widest text-blue-400 uppercase flex items-center">
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 mr-2 animate-ping"></span>
                            VIDEO LIVE SCANNER UMPAN AKTIF
                        </span>
                        <span class="text-[10px] bg-blue-500/10 border border-blue-500/20 text-blue-400 font-extrabold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            FPS: ~15
                        </span>
                    </div>

                    <!-- Camera Feed Canvas -->
                    <div class="relative bg-slate-950 aspect-video flex items-center justify-center overflow-hidden">
                        <video x-ref="video" autoplay playsinline muted class="w-full h-full object-cover transform -scale-x-100" :class="cameraActive ? '' : 'hidden'"></video>
                        
                        <!-- Camera Placeholder -->
                        <div x-show="!cameraActive" class="absolute inset-0 flex flex-col items-center justify-center text-center p-6 space-y-3">
                            <div class="w-16 h-16 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-500">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Kamera Nonaktif</h4>
                                <p class="text-xs text-slate-500 max-w-xs mt-1">Nyalakan kamera untuk memulai analisis biometrik live wajah</p>
                            </div>
                        </div>

                        <!-- Holographic Target Grid (Always Active on camera) -->
                        <div x-show="cameraActive" class="absolute inset-0 border-[3px] border-cyan-500/20 pointer-events-none z-10 m-6 rounded-2xl flex items-center justify-center">
                            <!-- Glowing Scanner line -->
                            <div class="absolute inset-x-0 h-[3px] bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-scan z-20"></div>
                            
                            <!-- Bounding corners -->
                            <div class="absolute top-0 left-0 w-8 h-8 border-t-[3px] border-l-[3px] border-cyan-400 rounded-tl-md"></div>
                            <div class="absolute top-0 right-0 w-8 h-8 border-t-[3px] border-r-[3px] border-cyan-400 rounded-tr-md"></div>
                            <div class="absolute bottom-0 left-0 w-8 h-8 border-b-[3px] border-l-[3px] border-cyan-400 rounded-bl-md"></div>
                            <div class="absolute bottom-0 right-0 w-8 h-8 border-b-[3px] border-r-[3px] border-cyan-400 rounded-br-md"></div>
                        </div>
                    </div>
                </div>

                <!-- Control Panel Card -->
                <div class="bg-[#0d1527]/55 border border-white/10 rounded-2xl p-5 shadow-xl space-y-4">
                    <h3 class="text-sm font-black uppercase text-slate-400 tracking-wider">PILIH PROFIL KALIBRASI</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 tracking-widest mb-1.5">Karyawan Target</label>
                            <select wire:model.live="selectedUserId" class="w-full bg-[#121d33]/50 border border-white/10 rounded-xl px-3 py-2 text-xs font-bold text-slate-200 focus:outline-none focus:border-cyan-500 transition-all">
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} (ID: {{ $u->id }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 tracking-widest mb-1.5">Ambang Batas Keaktifan</label>
                            <div class="w-full bg-[#121d33]/50 border border-white/10 rounded-xl px-3 py-2 text-xs font-mono font-bold text-slate-300">
                                {{ round((float) cache()->get('settings.biometric_liveness_threshold', 0.95) * 100.0, 1) }}% (Admin Settings)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Telemetry Target & Matching -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Matching Telemetry Matrix -->
                <div class="bg-[#0d1527]/55 border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black tracking-widest text-blue-400 uppercase">MATRIX TELEMETRI WIDGET</span>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full inline-block" :class="isScanning ? 'bg-emerald-500 animate-ping' : 'bg-slate-600'"></span>
                            <span class="text-[9px] font-mono text-slate-400" x-text="isScanning ? 'SCANNING' : 'STOPPED'"></span>
                        </div>
                    </div>

                    <!-- Comparison Visuals -->
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Master Face Induk -->
                        <div class="text-center space-y-2">
                            <span class="block text-[9px] font-black uppercase tracking-wider text-slate-500">Kunci Induk Wajah</span>
                            <div class="aspect-square bg-slate-900 border border-white/5 rounded-2xl overflow-hidden flex items-center justify-center relative shadow-inner">
                                @if($selectedUser && $selectedUser->hasRegisteredFace())
                                    <img src="{{ $selectedUser->getMasterFaceUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-slate-600 text-xs font-semibold">Tidak Ada Wajah</div>
                                @endif
                            </div>
                            <span class="block text-[10px] font-bold text-slate-300 truncate">{{ $selectedUser ? $selectedUser->name : 'N/A' }}</span>
                        </div>

                        <!-- Matching Status Badge -->
                        <div class="flex flex-col items-center justify-center text-center space-y-3">
                            <span class="block text-[9px] font-black uppercase tracking-wider text-slate-500">Hasil Evaluasi</span>
                            
                            <div x-show="!cameraActive" class="w-28 h-28 rounded-full border border-white/5 bg-white/5 flex items-center justify-center text-slate-600 font-mono text-xs">
                                IDLE
                            </div>
                            <div x-show="cameraActive && $wire.verified" class="w-28 h-28 rounded-full border-2 border-emerald-500/30 bg-emerald-500/10 flex flex-col items-center justify-center text-emerald-400 font-bold font-display shadow-[0_0_20px_rgba(16,185,129,0.2)] animate-pulse" style="display: none;">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs font-black tracking-widest uppercase">COCOK</span>
                                <span class="text-[9px] font-mono opacity-80 mt-0.5">VERIFIED</span>
                            </div>
                            <div x-show="cameraActive && !$wire.verified" class="w-28 h-28 rounded-full border-2 border-rose-500/30 bg-rose-500/10 flex flex-col items-center justify-center text-rose-400 font-bold font-display shadow-[0_0_20px_rgba(244,63,94,0.2)]" style="display: none;">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-xs font-black tracking-widest uppercase">TOLAK</span>
                                <span class="text-[9px] font-mono opacity-80 mt-0.5">DENIED</span>
                            </div>
                        </div>
                    </div>

                    <!-- Similarity Glow Bar -->
                    <div class="space-y-2 pt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] font-black tracking-wider text-slate-500 uppercase">Tingkat Kemiripan</span>
                            <span class="text-xs font-mono font-extrabold" :class="$wire.verified ? 'text-emerald-400' : 'text-rose-400'">
                                {{ $similarity }}%
                            </span>
                        </div>
                        <div class="w-full bg-[#121d33]/50 rounded-full h-3 border border-white/5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-300"
                                :class="$wire.verified ? 'bg-gradient-to-r from-emerald-500 to-teal-400' : 'bg-gradient-to-r from-rose-500 to-amber-500'"
                                style="width: {{ $similarity }}%"></div>
                        </div>
                    </div>

                    <!-- Diagnostics Logs -->
                    <div class="border-t border-white/5 pt-4 space-y-2 text-[10px] font-mono">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Status Mesin:</span>
                            <span class="font-bold text-slate-300">{{ $statusMessage }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Nilai Jarak (Distance):</span>
                            <span class="font-bold text-slate-300">{{ $distance }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Waktu Latensi Eksekusi:</span>
                            <span class="font-bold text-slate-300">{{ $scanLatency }} ms</span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500">Pemindaian Terakhir:</span>
                            <span class="font-bold text-slate-300">{{ $lastScanTime ?? 'Belum ada' }}</span>
                        </div>
                    </div>

                </div>
            </div>
            
        </div>

    </div>

</div>
