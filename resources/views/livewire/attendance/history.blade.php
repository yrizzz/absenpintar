{{-- Leaflet assets loaded via @assets so the component keeps a single root element (required by Livewire) --}}
@assets
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endassets

<div class="py-8 min-h-screen" x-data="{
    selectedLog: null,
    showModal: false,
    zoomPhotoUrl: null,
    init() {
        this.$watch('showModal', value => {
            document.body.style.overflow = value ? 'hidden' : '';
        });
        this.$watch('zoomPhotoUrl', value => {
            document.body.style.overflow = value ? 'hidden' : '';
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
            <div class="mt-4 sm:mt-0 grid grid-cols-2 gap-2 sm:flex sm:items-center sm:gap-2.5">
                @php
                    $start = $filterMonth ? \Carbon\Carbon::parse($filterMonth . '-01')->startOfMonth()->toDateString() : now()->startOfMonth()->toDateString();
                    $end = $filterMonth ? \Carbon\Carbon::parse($filterMonth . '-01')->endOfMonth()->toDateString() : now()->toDateString();
                @endphp
                <a href="{{ route('letters.attendance-certificate', ['start_date' => $start, 'end_date' => $end]) }}" target="_blank" class="btn-secondary btn-sm justify-center text-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Cetak Suket
                </a>
                <a href="{{ route('attendance.checkin') }}" class="btn-primary btn-sm justify-center text-center">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Absen Baru
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <h3 class="label-xs uppercase tracking-wide">Filter Catatan Log</h3>
                {{-- Tab Switch --}}
                <div class="inline-flex rounded-lg p-0.5 bg-surface-muted border border-border">
                    <button type="button" wire:click="$set('viewMode','table')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold transition-all
                               {{ $viewMode === 'table' ? 'bg-surface text-primary shadow-sm' : 'text-fg-subtle hover:text-fg' }}">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18M10 3v18M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6z" /></svg>
                        Tabel
                    </button>
                    <button type="button" wire:click="$set('viewMode','gallery')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold transition-all
                               {{ $viewMode === 'gallery' ? 'bg-surface text-primary shadow-sm' : 'text-fg-subtle hover:text-fg' }}">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Foto
                    </button>
                    <button type="button" wire:click="$set('viewMode','matrix')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-semibold transition-all
                               {{ $viewMode === 'matrix' ? 'bg-surface text-primary shadow-sm' : 'text-fg-subtle hover:text-fg' }}">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        Ceklis
                    </button>
                </div>
            </div>

            @if($viewMode === 'table' || $viewMode === 'gallery')
                {{-- Table & Gallery filters --}}
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
            @else
                {{-- Matrix filters --}}
                <div class="grid grid-cols-1 gap-4 {{ $isAdmin ? 'sm:grid-cols-4' : 'sm:grid-cols-3' }}">
                    @if($isAdmin)
                        <div class="space-y-1.5">
                            <label class="label">Cari Karyawan</label>
                            <input wire:model.live="searchEmployee" type="text" placeholder="Nama atau ID Karyawan…">
                        </div>
                    @endif
                    <div class="space-y-1.5">
                        <label class="label">Bulan</label>
                        <select wire:model.live="matrix_month" class="cursor-pointer">
                            @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $num => $name)
                                <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Tahun</label>
                        <select wire:model.live="matrix_year" class="cursor-pointer">
                            @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
            @endif
        </div>
        {{-- Table View --}}
        @if($viewMode === 'table')
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
                                            <button @click="selectedLog = {{ \Illuminate\Support\Js::from([
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
                <div class="md:hidden space-y-3 p-3">
                    @foreach($attendances as $attendance)
                        <div class="bg-surface rounded-2xl border border-border shadow-sm p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    @if($isAdmin)
                                        <div class="label-md truncate font-bold">{{ $attendance->user->name ?? 'Karyawan' }}</div>
                                        <div class="label-xs font-mono text-fg-subtle mb-1">#{{ $attendance->user->employee_id ?? 'N/A' }}</div>
                                    @endif
                                    <div class="text-sm font-semibold text-fg">{{ $attendance->timestamp->translatedFormat('d M Y') }}</div>
                                    <div class="text-xs text-fg-muted mt-0.5">{{ $attendance->timestamp->timezone($tzSetting)->format('H:i:s') }} {{ $tzLabel }}</div>
                                </div>
                                <span class="{{ $attendance->type === 'checkin' ? 'badge-rect-success' : 'badge-rect-info' }} flex-shrink-0">
                                    {{ $attendance->type === 'checkin' ? 'Masuk' : 'Keluar' }}
                                </span>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-3 p-3 rounded-xl bg-surface-muted border border-border/60">
                                <div>
                                    <div class="label-xs uppercase tracking-wide text-fg-subtle mb-0.5">Cabang</div>
                                    <div class="text-xs font-semibold text-fg truncate">{{ $attendance->branch?->name ?? 'HQ Workspace' }}</div>
                                </div>
                                <div>
                                    <div class="label-xs uppercase tracking-wide text-fg-subtle mb-0.5">Akurasi GPS</div>
                                    <div class="text-xs font-semibold text-fg">± {{ round($attendance->accuracy) }}m</div>
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
                                <button @click="selectedLog = {{ \Illuminate\Support\Js::from([
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
                                class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline cursor-pointer">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    Lihat Detail
                                </button>
                                <span class="text-border-strong">|</span>
                                <a href="{{ route('letters.attendance-slip', $attendance->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 text-xs font-bold text-success hover:underline">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    Cetak Slip
                                </a>
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
        @endif

        {{-- Matrix View --}}
        @if($viewMode === 'matrix')
        <div class="card overflow-hidden">
            @php
                $tzSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
                $tzLabel   = 'WIB';
                if ($tzSetting === 'Asia/Makassar') $tzLabel = 'WITA';
                if ($tzSetting === 'Asia/Jayapura') $tzLabel = 'WIT';
            @endphp

            @if($matrixUsers->isEmpty())
                <div class="text-center py-16 max-w-sm mx-auto">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-muted text-fg-muted mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <h4 class="heading-3">Tidak ada karyawan ditemukan</h4>
                    <p class="mt-1 label-sm">Coba ubah filter pencarian karyawan.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-fixed min-w-[900px]">
                        <thead>
                            <tr class="border-b border-border bg-surface-muted label-xs uppercase tracking-wide">
                                {{-- Sticky Name Column --}}
                                <th class="p-3 sticky left-0 bg-surface-muted z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] w-[200px]">
                                    Nama Karyawan
                                </th>
                                {{-- Day columns --}}
                                @foreach($matrixDays as $day)
                                    <th class="p-2 text-center border-l border-border/60 w-[36px]
                                        {{ ($day['is_sunday'] || $day['is_holiday']) ? 'bg-rose-50/50 dark:bg-rose-950/20 text-rose-500 font-bold' : '' }}"
                                        @if($day['is_holiday']) title="{{ $day['holiday_name'] }}" @endif>
                                        <span class="block text-[9px] opacity-70">{{ $day['day_name'] }}</span>
                                        <span class="block text-[11px] mt-0.5 {{ $day['is_holiday'] ? 'underline decoration-rose-500 decoration-dotted cursor-help' : '' }}">{{ $day['day'] }}</span>
                                    </th>
                                @endforeach
                                <th class="p-2 text-center border-l border-border/60 w-[56px] text-[10px]">Hadir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/60">
                            @foreach($matrixUsers as $user)
                                @php $hadirCount = 0; @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.02] transition-colors">
                                    {{-- Sticky name --}}
                                    <td class="p-3 sticky left-0 bg-surface z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] label-sm whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-fg">{{ $user->name }}</span>
                                            <span class="text-[10px] text-fg-subtle">ID: {{ $user->employee_id ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    {{-- Day cells --}}
                                    @foreach($matrixDays as $day)
                                        @php
                                            $key      = $user->id . '_' . $day['date_string'];
                                            $log      = $matrixLogs->get($key)?->first();
                                            $leaveTyp = $matrixLeaves[$key] ?? null;
                                            if ($log) $hadirCount++;
                                        @endphp
                                        <td class="p-1 text-center border-l border-border/60 {{ ($day['is_sunday'] || $day['is_holiday']) ? 'bg-rose-50/25 dark:bg-rose-950/15' : '' }}">
                                            @if($log)
                                                {{-- Hadir — clickable --}}
                                                <button type="button"
                                                    @click="selectedLog = {{ \Illuminate\Support\Js::from([
                                                        'id'               => $log->id,
                                                        'type'             => $log->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar',
                                                        'timestamp'        => \Carbon\Carbon::parse($log->timestamp)->timezone($tzSetting)->translatedFormat('H:i:s, d F Y') . ' ' . $tzLabel,
                                                        'latitude'         => $log->latitude,
                                                        'longitude'        => $log->longitude,
                                                        'accuracy'         => $log->accuracy,
                                                        'ip_address'       => $log->ip_address,
                                                        'work_mode'        => strtoupper($log->work_mode ?? 'office'),
                                                        'risk_score'       => $log->risk_score ?? 0,
                                                        'risk_level'       => $log->risk_level === 'high' ? 'Tinggi' : ($log->risk_level === 'medium' ? 'Sedang' : 'Rendah'),
                                                        'risk_class'       => $log->risk_level,
                                                        'status'           => $log->status === 'approved' ? 'Disetujui' : ($log->status === 'flagged' ? 'Dicurigai' : 'Diproses'),
                                                        'status_class'     => $log->status,
                                                        'is_late'          => $log->is_late,
                                                        'selfie_url'       => $log->selfie_path ? asset('storage/' . $log->selfie_path) : null,
                                                        'notes'            => $log->notes ?? 'Tidak ada catatan tambahan.',
                                                        'branch_name'      => $log->branch->name ?? 'HQ Workspace',
                                                        'device_hash'      => substr(md5($log->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                                                        'employee_name'    => $log->user->name ?? 'Karyawan',
                                                        'resolved_address' => $log->metadata['resolved_address'] ?? null,
                                                    ]) }}; showModal = true; initDetailMap();"
                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-all hover:scale-110 active:scale-95 cursor-pointer
                                                        {{ $log->is_late
                                                            ? 'bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-500/20'
                                                            : 'bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-500/20' }}"
                                                    title="Klik untuk detail · Hadir {{ \Carbon\Carbon::parse($log->timestamp)->format('H:i') }}{{ $log->is_late ? ' (Terlambat)' : '' }}">
                                                    ✓
                                                </button>
                                            @elseif($leaveTyp)
                                                {{-- Cuti --}}
                                                <div class="inline-flex items-center justify-center w-7 h-7 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-600 border border-blue-200 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-500/20 uppercase"
                                                    title="Cuti: {{ ucfirst($leaveTyp) }}">
                                                    C
                                                </div>
                                            @elseif($day['is_sunday'])
                                                <span class="text-[10px] font-bold text-rose-500 dark:text-rose-500/80 select-none">M</span>
                                            @elseif($day['is_holiday'])
                                                <span class="text-[10px] font-bold text-rose-500 dark:text-rose-500/80 select-none cursor-help" title="{{ $day['holiday_name'] }}">H</span>
                                            @else
                                                <span class="text-slate-300 dark:text-slate-700 select-none">–</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    {{-- Total hadir --}}
                                    <td class="p-2 text-center border-l border-border/60">
                                        <span class="inline-flex items-center justify-center w-8 h-6 rounded-md text-[11px] font-bold
                                            {{ $hadirCount > 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400' : 'text-fg-muted' }}">
                                            {{ $hadirCount }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Legend --}}
                <div class="px-4 py-4 border-t border-border flex flex-wrap items-center gap-x-5 gap-y-2 text-xs">
                    <span class="text-fg-muted font-medium">Keterangan:</span>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span><span class="text-fg-muted">Hadir Tepat Waktu</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span><span class="text-fg-muted">Hadir Terlambat</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span><span class="text-fg-muted">Cuti / Izin</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-rose-500 font-bold">M</span><span class="text-fg-muted">Hari Minggu</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-rose-500 font-bold">H</span><span class="text-fg-muted">Hari Libur Nasional</span></div>
                    <div class="flex items-center gap-1.5"><span class="text-slate-400">–</span><span class="text-fg-muted">Tidak Hadir</span></div>
            @endif
        </div>
        @endif

        {{-- Gallery View --}}
        @if($viewMode === 'gallery')
        <div class="space-y-6">
            @if($attendances->isEmpty())
                <div class="card p-12 text-center max-w-sm mx-auto">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-muted text-fg-muted mb-4">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    </div>
                    <h4 class="heading-3">Tidak ada foto ditemukan</h4>
                    <p class="mt-1 label-sm">Gunakan filter yang berbeda atau lakukan absensi self-verification terlebih dahulu.</p>
                </div>
            @else
                @php
                    $tzSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
                    $tzLabel   = 'WIB';
                    if ($tzSetting === 'Asia/Makassar') $tzLabel = 'WITA';
                    if ($tzSetting === 'Asia/Jayapura') $tzLabel = 'WIT';
                @endphp
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($attendances as $attendance)
                        @php
                            $selfieUrl = $attendance->selfie_path ? asset('storage/' . $attendance->selfie_path) : null;
                        @endphp
                        <div class="card overflow-hidden flex flex-col group hover:shadow-lg transition-all duration-300">
                            
                            {{-- Photo Thumbnail --}}
                            <div class="relative aspect-[3/4] w-full bg-slate-950 overflow-hidden border-b border-border">
                                @if($selfieUrl)
                                    <img src="{{ $selfieUrl }}" alt="Absen {{ $attendance->user->name ?? 'Karyawan' }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in"
                                         @click="zoomPhotoUrl = '{{ $selfieUrl }}'">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-fg-muted bg-surface-muted">
                                        <svg class="h-10 w-10 opacity-40 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        <span class="text-xs font-semibold uppercase tracking-wider">No Photo</span>
                                    </div>
                                @endif

                                {{-- Badges overlay --}}
                                <div class="absolute top-3 left-3 right-3 flex justify-between items-start pointer-events-none">
                                    <span class="px-2 py-1 rounded bg-black/60 backdrop-blur-md text-[10px] font-bold text-white uppercase tracking-wider">
                                        {{ $attendance->type === 'checkin' ? 'Masuk' : 'Keluar' }}
                                    </span>
                                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider
                                        {{ $attendance->status === 'approved' ? 'bg-emerald-500/80 text-white' : ($attendance->status === 'flagged' ? 'bg-rose-500/80 text-white' : 'bg-amber-500/80 text-white') }}">
                                        {{ $attendance->status === 'approved' ? 'OK' : ($attendance->status === 'flagged' ? 'Suspicious' : 'Pending') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 flex-grow flex flex-col justify-between space-y-3">
                                <div>
                                    @if($isAdmin)
                                        <h4 class="font-bold text-fg text-sm truncate" title="{{ $attendance->user->name ?? 'Karyawan' }}">
                                            {{ $attendance->user->name ?? 'Karyawan' }}
                                        </h4>
                                        <p class="text-[11px] font-mono text-fg-subtle">ID: #{{ $attendance->user->employee_id ?? 'N/A' }}</p>
                                    @endif
                                    
                                    <div class="mt-2 space-y-1 text-xs text-fg-muted">
                                        <div class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5 flex-shrink-0 text-fg-subtle" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            <span class="font-medium text-fg">{{ $attendance->timestamp->translatedFormat('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 truncate">
                                            <svg class="h-3.5 w-3.5 flex-shrink-0 text-fg-subtle" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span class="truncate" title="{{ $attendance->branch->name ?? 'Office' }}">{{ $attendance->branch->name ?? 'Office' }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Button --}}
                                <div class="pt-3 border-t border-border flex items-center justify-between">
                                    <button type="button"
                                        @click="selectedLog = {{ \Illuminate\Support\Js::from([
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
                                            'selfie_url' => $selfieUrl,
                                            'notes' => $attendance->notes ?? 'Tidak ada catatan tambahan.',
                                            'branch_name' => $attendance->branch->name ?? 'HQ Workspace',
                                            'device_hash' => substr(md5($attendance->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                                            'employee_name' => $attendance->user->name ?? 'Karyawan',
                                            'resolved_address' => $attendance->metadata['resolved_address'] ?? null
                                        ]) }}; showModal = true; initDetailMap();"
                                        class="text-xs font-bold text-primary hover:underline cursor-pointer">
                                        Lihat Detail
                                    </button>
                                    
                                    @if($selfieUrl)
                                        <button type="button" @click="zoomPhotoUrl = '{{ $selfieUrl }}'" class="p-1 rounded hover:bg-surface-muted text-fg-subtle hover:text-fg cursor-pointer" title="Perbesar Foto">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7" /></svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6 pt-5 border-t border-border">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
        @endif

    </div>

    <x-attendance.detail-modal :is-admin="$isAdmin" />

    {{-- Fullscreen Photo Zoom Lightbox Modal --}}
    <div x-show="zoomPhotoUrl" x-cloak class="fixed inset-0 z-[150] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4"
        @keydown.escape.window="zoomPhotoUrl = null"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <!-- Close Button -->
        <button @click="zoomPhotoUrl = null" class="absolute top-5 right-5 z-[160] flex items-center justify-center p-3 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors cursor-pointer active:scale-95">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>

        <!-- Main Photo Box -->
        <div class="relative max-w-4xl w-full h-full max-h-[85vh] flex items-center justify-center" @click.away="zoomPhotoUrl = null">
            <img :src="zoomPhotoUrl" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl border border-white/10"
                x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0">
        </div>
    </div>

    {{-- Toast notification --}}
    <div x-show="toast.show" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 z-50 max-w-sm w-full card shadow-md p-4 flex items-center gap-3.5">
        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl text-white"
            :class="toast.type === 'info' ? 'bg-info' : 'bg-success'">
            <template x-if="toast.type === 'info'">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </template>
            <template x-if="toast.type !== 'info'">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </template>
        </span>
        <div class="flex-grow">
            <span class="text-xs font-semibold text-primary block">Notifikasi</span>
            <p class="text-sm font-medium text-fg mt-0.5" x-text="toast.message"></p>
        </div>
        <button @click="toast.show = false" class="text-fg-subtle hover:text-fg flex-shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

</div>
