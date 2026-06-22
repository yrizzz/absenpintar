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
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <h3 class="label-xs uppercase tracking-wide">Filter Catatan Log</h3>
                {{-- Tab Switch --}}
                <div class="inline-flex rounded-lg p-0.5 bg-surface-muted border border-border">
                    <button type="button" wire:click="$set('viewMode','table')"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all
                               {{ $viewMode === 'table' ? 'bg-surface text-primary shadow-sm' : 'text-fg-subtle hover:text-fg' }}">
                        Versi Tabel
                    </button>
                    <button type="button" wire:click="$set('viewMode','matrix')"
                        class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all
                               {{ $viewMode === 'matrix' ? 'bg-surface text-primary shadow-sm' : 'text-fg-subtle hover:text-fg' }}">
                        Versi Ceklis Bulanan
                    </button>
                </div>
            </div>

            @if($viewMode === 'table')
                {{-- Table filters --}}
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
                </div>
            @endif
        </div>
        @endif

    </div>

    <x-attendance.detail-modal :is-admin="$isAdmin" />

</div>
