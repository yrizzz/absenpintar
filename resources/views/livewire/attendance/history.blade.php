<!-- Inject Leaflet Assets directly to avoid bundle overhead -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="py-8 min-h-screen" x-data="{
    selectedLog: null,
    showModal: false,
    init() {
        this.$watch('showModal', value => {
            document.body.style.overflow = value ? 'hidden' : '';
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

            setTimeout(() => {
                const mapEl = document.getElementById('detail-map');
                if (!mapEl) { console.warn('[Map] detail-map element not found'); return; }

                if (this.detailMap) {
                    try { this.detailMap.remove(); } catch (e) { console.error(e); }
                    this.detailMap = null;
                    this.detailUserMarker = null;
                }

                this.detailMap = L.map('detail-map', { zoomControl: true, attributionControl: false }).setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.detailMap);

                const userIcon = L.divIcon({
                    className: 'custom-user-dot',
                    html: `<div class='relative flex items-center justify-center'>
                             <div class='absolute w-8 h-8 rounded-full bg-primary/30 animate-ping'></div>
                             <div class='relative w-3.5 h-3.5 bg-primary rounded-full border-2 border-white'></div>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                this.detailUserMarker = L.marker([lat, lng], { icon: userIcon }).addTo(this.detailMap);
                this.detailUserMarker.bindPopup('<strong class=\'text-xs text-slate-800\'>Lokasi Absensi</strong>').openPopup();

                [100, 300, 600, 1200, 2000].forEach(delay => {
                    setTimeout(() => { if (this.detailMap) this.detailMap.invalidateSize(); }, delay);
                });
            }, 250);
        });
    }
}">
    <div class="px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="heading-1">Riwayat Kehadiran</h1>
                <p class="mt-1 label-sm">
                    {{ $isAdmin ? 'Telusuri, verifikasi, dan filter log kehadiran semua karyawan secara mendalam.' : 'Telusuri, verifikasi, dan filter log kehadiran historis Anda secara mendalam.' }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-2.5">
                @php
                    $start = $filterMonth ? \Carbon\Carbon::parse($filterMonth . '-01')->startOfMonth()->toDateString() : now()->startOfMonth()->toDateString();
                    $end = $filterMonth ? \Carbon\Carbon::parse($filterMonth . '-01')->endOfMonth()->toDateString() : now()->toDateString();
                @endphp
                <a href="{{ route('letters.attendance-certificate', ['start_date' => $start, 'end_date' => $end]) }}" target="_blank" class="btn-secondary btn-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak Suket Kehadiran
                </a>
                <a href="{{ route('attendance.checkin') }}" class="btn-primary btn-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Absen Masuk Baru
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card p-6 mb-8">
            <h3 class="label-xs uppercase tracking-wide mb-4">Filter Catatan Log</h3>
            <div class="grid grid-cols-1 gap-4 {{ $isAdmin ? 'sm:grid-cols-4' : 'sm:grid-cols-3' }}">
                @if($isAdmin)
                    <div class="space-y-1.5">
                        <label for="searchEmployee" class="label">Cari Karyawan</label>
                        <input wire:model.live="searchEmployee" type="text" id="searchEmployee" placeholder="Nama atau ID Karyawan…">
                    </div>
                @endif
                <div class="space-y-1.5">
                    <label for="filterMonth" class="label">Bulan</label>
                    <input wire:model.live="filterMonth" type="month" id="filterMonth" onclick="this.showPicker()" class="cursor-pointer">
                </div>
                <div class="space-y-1.5">
                    <label for="filterType" class="label">Tipe Absen</label>
                    <select wire:model.live="filterType" id="filterType" class="cursor-pointer">
                        <option value="">Semua Tipe</option>
                        <option value="checkin">Absen Masuk</option>
                        <option value="checkout">Absen Keluar</option>
                        <option value="break_start">Mulai Istirahat</option>
                        <option value="break_end">Selesai Istirahat</option>
                    </select>
                </div>
                <div class="space-y-1.5">
                    <label for="filterStatus" class="label">Status Validasi</label>
                    <select wire:model.live="filterStatus" id="filterStatus" class="cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="approved">Disetujui</option>
                        <option value="pending">Diproses</option>
                        <option value="flagged">Dicurigai</option>
                        <option value="rejected">Ditolak</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card overflow-hidden">
            @if($attendances->isEmpty())
                <div class="text-center py-16 max-w-sm mx-auto">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-muted text-fg-muted mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <h4 class="heading-3">Tidak ada catatan ditemukan</h4>
                    <p class="mt-1 label-sm">Cobalah melonggarkan filter Anda atau lakukan absensi baru untuk mengisi halaman riwayat.</p>
                </div>
            @else
                @php
                    $tzSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
                    $tzLabel = 'WIB';
                    if ($tzSetting === 'Asia/Makassar') $tzLabel = 'WITA';
                    if ($tzSetting === 'Asia/Jayapura') $tzLabel = 'WIT';
                @endphp

                {{-- Desktop table --}}
                <div class="hidden md:block overflow-x-auto">
                    @php
                        $sortBtn = function ($field, $label) use ($sortField, $sortDirection) {
                            $active = $sortField === $field;
                            $arrow = !$active
                                ? '<svg class="h-3.5 w-3.5 opacity-40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>'
                                : ($sortDirection === 'asc'
                                    ? '<svg class="h-3.5 w-3.5 text-primary" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" /></svg>'
                                    : '<svg class="h-3.5 w-3.5 text-primary" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>');
                            return '<button type="button" wire:click="sortBy(\'' . $field . '\')" class="inline-flex items-center gap-1 uppercase tracking-wide transition-colors hover:text-fg ' . ($active ? 'text-fg' : '') . '">' . $label . $arrow . '</button>';
                        };
                    @endphp
                    <table class="min-w-full">
                        <thead class="bg-surface-muted">
                            <tr>
                                @if($isAdmin)<th class="px-4 lg:px-6 py-3.5 text-left label-xs uppercase tracking-wide">Karyawan</th>@endif
                                <th class="px-4 lg:px-6 py-3.5 text-left label-xs uppercase tracking-wide">{!! $sortBtn('timestamp', 'Tanggal &amp; Waktu') !!}</th>
                                <th class="px-4 lg:px-6 py-3.5 text-left label-xs uppercase tracking-wide">{!! $sortBtn('type', 'Metode') !!}</th>
                                <th class="px-4 lg:px-6 py-3.5 text-left label-xs uppercase tracking-wide">Cabang &amp; GPS</th>
                                <th class="px-4 lg:px-6 py-3.5 text-left label-xs uppercase tracking-wide">{!! $sortBtn('risk_level', 'Telemetri Risiko') !!}</th>
                                <th class="px-4 lg:px-6 py-3.5 text-left label-xs uppercase tracking-wide">{!! $sortBtn('status', 'Status') !!}</th>
                                <th class="px-4 lg:px-6 py-3.5 text-right label-xs uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr class="border-t border-border hover:bg-surface-muted transition-colors">
                                    @if($isAdmin)
                                        <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                            <div class="label-md">{{ $attendance->user->name ?? 'Karyawan' }}</div>
                                            <div class="label-xs font-mono">#{{ $attendance->user->employee_id ?? 'N/A' }}</div>
                                        </td>
                                    @endif
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="label-md">{{ $attendance->timestamp->translatedFormat('d M Y') }}</div>
                                        <div class="label-sm mt-0.5">{{ $attendance->timestamp->timezone($tzSetting)->format('H:i:s') }} {{ $tzLabel }}</div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span class="{{ $attendance->type === 'checkin' ? 'badge-rect-success' : 'badge-rect-info' }}">
                                            {{ $attendance->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar' }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <div class="label-md">{{ $attendance->branch?->name ?? 'HQ Workspace' }}</div>
                                        <div class="label-sm mt-0.5">Akurasi GPS: ± {{ round($attendance->accuracy) }}m</div>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span class="badge-rect-{{ $attendance->risk_level === 'high' ? 'danger' : ($attendance->risk_level === 'medium' ? 'warning' : 'success') }}">
                                            {{ $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah') }} ({{ $attendance->risk_score }})
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span class="badge-rect-{{ $attendance->status === 'approved' ? 'success' : ($attendance->status === 'flagged' ? 'danger' : 'warning') }}">
                                            {{ $attendance->status === 'approved' ? 'Disetujui' : ($attendance->status === 'flagged' ? 'Dicurigai' : 'Diproses') }}
                                        </span>
                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-3">
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
                                            class="text-sm font-medium text-primary hover:opacity-80 transition-opacity cursor-pointer">Detail</button>
                                            <span class="text-border-strong">|</span>
                                            <a href="{{ route('letters.attendance-slip', $attendance->id) }}" target="_blank" class="text-sm font-medium text-success hover:opacity-80 transition-opacity">Cetak Slip</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y divide-border">
                    @foreach($attendances as $attendance)
                        <div class="p-4 hover:bg-surface-muted transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    @if($isAdmin)
                                        <div class="label-md truncate">{{ $attendance->user->name ?? 'Karyawan' }}</div>
                                        <div class="label-xs font-mono mb-1">#{{ $attendance->user->employee_id ?? 'N/A' }}</div>
                                    @endif
                                    <div class="label-md">{{ $attendance->timestamp->translatedFormat('d M Y') }}</div>
                                    <div class="label-sm mt-0.5">{{ $attendance->timestamp->timezone($tzSetting)->format('H:i:s') }} {{ $tzLabel }}</div>
                                </div>
                                <span class="{{ $attendance->type === 'checkin' ? 'badge-rect-success' : 'badge-rect-info' }} flex-shrink-0">
                                    {{ $attendance->type === 'checkin' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <div>
                                    <div class="label-xs uppercase tracking-wide">Cabang</div>
                                    <div class="label-sm font-medium text-fg truncate">{{ $attendance->branch?->name ?? 'HQ Workspace' }}</div>
                                </div>
                                <div>
                                    <div class="label-xs uppercase tracking-wide">Akurasi GPS</div>
                                    <div class="label-sm font-medium text-fg">± {{ round($attendance->accuracy) }}m</div>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span class="badge-rect-{{ $attendance->risk_level === 'high' ? 'danger' : ($attendance->risk_level === 'medium' ? 'warning' : 'success') }}">
                                    Risiko {{ $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah') }} ({{ $attendance->risk_score }})
                                </span>
                                <span class="badge-rect-{{ $attendance->status === 'approved' ? 'success' : ($attendance->status === 'flagged' ? 'danger' : 'warning') }}">
                                    {{ $attendance->status === 'approved' ? 'Disetujui' : ($attendance->status === 'flagged' ? 'Dicurigai' : 'Diproses') }}
                                </span>
                            </div>

                            <div class="mt-3 flex items-center gap-3 border-t border-border pt-3">
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
                                class="text-sm font-medium text-primary hover:opacity-80 transition-opacity cursor-pointer">Lihat Detail</button>
                                <span class="text-border-strong">|</span>
                                <a href="{{ route('letters.attendance-slip', $attendance->id) }}" target="_blank" class="text-sm font-medium text-success hover:opacity-80 transition-opacity">Cetak Slip</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-5 border-t border-border">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>

    </div>

    <x-attendance.detail-modal :is-admin="$isAdmin" />
</div>
