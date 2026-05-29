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
}">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8 sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="heading-1">Riwayat Kehadiran</h1>
                <p class="mt-1 label-sm">
                    {{ $isAdmin ? 'Telusuri, verifikasi, dan filter log kehadiran semua karyawan secara mendalam' : 'Telusuri, verifikasi, dan filter log kehadiran historis Anda secara mendalam' }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2.5">
                @php
                    $start = $filterMonth ? \Carbon\Carbon::parse($filterMonth . '-01')->startOfMonth()->toDateString() : now()->startOfMonth()->toDateString();
                    $end = $filterMonth ? \Carbon\Carbon::parse($filterMonth . '-01')->endOfMonth()->toDateString() : now()->toDateString();
                @endphp
                <a href="{{ route('letters.attendance-certificate', ['start_date' => $start, 'end_date' => $end]) }}" 
                   target="_blank"
                   class="btn-sm btn-secondary flex items-center gap-1.5">
                    <svg class="w-4.5 h-4.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    <span>Cetak Suket Kehadiran</span>
                </a>

                <a href="{{ route('attendance.checkin') }}" 
                   class="btn-sm btn-primary flex items-center">
                    <svg class="-ml-1 mr-2 h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Absen Masuk Baru
                </a>
            </div>
        </div>

        <!-- Filters Box -->
        <div class="mb-8 bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full blur-xl pointer-events-none"></div>
            <h3 class="label-xs mb-4">Filter Catatan Log</h3>
            
            <div class="grid grid-cols-1 gap-4 {{ $isAdmin ? 'sm:grid-cols-4' : 'sm:grid-cols-3' }}">
                @if($isAdmin)
                    <div class="space-y-1.5">
                        <label for="searchEmployee" class="block label-xs">Cari Karyawan</label>
                        <input wire:model.live="searchEmployee" 
                               type="text" 
                               id="searchEmployee"
                               placeholder="Nama atau ID Karyawan..."
                               class="w-full px-4 py-2.5 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all">
                    </div>
                @endif
                <div class="space-y-1.5">
                    <label for="filterMonth" class="block label-xs">Bulan</label>
                    <input wire:model.live="filterMonth" 
                           type="month" 
                           id="filterMonth"
                           onclick="this.showPicker()"
                           class="w-full px-4 py-2.5 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all cursor-pointer">
                </div>
                <div class="space-y-1.5">
                    <label for="filterType" class="block label-xs">Tipe Absen</label>
                    <select wire:model.live="filterType" 
                            id="filterType"
                            class="w-full px-4 py-2.5 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all cursor-pointer">
                        <option value="">Semua Tipe</option>
                        <option value="checkin">Absen Masuk</option>
                        <option value="checkout">Absen Keluar</option>
                        <option value="break_start">Mulai Istirahat</option>
                        <option value="break_end">Selesai Istirahat</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label for="filterStatus" class="block label-xs">Status Validasi</label>
                    <select wire:model.live="filterStatus" 
                            id="filterStatus"
                            class="w-full px-4 py-2.5 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="approved">Disetujui</option>
                        <option value="pending">Diproses</option>
                        <option value="flagged">Dicurigai</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
            @if($attendances->isEmpty())
                <div class="text-center py-16 max-w-sm mx-auto">
                    <div class="mx-auto w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                        <svg class="h-6.5 w-6.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h4 class="heading-3 text-white">Tidak ada catatan ditemukan</h4>
                    <p class="mt-1 label-sm">Cobalah melonggarkan filter Anda atau lakukan absensi baru untuk mengisi halaman riwayat.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full min-w-max divide-y divide-white/5">
                        <thead class="bg-white/5">
                            <tr>
                                @if($isAdmin)
                                    <th class="px-6 py-4 text-left label-xs w-[200px]" style="width: 200px;">Karyawan</th>
                                @endif
                                <th class="px-6 py-4 text-left label-xs w-[180px]" style="width: 180px;">Tanggal & Waktu</th>
                                <th class="px-6 py-4 text-left label-xs w-[140px]" style="width: 140px;">Metode Absensi</th>
                                <th class="px-6 py-4 text-left label-xs w-[220px]" style="width: 220px;">Cabang & Presisi GPS</th>
                                <th class="px-6 py-4 text-left label-xs w-[160px]" style="width: 160px;">Telemetri Risiko</th>
                                <th class="px-6 py-4 text-left label-xs w-[150px]" style="width: 150px;">Status Kepatuhan</th>
                                <th class="px-6 py-4 text-left label-xs w-[100px]" style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-transparent">
                            @php
                                $tzSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
                                $tzLabel = 'WIB';
                                if ($tzSetting === 'Asia/Makassar') $tzLabel = 'WITA';
                                if ($tzSetting === 'Asia/Jayapura') $tzLabel = 'WIT';
                            @endphp
                            @foreach($attendances as $attendance)
                                <tr class="hover:bg-white/5 transition-colors duration-150">
                                    @if($isAdmin)
                                        <td class="px-6 py-4.5 whitespace-nowrap">
                                            <div class="label-md font-bold text-white">{{ $attendance->user->name ?? 'Karyawan' }}</div>
                                            <div class="label-xs font-mono">#{{ $attendance->user->employee_id ?? 'N/A' }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <div class="label-md font-bold text-white">
                                            {{ $attendance->timestamp->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="label-sm mt-0.5">
                                            {{ $attendance->timestamp->timezone($tzSetting)->format('H:i:s') }} {{ $tzLabel }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <span class="{{ $attendance->type === 'checkin' ? 'badge-rect-success' : 'badge-rect-info' }}">
                                            {{ $attendance->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <div class="label-md font-bold text-white">{{ $attendance->branch?->name ?? 'HQ Workspace' }}</div>
                                        <div class="label-sm mt-0.5">Akurasi GPS: ± {{ round($attendance->accuracy) }}m</div>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <span class="badge-rect-{{ $attendance->risk_level === 'high' ? 'danger' : ($attendance->risk_level === 'medium' ? 'warning' : 'success') }}">
                                            Risiko: {{ $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah') }} (Skor: {{ $attendance->risk_score }})
                                        </span>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <span class="badge-rect-{{ $attendance->status === 'approved' ? 'success' : ($attendance->status === 'flagged' ? 'danger' : 'warning') }}">
                                            {{ $attendance->status === 'approved' ? 'Disetujui' : ($attendance->status === 'flagged' ? 'Dicurigai' : 'Diproses') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap text-xs">
                                        <div class="flex items-center gap-3">
                                            <button @click="selectedLog = {{ json_encode([
                                                'id' => $attendance->id,
                                                'type' => $attendance->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar',
                                                'timestamp' => $attendance->timestamp->timezone($tzSetting)->translatedFormat('H:i:s, d F Y') . ' ' . $tzLabel,
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
                                                'employee_name' => $attendance->user->name ?? 'Karyawan',
                                                'resolved_address' => $attendance->metadata['resolved_address'] ?? null
                                            ]) }}; showModal = true; initDetailMap();"
                                            class="label-sm font-bold text-blue-400 hover:text-blue-300 transition-colors cursor-pointer focus:outline-none">
                                                Detail
                                            </button>
                                            <span class="text-white/10">|</span>
                                            <a href="{{ route('letters.attendance-certificate', ['user_id' => $attendance->user_id, 'start_date' => $attendance->timestamp->startOfMonth()->toDateString(), 'end_date' => $attendance->timestamp->endOfMonth()->toDateString()]) }}" 
                                               target="_blank"
                                               class="label-sm font-bold text-emerald-400 hover:text-emerald-300 transition-colors">
                                                Cetak
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination block -->
                <div class="px-6 py-5 border-t border-white/5">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Reusable Glassmorphic Popup Modal for Attendance Details -->
    <x-attendance.detail-modal :is-admin="$isAdmin" />

</div>

<style>
@keyframes scanLine {
    0% { top: 0%; }
    50% { top: 100%; }
    100% { top: 0%; }
}
</style>
