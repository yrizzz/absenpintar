<!-- Inject Leaflet Assets directly to avoid bundle overhead -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="py-8 min-h-screen text-slate-100 bg-transparent" x-data="{
    selectedLog: null,
    showModal: false,
    init() {
        this.$watch('showModal', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    },
    toast: { show: false, message: '', type: 'success' },
    showToast(message, type = 'success') {
        this.toast.message = message;
        this.toast.type = type;
        this.toast.show = true;
        setTimeout(() => { this.toast.show = false; }, 5000);
    },
    detailMap: null,
    detailUserMarker: null,
    initDetailMap() {
        this.$nextTick(() => {
            if (!this.selectedLog) return;
            const lat = parseFloat(this.selectedLog.latitude);
            const lng = parseFloat(this.selectedLog.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            // Wait for Alpine x-if to insert DOM, then init Leaflet
            setTimeout(() => {
                const mapEl = document.getElementById('detail-map');
                if (!mapEl) { console.warn('[Map] detail-map element not found'); return; }

                if (this.detailMap) {
                    try {
                        this.detailMap.remove();
                    } catch (e) { console.error(e); }
                    this.detailMap = null;
                    this.detailUserMarker = null;
                }

                this.detailMap = L.map('detail-map', {
                    zoomControl: true,
                    attributionControl: false
                }).setView([lat, lng], 16);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(this.detailMap);

                // Google Maps style blue pulsing location dot
                const userIcon = L.divIcon({
                    className: 'custom-user-dot',
                    html: `<div class='relative flex items-center justify-center'>
                             <div class='absolute w-8 h-8 rounded-full bg-blue-500/35 animate-ping'></div>
                             <div class='relative w-3.5 h-3.5 bg-blue-600 rounded-full border-2 border-white shadow-[0_0_8px_rgba(37,99,235,0.7)]'></div>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                this.detailUserMarker = L.marker([lat, lng], { icon: userIcon }).addTo(this.detailMap);
                this.detailUserMarker.bindPopup('<strong class=\'text-xs text-slate-800\'>Lokasi Absensi</strong>').openPopup();

                // Staggered size recalculations to handle the transition animation perfectly
                [100, 300, 600, 1200, 2000].forEach(delay => {
                    setTimeout(() => {
                        if (this.detailMap) this.detailMap.invalidateSize();
                    }, delay);
                });
            }, 250);
        });
    }
}"
    @attendance-updated.window="
         const eventDetail = $event.detail[0] || {};
         const eventType = eventDetail.type === 'checkin' ? 'Masuk' : 'Keluar';
         const isAdmin = {{ auth()->user()->hasAnyRole(['super_admin', 'hr_admin'])? 'true': 'false' }};
         if (isAdmin) {
             showToast('Log Absensi Baru: Seseorang baru saja melakukan Absen ' + eventType + '!', 'info');
         } else {
             showToast('Absensi berhasil terverifikasi real-time!', 'success');
         }
     ">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <!-- Welcome banner with a gorgeous custom glassmorphic slate background -->
        <div
            class="relative overflow-hidden bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 mb-8 shadow-2xl">
            <div
                class="absolute top-[-50%] right-[-10%] w-[350px] h-[350px] rounded-full bg-blue-500/10 blur-[80px] pointer-events-none animate-pulse-subtle">
            </div>
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span
                        class="badge-info mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-ping mr-1.5"></span>
                        <span>Workspace Aktif</span>
                    </span>
                    <h1 class="heading-1 text-2xl sm:text-3xl font-black text-white">Selamat datang kembali,
                        {{ auth()->user()->name }}</h1>
                    <p class="mt-1.5 label-sm">Ruang Karyawan:
                        <strong class="text-white">{{ auth()->user()->branch->name ?? 'HQ Workspace' }}</strong> • {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('attendance.index') }}"
                        class="btn-sm btn-secondary">
                        <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Riwayat Kehadiran
                    </a>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div
                class="mb-8 p-5 bg-gradient-to-r from-emerald-500/10 via-[#121d33]/80 to-blue-500/10 border border-white/10 text-slate-100 rounded-2xl shadow-2xl relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/10 flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-white leading-tight">Absensi Berhasil Terverifikasi!</h4>
                        <p class="text-xs text-slate-400 mt-1">Sistem biometrik & geofencing pintar berhasil mendata sesi Anda secara akurat.</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span
                        class="badge-rect-success">
                        {{ session('success') }}
                    </span>
                    @if (session()->has('warning'))
                        <span
                            class="badge-rect-warning">
                            {{ session('warning') }}
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-8">
            <!-- Total Attendance -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-blue-500/20 rounded-2xl p-4 sm:p-6 shadow-[0_0_15px_rgba(59,130,246,0.05)] hover:shadow-[0_0_25px_rgba(59,130,246,0.2)] hover:border-blue-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-400 opacity-80"></div>
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <span
                        class="label-xs font-black uppercase tracking-wider text-blue-400/90 group-hover:text-blue-300 transition-colors">Bulan Ini</span>
                    <div class="p-2 sm:p-2.5 bg-blue-500/10 rounded-xl text-blue-400 border border-blue-500/20 flex-shrink-0 group-hover:bg-blue-500/20 transition-all">
                        <svg class="h-4.5 w-4.5 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-blue-200 drop-shadow-[0_0_8px_rgba(59,130,246,0.45)]">{{ $stats['total_attendance'] }}</p>
                <div class="mt-2 label-sm truncate font-medium text-slate-400">Log kehadiran tercatat</div>
            </div>

            <!-- On Time -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-emerald-500/20 rounded-2xl p-4 sm:p-6 shadow-[0_0_15px_rgba(16,185,129,0.05)] hover:shadow-[0_0_25px_rgba(16,185,129,0.2)] hover:border-emerald-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-emerald-500 via-teal-500 to-green-400 opacity-80"></div>
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <span
                        class="label-xs font-black uppercase tracking-wider text-emerald-400/90 group-hover:text-emerald-300 transition-colors">Tepat Waktu</span>
                    <div class="p-2 sm:p-2.5 bg-emerald-500/10 rounded-xl text-emerald-400 border border-emerald-500/20 flex-shrink-0 group-hover:bg-emerald-500/20 transition-all">
                        <svg class="h-4.5 w-4.5 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-200 drop-shadow-[0_0_8px_rgba(16,185,129,0.45)]">{{ $stats['on_time'] }}</p>
                <div class="mt-2 label-sm truncate font-medium text-slate-400">Absensi tepat waktu</div>
            </div>

            <!-- Late -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-rose-500/20 rounded-2xl p-4 sm:p-6 shadow-[0_0_15px_rgba(244,63,94,0.05)] hover:shadow-[0_0_25px_rgba(244,63,94,0.2)] hover:border-rose-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-500 via-red-500 to-pink-400 opacity-80"></div>
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <span
                        class="label-xs font-black uppercase tracking-wider text-rose-400/90 group-hover:text-rose-300 transition-colors">Terlambat</span>
                    <div class="p-2 sm:p-2.5 bg-rose-500/10 rounded-xl text-rose-400 border border-rose-500/20 flex-shrink-0 group-hover:bg-rose-500/20 transition-all">
                        <svg class="h-4.5 w-4.5 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-rose-200 drop-shadow-[0_0_8px_rgba(244,63,94,0.45)]">{{ $stats['late'] }}</p>
                <div class="mt-2 label-sm truncate font-medium text-slate-400">Keterlambatan kedatangan</div>
            </div>

            <!-- Alerts -->
            <div
                class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-amber-500/20 rounded-2xl p-4 sm:p-6 shadow-[0_0_15px_rgba(245,158,11,0.05)] hover:shadow-[0_0_25px_rgba(245,158,11,0.2)] hover:border-amber-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-amber-500 via-orange-500 to-yellow-400 opacity-80"></div>
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <span
                        class="label-xs font-black uppercase tracking-wider text-amber-400/90 group-hover:text-amber-300 transition-colors">Peringatan</span>
                    <div class="p-2 sm:p-2.5 bg-amber-500/10 rounded-xl text-amber-400 border border-amber-500/20 flex-shrink-0 group-hover:bg-amber-500/20 transition-all">
                        <svg class="h-4.5 w-4.5 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
                <p class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-amber-200 drop-shadow-[0_0_8px_rgba(245,158,11,0.45)]">{{ $stats['suspicious_events'] }}</p>
                <div class="mt-2 label-sm truncate font-medium text-slate-400">Anomali terdeteksi</div>
            </div>
        </div>

        <div class="relative overflow-hidden bg-gradient-to-b from-[#121d33]/85 to-[#0b1222]/95 backdrop-blur-xl border border-white/10 rounded-3xl shadow-[0_0_30px_rgba(0,0,0,0.2)] transition-all duration-300 hover:border-white/15">
            <div class="absolute top-0 left-0 right-0 h-[1px] bg-gradient-to-r from-transparent via-white/15 to-transparent"></div>
            <div class="px-6 py-5 border-b border-white/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 bg-white/[0.01]">
                <div class="flex-1 min-w-0">
                    <h3 class="heading-2 text-lg sm:text-xl font-bold text-white">Kehadiran Hari Ini</h3>
                    <p class="label-sm mt-1">Status kehadiran real-time dan parameter telemetri (Klik kartu untuk detail absensi)</p>
                </div>
                <div class="flex-shrink-0 self-start sm:self-auto">
                    <span
                        class="badge-success inline-flex items-center">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span> Verifikasi Aktif
                    </span>
                </div>
            </div>

            <div class="p-6">
                @if ($todayAttendance->isEmpty())
                    <div class="text-center py-14 max-w-sm mx-auto">
                        <div
                            class="mx-auto w-16 h-16 bg-blue-500/10 border border-blue-500/20 rounded-2xl flex items-center justify-center mb-5 text-blue-400">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="heading-3 text-base sm:text-lg font-bold text-white">Gerbang Absensi Terbuka</h4>
                        <p class="mt-2 label-sm">Anda belum mencatat kehadiran hari ini. Pastikan izin GPS aktif pada perangkat Anda sebelum memulai absensi.</p>
                        <div class="mt-6">
                            <a href="{{ route('attendance.checkin') }}"
                                class="btn-sm btn-primary">
                                <svg class="-ml-1 mr-2 h-4.5 w-4.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Absen Masuk Sekarang
                            </a>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($todayAttendance as $attendance)
                            <div @click="selectedLog = {{ json_encode([
                                'id' => $attendance->id,
                                'type' => $attendance->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar',
                                'timestamp' => $attendance->timestamp->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->translatedFormat('H:i:s, d F Y'),
                                'latitude' => $attendance->latitude,
                                'longitude' => $attendance->longitude,
                                'accuracy' => $attendance->accuracy,
                                'ip_address' => $attendance->ip_address,
                                'work_mode' => strtoupper($attendance->work_mode ?? 'office'),
                                'risk_score' => $attendance->risk_score ?? 0,
                                'risk_level' =>
                                    $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah'),
                                'risk_class' => $attendance->risk_level,
                                'status' =>
                                    $attendance->status === 'approved'
                                        ? 'Disetujui'
                                        : ($attendance->status === 'flagged'
                                            ? 'Dicurigai'
                                            : 'Diproses'),
                                'status_class' => $attendance->status,
                                'is_late' => $attendance->is_late,
                                'selfie_url' => $attendance->selfie_path ? asset('storage/' . $attendance->selfie_path) : null,
                                'notes' => $attendance->notes ?? 'Tidak ada catatan tambahan.',
                                'branch_name' => $attendance->branch->name ?? 'HQ Workspace',
                                'device_hash' => substr(md5($attendance->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                                'employee_name' => $attendance->user->name ?? 'Karyawan',
                            ]) }}; showModal = true; initDetailMap();"
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-[#0d1527]/40 border border-white/5 rounded-2xl hover:border-blue-500/35 hover:bg-blue-500/[0.02] hover:scale-[1.004] hover:shadow-[0_0_15px_rgba(59,130,246,0.05)] cursor-pointer transition-all duration-200 group">
                                <div class="flex items-start space-x-3.5 w-full sm:w-auto">
                                    @if ($attendance->type === 'checkin')
                                        <div
                                            class="h-11 w-11 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                    @else
                                        <div
                                            class="h-11 w-11 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                            <span
                                                class="label-md font-bold text-white group-hover:text-blue-300 transition-colors truncate">
                                                {{ auth()->user()->hasAnyRole(['super_admin', 'hr_admin'])? $attendance->user->name ?? 'Karyawan': ($attendance->type === 'checkin'? 'Verifikasi Absen Masuk': 'Verifikasi Absen Keluar') }}
                                            </span>
                                            <div class="flex items-center gap-1 flex-wrap">
                                                @if (auth()->user()->hasAnyRole(['super_admin', 'hr_admin']))
                                                    <span
                                                        class="badge-rect-info text-[9px] py-0.5 px-1.5 font-bold uppercase tracking-wider">
                                                        {{ $attendance->type === 'checkin' ? 'Masuk' : 'Keluar' }}
                                                    </span>
                                                @endif
                                                <span
                                                    class="badge-rect-neutral text-[9px] py-0.5 px-1.5 font-bold uppercase tracking-wider">{{ strtoupper($attendance->work_mode ?? 'office') }}</span>
                                            </div>
                                        </div>
                                        <div class="text-[11px] text-slate-400 font-medium mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                            <span>Waktu: <strong class="text-slate-200">{{ $attendance->timestamp->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->format('H:i:s') }}</strong></span>
                                            <span class="text-slate-600 hidden xs:inline">•</span>
                                            <span>IP: <strong class="text-slate-200">{{ $attendance->ip_address }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-3 mt-4 sm:mt-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-white/5 w-full sm:w-auto">
                                    <div class="flex items-center gap-1.5">
                                        <span
                                            class="badge-rect-{{ $attendance->risk_level === 'high' ? 'danger' : ($attendance->risk_level === 'medium' ? 'warning' : 'success') }} text-[10px] py-1 px-2.5">
                                            Risiko:
                                            {{ $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah') }}
                                        </span>
                                        <span
                                            class="badge-rect-{{ $attendance->status === 'approved' ? 'success' : ($attendance->status === 'flagged' ? 'danger' : 'warning') }} text-[10px] py-1 px-2.5">
                                            Status:
                                            {{ $attendance->status === 'approved' ? 'Disetujui' : ($attendance->status === 'flagged' ? 'Dicurigai' : 'Diproses') }}
                                        </span>
                                    </div>
                                    <span
                                        class="label-sm font-bold text-blue-400 group-hover:translate-x-1 transition-transform flex items-center shrink-0">
                                        <span>Detail</span>
                                        <svg class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if (!$todayAttendance->where('type', 'checkout')->count())
                            <div class="pt-4 flex justify-center">
                                <a href="{{ route('attendance.checkout') }}"
                                    class="btn-sm btn-primary">
                                    <svg class="-ml-1 mr-2 h-4.5 w-4.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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

    <x-attendance.detail-modal :is-admin="$isAdmin" />

    <!-- Real-time Toast Notification -->
    <div x-show="toast.show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 z-50 max-w-sm w-full bg-[#121d33]/90 backdrop-blur-xl border border-white/10 p-4 rounded-2xl shadow-2xl flex items-center space-x-3.5"
        style="display: none;">
        <div class="p-2 rounded-xl text-white shadow-md flex-shrink-0"
            :class="toast.type === 'info' ? 'bg-blue-600' : 'bg-emerald-600'">
            <template x-if="toast.type === 'info'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
            <template x-if="toast.type !== 'info'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </template>
        </div>
        <div class="flex-grow text-left">
            <span class="text-[9px] font-black text-blue-400 block">Notifikasi real-time</span>
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
