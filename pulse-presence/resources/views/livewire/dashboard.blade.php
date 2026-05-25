<div class="py-8 min-h-screen text-slate-100 bg-transparent" 
     x-data="{ 
         selectedLog: null, 
         showModal: false,
         toast: { show: false, message: '', type: 'success' },
         showToast(message, type = 'success') {
             this.toast.message = message;
             this.toast.type = type;
             this.toast.show = true;
             setTimeout(() => { this.toast.show = false; }, 5000);
         }
     }"
     @attendance-updated.window="
         const eventDetail = $event.detail[0] || {};
         const eventType = eventDetail.type === 'checkin' ? 'Masuk' : 'Keluar';
         const isAdmin = {{ auth()->user()->hasAnyRole(['super_admin', 'hr_admin']) ? 'true' : 'false' }};
         if (isAdmin) {
             showToast('Log Absensi Baru: Seseorang baru saja melakukan Absen ' + eventType + '!', 'info');
         } else {
             showToast('Absensi berhasil terverifikasi real-time!', 'success');
         }
     "
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Welcome banner with a gorgeous custom glassmorphic slate background -->
        <div class="relative overflow-hidden bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 mb-8 shadow-2xl">
            <div class="absolute top-[-50%] right-[-10%] w-[350px] h-[350px] rounded-full bg-blue-500/10 blur-[80px] pointer-events-none animate-pulse-subtle"></div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex items-center space-x-2 bg-blue-500/10 border border-blue-500/20 px-2.5 py-1 rounded-full mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-400 animate-ping"></span>
                        <span class="text-[10px] font-bold text-sky-400 uppercase tracking-wider">Workspace Aktif</span>
                    </span>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Selamat datang kembali, {{ auth()->user()->name }}</h1>
                    <p class="mt-1 text-sm text-slate-400 font-medium">Ruang Karyawan: {{ auth()->user()->branch->name ?? 'HQ Workspace' }} • {{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-xs font-bold text-slate-300 hover:text-white hover:border-white/20 shadow-sm transition-all duration-200">
                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Kehadiran
                    </a>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-8 p-5 bg-gradient-to-r from-emerald-500/10 via-[#121d33]/80 to-blue-500/10 border border-white/10 text-slate-100 rounded-3xl shadow-2xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white text-xl shadow-lg shadow-emerald-500/10">
                        ⚡
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white leading-tight">Absensi Berhasil Terverifikasi!</h4>
                        <p class="text-xs text-slate-400 mt-1">Sistem biometrik & geofencing pintar berhasil mendata sesi Anda secara akurat.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-wider rounded-xl">
                        {{ session('success') }}
                    </span>
                    @if (session()->has('warning'))
                        <span class="px-4 py-2 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-black uppercase tracking-wider rounded-xl">
                            {{ session('warning') }}
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total Attendance -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-premium hover:shadow-premium-hover hover:translate-y-[-2px] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-sky-400 transition-colors">Bulan Ini</span>
                    <div class="p-3 bg-blue-500/10 rounded-xl text-sky-400 border border-blue-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-display">{{ $stats['total_attendance'] }}</p>
                <div class="mt-2 text-xs text-slate-400 font-medium">Log kehadiran tercatat</div>
            </div>

            <!-- On Time -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-premium hover:shadow-premium-hover hover:translate-y-[-2px] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-emerald-400 transition-colors">Tepat Waktu</span>
                    <div class="p-3 bg-emerald-500/10 rounded-xl text-emerald-400 border border-emerald-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-display">{{ $stats['on_time'] }}</p>
                <div class="mt-2 text-xs text-slate-400 font-medium">Absensi tepat waktu</div>
            </div>

            <!-- Late -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-premium hover:shadow-premium-hover hover:translate-y-[-2px] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-rose-400 transition-colors">Terlambat</span>
                    <div class="p-3 bg-rose-500/10 rounded-xl text-rose-400 border border-rose-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-display">{{ $stats['late'] }}</p>
                <div class="mt-2 text-xs text-slate-400 font-medium">Keterlambatan kedatangan</div>
            </div>

            <!-- Alerts -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-2xl p-6 shadow-premium hover:shadow-premium-hover hover:translate-y-[-2px] transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 group-hover:text-amber-400 transition-colors">Peringatan Keamanan</span>
                    <div class="p-3 bg-amber-500/10 rounded-xl text-amber-400 border border-amber-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-extrabold text-white font-display">{{ $stats['suspicious_events'] }}</p>
                <div class="mt-2 text-xs text-slate-400 font-medium">Anomali telemetri terdeteksi</div>
            </div>
        </div>

        <!-- Today's Attendance Section -->
        <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-white tracking-tight">Kehadiran Hari Ini</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Status kehadiran real-time dan parameter telemetri (Klik kartu untuk detail absensi)</p>
                </div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full flex items-center">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> Verifikasi Aktif
                </span>
            </div>
            
            <div class="p-6">
                @if($todayAttendance->isEmpty())
                    <div class="text-center py-14 max-w-sm mx-auto">
                        <div class="mx-auto w-16 h-16 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center mb-5 text-sky-400">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-base font-bold text-white">Gerbang Absensi Terbuka</h4>
                        <p class="mt-1.5 text-xs text-slate-400 leading-relaxed">Anda belum mencatat kehadiran hari ini. Pastikan izin GPS aktif pada perangkat Anda sebelum memulai absensi.</p>
                        <div class="mt-6">
                            <a href="{{ route('attendance.checkin') }}" 
                               class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-button-primary hover:shadow-button-primary-hover hover:translate-y-[-1px] active:translate-y-[0px] transition-all">
                                <svg class="-ml-1 mr-2 h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Absen Masuk Sekarang
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($todayAttendance as $attendance)
                            <div @click="selectedLog = {{ json_encode([
                                'id' => $attendance->id,
                                'type' => $attendance->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar',
                                'timestamp' => $attendance->timestamp->translatedFormat('H:i:s, d F Y'),
                                'latitude' => $attendance->latitude,
                                'longitude' => $attendance->longitude,
                                'accuracy' => $attendance->accuracy,
                                'ip_address' => $attendance->ip_address,
                                'work_mode' => strtoupper($attendance->work_mode ?? 'office'),
                                'risk_score' => $attendance->risk_score ?? 0,
                                'risk_level' => $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah'),
                                'risk_class' => $attendance->risk_level,
                                'status' => $attendance->status === 'approved' ? 'Disetujui' : ($attendance->status === 'flagged' ? 'Dicurigai' : 'Diproses'),
                                'status_class' => $attendance->status,
                                'is_late' => $attendance->is_late,
                                'selfie_url' => $attendance->selfie_path ? asset('storage/' . $attendance->selfie_path) : null,
                                'notes' => $attendance->notes ?? 'Tidak ada catatan tambahan.',
                                'branch_name' => $attendance->branch->name ?? 'HQ Workspace',
                                'device_hash' => substr(md5($attendance->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                                'employee_name' => $attendance->user->name ?? 'Karyawan'
                            ]) }}; showModal = true;"
                            class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-[#0d1527]/55 border border-white/5 rounded-2xl hover:border-white/10 hover:bg-[#121d33]/45 hover:scale-[1.008] cursor-pointer transition-all duration-200 group">
                                <div class="flex items-center space-x-4">
                                    @if($attendance->type === 'checkin')
                                        <div class="h-11 w-11 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="h-11 w-11 rounded-xl bg-blue-500/10 text-sky-400 border border-blue-500/20 flex items-center justify-center flex-shrink-0">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm font-bold text-white group-hover:text-sky-300 transition-colors">
                                                {{ auth()->user()->hasAnyRole(['super_admin', 'hr_admin']) ? ($attendance->user->name ?? 'Karyawan') : ($attendance->type === 'checkin' ? 'Verifikasi Absen Masuk' : 'Verifikasi Absen Keluar') }}
                                            </span>
                                            @if(auth()->user()->hasAnyRole(['super_admin', 'hr_admin']))
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-blue-500/10 text-sky-400 border border-blue-500/20">
                                                    {{ $attendance->type === 'checkin' ? 'Masuk' : 'Keluar' }}
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/5 text-slate-300">{{ strtoupper($attendance->work_mode ?? 'office') }}</span>
                                        </div>
                                        <div class="text-xs text-slate-400 font-medium mt-0.5">Waktu Tercatat: {{ $attendance->timestamp->format('H:i:s') }} • IP: {{ $attendance->ip_address }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end space-x-4 mt-3 sm:mt-0">
                                    <div class="flex items-center space-x-2.5">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $attendance->risk_level === 'high' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : ($attendance->risk_level === 'medium' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20') }}">
                                            Risiko: {{ $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah') }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $attendance->status === 'approved' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($attendance->status === 'flagged' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                            Status: {{ $attendance->status === 'approved' ? 'Disetujui' : ($attendance->status === 'flagged' ? 'Dicurigai' : 'Diproses') }}
                                        </span>
                                    </div>
                                    <span class="text-xs font-bold text-sky-400 group-hover:translate-x-1.5 transition-transform flex items-center">
                                        Detail
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if(!$todayAttendance->where('type', 'checkout')->count())
                            <div class="pt-4 flex justify-center">
                                <a href="{{ route('attendance.checkout') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-2xl hover:text-white hover:border-white/20 shadow-sm transition-all duration-200">
                                    <svg class="-ml-1 mr-2 h-4.5 w-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Absen Keluar Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Gorgeous Glassmorphic Popup Modal for Attendance Details -->
    <div x-show="showModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#090e1a]/85 backdrop-blur-2xl transition-all duration-300"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="relative w-full max-w-3xl bg-[#121d33]/90 border border-white/15 rounded-3xl shadow-2xl overflow-hidden p-6 sm:p-8 transform transition-all duration-300"
             @click.away="showModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="scale-95 translate-y-4"
             x-transition:enter-end="scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="scale-100 translate-y-0"
             x-transition:leave-end="scale-95 translate-y-4">
            
            <!-- Absolute decoration background -->
            <div class="absolute -right-24 -top-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-24 -bottom-24 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-5 border-b border-white/10 mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-sky-400 shadow-inner">
                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-white tracking-tight font-display" x-text="'Detail ' + (selectedLog ? selectedLog.type : '')"></h3>
                        <p class="text-xs text-slate-400 mt-0.5">Telemetri Kehadiran Resmi Terenkripsi</p>
                    </div>
                </div>
                <button @click="showModal = false" class="text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 p-2 rounded-xl transition-all border border-white/5 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6" x-if="selectedLog">
                
                <!-- Left Column: Biometric Selfie Photo -->
                <div class="md:col-span-5 flex flex-col items-center">
                    <div class="relative w-full aspect-square max-w-[240px] bg-[#0d1527] border border-white/10 rounded-3xl overflow-hidden flex items-center justify-center group shadow-xl">
                        
                        <template x-if="selectedLog && selectedLog.selfie_url">
                            <img :src="selectedLog.selfie_url" class="w-full h-full object-cover transform scaleX(-1)">
                        </template>

                        <template x-if="selectedLog && !selectedLog.selfie_url">
                            <div class="text-center text-slate-500 p-6 flex flex-col items-center">
                                <div class="relative w-24 h-24 mb-4 flex items-center justify-center">
                                    <!-- Dynamic Animated Hologram Face Map -->
                                    <div class="absolute inset-0 border border-sky-400/40 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-2 border border-dashed border-sky-400/30 rounded-full animate-spin" style="animation-duration: 10s"></div>
                                    <svg class="w-12 h-12 text-sky-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-black uppercase text-sky-400 tracking-wider">Baseline Face Match</span>
                                <span class="text-[8px] font-medium text-slate-500 mt-1 block">Foto Fisik Terenkripsi Aman</span>
                            </div>
                        </template>

                        <!-- Cyber Scan HUD Sweep -->
                        <div class="absolute inset-x-0 h-[1.5px] bg-gradient-to-r from-transparent via-sky-400 to-transparent shadow-[0_0_10px_#38bdf8] pointer-events-none z-10 animate-scan" style="animation: scanLine 3s linear infinite;"></div>

                        <!-- Mini indicator tags overlay -->
                        <div class="absolute bottom-3 left-3 right-3 flex justify-between z-10 pointer-events-none">
                            <span class="px-2 py-0.5 bg-black/60 backdrop-blur-md rounded text-[8px] font-mono font-bold text-emerald-400 border border-white/5">GPS LOCKED</span>
                            <span class="px-2 py-0.5 bg-black/60 backdrop-blur-md rounded text-[8px] font-mono font-bold text-sky-400 border border-white/5">MFA SAFE</span>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-white/5 border border-white/10 text-slate-300">
                            Modus Kerja: <span class="text-sky-400 font-extrabold ml-1" x-text="selectedLog ? selectedLog.work_mode : ''"></span>
                        </span>
                    </div>
                </div>

                <!-- Right Column: Interactive Parameter Table -->
                <div class="md:col-span-7 space-y-4.5">
                    
                    <!-- Top stats section -->
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3.5 bg-[#0d1527]/90 border border-white/5 rounded-2xl">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Status Waktu</span>
                            <div class="mt-1 flex items-center">
                                <template x-if="selectedLog && selectedLog.is_late">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase tracking-wider">Terlambat</span>
                                </template>
                                <template x-if="selectedLog && !selectedLog.is_late">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase tracking-wider">Tepat Waktu</span>
                                </template>
                            </div>
                        </div>

                        <div class="p-3.5 bg-[#0d1527]/90 border border-white/5 rounded-2xl">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Validitas Sistem</span>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider" 
                                      :class="selectedLog && selectedLog.status_class === 'approved' ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' : (selectedLog && selectedLog.status_class === 'flagged' ? 'bg-rose-500/10 border border-rose-500/20 text-rose-400' : 'bg-amber-500/10 border border-amber-500/20 text-amber-400')" 
                                      x-text="selectedLog ? selectedLog.status : ''"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Telemetry Data table -->
                    <div class="bg-[#0d1527]/90 border border-white/5 rounded-2xl divide-y divide-white/5 text-xs">
                        
                        <!-- Employee Name -->
                        <div class="flex justify-between items-center p-3" x-show="selectedLog && selectedLog.employee_name">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Nama Karyawan</span>
                            <span class="font-bold text-white text-right" x-text="selectedLog ? selectedLog.employee_name : ''"></span>
                        </div>

                        <!-- Precise Time -->
                        <div class="flex justify-between items-center p-3">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Waktu Absen</span>
                            <span class="font-bold text-white text-right" x-text="selectedLog ? selectedLog.timestamp : ''"></span>
                        </div>

                        <!-- Branch Name -->
                        <div class="flex justify-between items-center p-3">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Lokasi Cabang</span>
                            <span class="font-bold text-sky-400" x-text="selectedLog ? selectedLog.branch_name : ''"></span>
                        </div>

                        <!-- Coordinates -->
                        <div class="flex justify-between items-center p-3">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Koordinat Geografis</span>
                            <span class="font-mono font-semibold text-white" x-text="selectedLog ? selectedLog.latitude + ', ' + selectedLog.longitude : ''"></span>
                        </div>

                        <!-- Accuracy -->
                        <div class="flex justify-between items-center p-3">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Presisi Telemetri GPS</span>
                            <div class="text-right">
                                <span class="font-bold text-emerald-400" x-text="selectedLog ? '± ' + selectedLog.accuracy + ' meter' : ''"></span>
                                <div class="w-20 bg-slate-800 h-1.5 rounded-full overflow-hidden border border-white/5 mt-1 ml-auto">
                                    <div class="bg-gradient-to-r from-emerald-500 to-green-500 h-full rounded-full" :style="selectedLog ? `width: ${Math.max(10, Math.min(100, 100 - (selectedLog.accuracy * 1.5)))}%` : 'width: 0%'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Network parameters -->
                        <div class="flex justify-between items-center p-3">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">IP & Perangkat Absen</span>
                            <div class="text-right space-y-0.5">
                                <span class="font-semibold text-white block" x-text="selectedLog ? selectedLog.ip_address : ''"></span>
                                <span class="font-mono text-[9px] text-slate-500 block" x-text="selectedLog ? '#' + selectedLog.device_hash : ''"></span>
                            </div>
                        </div>

                        <!-- Risk Score -->
                        <div class="flex justify-between items-center p-3">
                            <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Tingkat Risiko Biometrik</span>
                            <div class="text-right">
                                <span class="font-bold text-emerald-400" :class="selectedLog && selectedLog.risk_class === 'high' ? 'text-rose-400' : (selectedLog && selectedLog.risk_class === 'medium' ? 'text-amber-400' : 'text-emerald-400')" x-text="selectedLog ? selectedLog.risk_score + '% (' + selectedLog.risk_level + ')' : ''"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    <div class="p-3.5 bg-white/5 border border-white/10 rounded-2xl">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-1">Catatan Kehadiran</span>
                        <p class="text-xs text-slate-300 leading-relaxed font-medium italic" x-text="selectedLog ? selectedLog.notes : ''"></p>
                    </div>

                </div>
            </div>

            <!-- Action buttons -->
            <div class="mt-6 pt-5 border-t border-white/10 flex justify-end space-x-3">
                <button @click="showModal = false" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-700 hover:to-sky-600 text-white text-xs font-bold uppercase tracking-wider rounded-2xl shadow-button-primary transition-all cursor-pointer">
                    Tutup Detail
                </button>
            </div>

        </div>
    </div>

    <!-- Real-time Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-[#121d33]/90 backdrop-blur-xl border border-white/10 p-4 rounded-2xl shadow-2xl flex items-center space-x-3.5"
         style="display: none;">
        <div class="p-2 rounded-xl text-white shadow-md flex-shrink-0"
             :class="toast.type === 'info' ? 'bg-blue-600' : 'bg-emerald-600'">
            <template x-if="toast.type === 'info'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="toast.type !== 'info'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
        </div>
        <div class="flex-grow text-left">
            <span class="text-[9px] font-black uppercase tracking-wider text-sky-400 block">Notifikasi Real-time</span>
            <p class="text-xs font-semibold text-white mt-0.5" x-text="toast.message"></p>
        </div>
        <button @click="toast.show = false" class="text-slate-400 hover:text-white cursor-pointer flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

</div>

<style>
@keyframes scanLine {
    0% { top: 0%; }
    50% { top: 100%; }
    100% { top: 0%; }
}
</style>
