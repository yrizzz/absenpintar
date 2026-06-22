<!-- Inject Leaflet Assets directly to avoid bundle overhead -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<div class="py-8 min-h-screen" x-data="{
    selectedLog: null,
    showModal: false,
    selectedDevice: null,
    showDeviceModal: false,
    deviceViewMode: @entangle('device_view_mode'),
    viewMode: @entangle('view_mode'),
    init() {
        this.$watch('showModal', value => {
            document.body.style.overflow = (value || this.showDeviceModal) ? 'hidden' : '';
        });
        this.$watch('showDeviceModal', value => {
            document.body.style.overflow = (value || this.showModal) ? 'hidden' : '';
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

        @php
            $sortBtn = function ($field, $label) use ($sortField, $sortDirection) {
                $active = $sortField === $field;
                $arrow = !$active
                    ? '<svg class="h-3.5 w-3.5 opacity-40" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>'
                    : ($sortDirection === 'asc'
                        ? '<svg class="h-3.5 w-3.5 text-primary" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" /></svg>'
                        : '<svg class="h-3.5 w-3.5 text-primary" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>');
                return '<button type="button" wire:click="sortBy(\'' . $field . '\')" class="inline-flex items-center gap-1 transition-colors hover:text-fg ' . ($active ? 'text-fg' : '') . '">' . $label . $arrow . '</button>';
            };
        @endphp

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="heading-1">Laporan &amp; Telemetri Kehadiran</h1>
            <p class="mt-1 label-sm">Analisis koordinat tim, akurasi GPS, distribusi risiko, dan kepatuhan perimeter keamanan.</p>
        </div>

        {{-- KPIs --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <div class="card p-6">
                <span class="label-xs uppercase tracking-wide">Rata-rata Akurasi GPS</span>
                <div class="heading-value mt-2">± {{ $avg_accuracy }} <span class="label-sm font-normal">meter</span></div>
                <div class="mt-4 text-xs font-medium text-success flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Presisi GPS terkalibrasi
                </div>
            </div>
            <div class="card p-6">
                <span class="label-xs uppercase tracking-wide">Total Log Hadir (WFO)</span>
                <div class="heading-value mt-2">{{ $total_presence_logs }} <span class="label-sm font-normal">absen</span></div>
                <div class="mt-4 text-xs font-medium text-success flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    Semua data tervalidasi
                </div>
            </div>
            <div class="card p-6">
                <span class="label-xs uppercase tracking-wide">Deteksi Pelanggaran</span>
                <div class="heading-value mt-2 {{ $risk_events > 0 ? 'text-danger' : 'text-success' }}">{{ $risk_events }} <span class="label-sm font-normal text-fg-muted">kasus</span></div>
                <div class="mt-4 text-xs font-medium text-fg-muted">Tingkat keamanan: {{ $risk_events > 0 ? 'Waspada' : 'Sangat aman' }}</div>
            </div>
            <div class="card p-6">
                <span class="label-xs uppercase tracking-wide">Akumulasi Lembur</span>
                <div class="heading-value mt-2">{{ $overtime_hours }} <span class="label-sm font-normal">jam</span></div>
                <div class="mt-4 text-xs font-medium text-primary">Terhitung otomatis</div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 lg:col-span-1">
                <h3 class="heading-3 mb-1">Kehadiran Mingguan</h3>
                <p class="label-xs mb-4">Check-in per hari minggu ini</p>
                <div wire:ignore class="relative h-56" x-data="{ init() {
                    if (typeof Chart === 'undefined') return;
                    new Chart(this.$refs.c, {
                        type: 'bar',
                        data: { labels: ['Sen','Sel','Rab','Kam','Jum'], datasets: [{ data: {{ json_encode($weeklyCounts) }}, backgroundColor: '#2563eb', borderRadius: 6, maxBarThickness: 38 }] },
                        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } },
                            scales: { x: { grid: { display: false }, ticks: { color: '#94a3b8' } }, y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.15)' }, ticks: { color: '#94a3b8', precision: 0 } } } }
                    });
                } }">
                    <canvas x-ref="c"></canvas>
                </div>
            </div>

            <div class="card p-6 lg:col-span-1">
                <h3 class="heading-3 mb-1">Distribusi Risiko</h3>
                <p class="label-xs mb-4">Sebaran skor kerawanan absensi</p>
                <div wire:ignore class="relative h-56" x-data="{ init() {
                    if (typeof Chart === 'undefined') return;
                    new Chart(this.$refs.c, {
                        type: 'doughnut',
                        data: { labels: ['Rendah','Sedang','Tinggi'], datasets: [{ data: {{ json_encode($riskDistribution) }}, backgroundColor: ['#059669','#d97706','#e11d48'], borderWidth: 0 }] },
                        options: { responsive: true, maintainAspectRatio: false, cutout: '64%', plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8', usePointStyle: true, padding: 14 } } } }
                    });
                } }">
                    <canvas x-ref="c"></canvas>
                </div>
            </div>

            <div class="card p-6 lg:col-span-1">
                <h3 class="heading-3 mb-1">Ketepatan Waktu</h3>
                <p class="label-xs mb-4">Tepat waktu vs terlambat</p>
                <div wire:ignore class="relative h-56" x-data="{ init() {
                    if (typeof Chart === 'undefined') return;
                    new Chart(this.$refs.c, {
                        type: 'doughnut',
                        data: { labels: ['Tepat Waktu','Terlambat'], datasets: [{ data: [{{ (int) $onTime }}, {{ (int) $late }}], backgroundColor: ['#059669','#e11d48'], borderWidth: 0 }] },
                        options: { responsive: true, maintainAspectRatio: false, cutout: '64%', plugins: { legend: { position: 'bottom', labels: { color: '#94a3b8', usePointStyle: true, padding: 14 } } } }
                    });
                } }">
                    <canvas x-ref="c"></canvas>
                </div>
            </div>
        </div>

        {{-- Generator + device audit --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="card p-6 lg:col-span-1">
                <h3 class="heading-3 mb-6">Penyusun Laporan</h3>
                @if (session()->has('success'))
                    <div class="mb-4 rounded-lg border border-success/30 bg-success-soft p-3.5 text-xs font-medium text-success">{{ session('success') }}</div>
                @endif
                <form wire:submit.prevent="generateReport" class="space-y-5">
                    <div class="space-y-1.5">
                        <label class="label">Tipe Laporan</label>
                        <select wire:model.live="report_type" class="cursor-pointer">
                            <option value="presence_summary">Ringkasan Kehadiran Biometrik</option>
                            <option value="coordinates_log">Telemetri Pelanggaran Geofence</option>
                            <option value="leaves_audit">Ledger Cuti Tahunan &amp; Lembur</option>
                            <option value="system_logs">Audit Sidik Jari Perangkat</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="label">Rentang Waktu</label>
                        <select wire:model.live="report_period" class="cursor-pointer">
                            <option value="weekly">Minggu Ini</option>
                            <option value="monthly">Bulan Ini</option>
                            <option value="quarterly">Kuartal Q2 2026</option>
                            <option value="annual">Tahunan FY 2026</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary btn-sm w-full">Susun Data Telemetri</button>
                </form>
                <div class="mt-6 pt-6 border-t border-border space-y-2">
                    <a href="{{ route('reports.print', [
                        'type' => $report_type,
                        'period' => $report_period,
                        'user_id' => $filter_user_id,
                        'branch_id' => $filter_branch_id,
                        'start_date' => $filter_start_date,
                        'end_date' => $filter_end_date,
                        'selected_ids' => !empty($selectedLogs) ? implode(',', $selectedLogs) : null
                    ]) }}" target="_blank" class="btn-secondary btn-sm w-full">
                        <svg class="h-4 w-4 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Cetak / Simpan PDF
                    </a>
                    <button wire:click="downloadExcel" class="btn-secondary btn-sm w-full">
                        <svg class="h-4 w-4 text-success" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Unduh Excel (.xlsx)
                    </button>
                </div>
            </div>

            <div class="card p-6 lg:col-span-2 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="heading-3">Audit Integritas Perangkat</h3>
                        
                        {{-- View Mode Toggle --}}
                        <div class="inline-flex rounded-lg p-0.5 bg-surface-muted border border-border">
                            <button type="button" @click="deviceViewMode = 'list'"
                                class="px-2 py-1 rounded-md text-[10px] font-semibold transition-all flex items-center gap-1"
                                :class="deviceViewMode === 'list' ? 'bg-surface text-primary shadow-sm font-bold' : 'text-fg-subtle hover:text-fg'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 5.25h16.5m-16.5-10.5h16.5"/></svg>
                                List
                            </button>
                            <button type="button" @click="deviceViewMode = 'grid'"
                                class="px-2 py-1 rounded-md text-[10px] font-semibold transition-all flex items-center gap-1"
                                :class="deviceViewMode === 'grid' ? 'bg-surface text-primary shadow-sm font-bold' : 'text-fg-subtle hover:text-fg'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                                Grid
                            </button>
                        </div>
                    </div>
 
                    @if($latest_devices->isEmpty())
                        <div class="text-sm text-fg-muted text-center py-8 rounded-xl bg-surface-muted">Belum ada telemetri perangkat terdaftar.</div>
                    @else
                        {{-- List View (Table) --}}
                        <div x-show="deviceViewMode === 'list'" class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-border label-xs uppercase tracking-wide">
                                        <th class="pb-2 text-[10px] text-fg-muted">Karyawan</th>
                                        <th class="pb-2 text-[10px] text-fg-muted">Browser & OS</th>
                                        <th class="pb-2 text-[10px] text-fg-muted">Platform</th>
                                        <th class="pb-2 text-[10px] text-fg-muted">Status</th>
                                        <th class="pb-2 text-[10px] text-fg-muted text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border/40">
                                    @foreach($latest_devices as $d)
                                        <tr class="hover:bg-surface-muted/50 transition-colors">
                                            <td class="py-2 text-xs font-medium text-fg">{{ $d->user->name ?? 'N/A' }}</td>
                                            <td class="py-2 text-xs text-fg-subtle">{{ $d->browser }} on {{ $d->os }}</td>
                                            <td class="py-2 text-xs text-fg-subtle">{{ $d->platform ?? 'Desktop' }}</td>
                                            <td class="py-2">
                                                @if($d->trusted)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-500/20">Tepercaya</span>
                                                @else
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-500/20">Unverified</span>
                                                @endif
                                            </td>
                                            <td class="py-2 text-right">
                                                <button type="button" @click="selectedDevice = {{ \Illuminate\Support\Js::from([
                                                    'id' => $d->id,
                                                    'user_name' => $d->user->name ?? 'N/A',
                                                    'employee_id' => $d->user->employee_id ?? 'N/A',
                                                    'device_hash' => $d->device_hash,
                                                    'browser' => $d->browser,
                                                    'os' => $d->os,
                                                    'platform' => $d->platform ?? 'Desktop/Web',
                                                    'timezone' => $d->timezone ?? 'Asia/Jakarta',
                                                    'language' => $d->language ?? 'id-ID',
                                                    'screen_resolution' => $d->screen_resolution ?? '1920x1080',
                                                    'hardware_concurrency' => $d->hardware_concurrency ?? '8',
                                                    'gpu_info' => $d->gpu_info ?? 'Intel / Apple GPU',
                                                    'trusted' => $d->trusted,
                                                    'last_used' => $d->last_used_at ? \Carbon\Carbon::parse($d->last_used_at)->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->translatedFormat('d F Y, H:i:s') : 'Belum Pernah'
                                                ]) }}; showDeviceModal = true;"
                                                class="text-[11px] font-bold text-primary hover:underline cursor-pointer">
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Grid View (Cards) --}}
                        <div x-show="deviceViewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 gap-3" x-cloak>
                            @foreach($latest_devices as $d)
                                <div @click="selectedDevice = {{ \Illuminate\Support\Js::from([
                                        'id' => $d->id,
                                        'user_name' => $d->user->name ?? 'N/A',
                                        'employee_id' => $d->user->employee_id ?? 'N/A',
                                        'device_hash' => $d->device_hash,
                                        'browser' => $d->browser,
                                        'os' => $d->os,
                                        'platform' => $d->platform ?? 'Desktop/Web',
                                        'timezone' => $d->timezone ?? 'Asia/Jakarta',
                                        'language' => $d->language ?? 'id-ID',
                                        'screen_resolution' => $d->screen_resolution ?? '1920x1080',
                                        'hardware_concurrency' => $d->hardware_concurrency ?? '8',
                                        'gpu_info' => $d->gpu_info ?? 'Intel / Apple GPU',
                                        'trusted' => $d->trusted,
                                        'last_used' => $d->last_used_at ? \Carbon\Carbon::parse($d->last_used_at)->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->translatedFormat('d F Y, H:i:s') : 'Belum Pernah'
                                    ]) }}; showDeviceModal = true;"
                                    class="p-3 rounded-lg border border-border bg-surface hover:bg-surface-muted/50 cursor-pointer transition-all hover:scale-[1.02] flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-start justify-between">
                                            <span class="text-xs font-semibold text-fg line-clamp-1">{{ $d->user->name ?? 'N/A' }}</span>
                                            @if($d->trusted)
                                                <span class="w-2 h-2 rounded-full bg-emerald-500" title="Tepercaya"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-amber-500" title="Tidak Terverifikasi"></span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-fg-subtle mt-1">{{ $d->browser }} on {{ $d->os }}</p>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between text-[9px] text-fg-muted">
                                        <span>{{ $d->platform ?? 'Desktop' }}</span>
                                        <span>{{ $d->last_used_at ? \Carbon\Carbon::parse($d->last_used_at)->diffForHumans() : 'Belum Pernah' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>



        {{-- Recap: filters + sort + pagination --}}
        <div class="card p-6 sm:p-8 mt-8">            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h3 class="heading-3 flex items-center gap-2">
                        Rekapitulasi Kehadiran
                        <span x-show="viewMode === 'list' && {{ count($selectedLogs) > 0 ? 'true' : 'false' }}" class="badge-info" x-cloak>{{ count($selectedLogs) }} terpilih</span>
                    </h3>
                    <p class="label-sm mt-1">Saring, urutkan, dan ekspor log kehadiran lengkap beserta bukti biometrik.</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Toggle view mode --}}
                    <div class="inline-flex rounded-lg p-0.5 bg-surface-muted border border-border">
                        <button type="button" @click="viewMode = 'grid'" 
                            class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all flex items-center gap-1.5"
                            :class="viewMode === 'grid' ? 'bg-surface text-primary shadow-sm font-bold' : 'text-fg-subtle hover:text-fg'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25a2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                            Matriks Bulanan
                        </button>
                        <button type="button" @click="viewMode = 'list'" 
                            class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all flex items-center gap-1.5"
                            :class="viewMode === 'list' ? 'bg-surface text-primary shadow-sm font-bold' : 'text-fg-subtle hover:text-fg'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 5.25h16.5m-16.5-10.5h16.5"/></svg>
                            Daftar Log Harian
                        </button>
                    </div>
                    <button x-show="viewMode === 'list'" wire:click="downloadExcel" class="btn-success btn-sm" x-cloak>
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Ekspor Terpilih
                    </button>
                </div>
            </div>
            {{-- Filter bar --}}
            <div class="rounded-xl border border-border bg-surface-muted p-4 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    {{-- Cari Karyawan --}}
                    <div class="space-y-1">
                        <label class="label text-xs font-semibold text-fg-muted">Cari Karyawan</label>
                        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Nama atau ID karyawan…"
                            class="w-full rounded-lg border border-border bg-surface text-fg placeholder-fg-subtle text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 px-3">
                    </div>

                    {{-- Select Karyawan --}}
                    <div class="space-y-1">
                        <label class="label text-xs font-semibold text-fg-muted">Karyawan</label>
                        <select wire:model.live="filter_user_id"
                            class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                            <option value="">Semua Karyawan</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Select Cabang --}}
                    <div class="space-y-1">
                        <label class="label text-xs font-semibold text-fg-muted">Cabang</label>
                        <select wire:model.live="filter_branch_id"
                            class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                            <option value="">Semua Cabang</option>
                            @foreach($branches as $br)
                                <option value="{{ $br->id }}">{{ $br->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="contents" x-show="viewMode === 'grid'">
                        {{-- Bulan --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Bulan</label>
                            <select wire:model.live="matrix_month"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                                @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tahun --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Tahun</label>
                            <select wire:model.live="matrix_year"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                                @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Per Halaman --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Per Halaman</label>
                            <select wire:model.live="perPage"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                                <option value="15">15 data</option>
                                <option value="30">30 data</option>
                                <option value="50">50 data</option>
                            </select>
                        </div>
                    </div>
                    <div class="contents" x-show="viewMode === 'list'" x-cloak>
                        {{-- Tipe --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Tipe Absen</label>
                            <select wire:model.live="filter_type"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                                <option value="">Semua Tipe</option>
                                <option value="checkin">Masuk</option>
                                <option value="checkout">Keluar</option>
                            </select>
                        </div>

                        {{-- Kerawanan --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Kerawanan</label>
                            <select wire:model.live="filter_risk"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                                <option value="">Semua Tingkat</option>
                                <option value="low">Rendah</option>
                                <option value="medium">Sedang</option>
                                <option value="high">Tinggi</option>
                            </select>
                        </div>

                        {{-- Status --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Status Validasi</label>
                            <select wire:model.live="filter_status"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 cursor-pointer px-3">
                                <option value="">Semua Status</option>
                                <option value="approved">Disetujui</option>
                                <option value="pending">Diproses</option>
                                <option value="flagged">Dicurigai</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                        </div>

                        {{-- Tanggal Mulai --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Tanggal Mulai</label>
                            <input type="date" wire:model.live="filter_start_date"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 px-3">
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div class="space-y-1">
                            <label class="label text-xs font-semibold text-fg-muted">Tanggal Selesai</label>
                            <input type="date" wire:model.live="filter_end_date"
                                class="w-full rounded-lg border border-border bg-surface text-fg text-sm focus:border-primary focus:ring-1 focus:ring-primary h-10 px-3">
                        </div>
                    </div>

                    {{-- Reset Button --}}
                    <div class="flex items-end">
                        <button type="button" wire:click="resetFilters" 
                            class="w-full h-10 flex items-center justify-center gap-2 rounded-lg border border-border bg-surface hover:bg-surface-muted text-fg text-sm font-semibold transition-all active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4 text-fg-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                            Reset Filter
                        </button>
                    </div>
                </div>
            </div>

            {{-- Table Render --}}
            <div x-show="viewMode === 'grid'">
                @if($matrixUsers->isEmpty())
                    <div class="text-sm text-fg-muted text-center py-12 rounded-xl bg-surface-muted">Tidak ada data karyawan yang cocok.</div>
                @else
                    <div class="overflow-x-auto border border-border rounded-xl">
                        <table class="w-full text-left border-collapse table-fixed min-w-[1200px]">
                            <thead>
                                <tr class="border-b border-border bg-surface-muted label-xs uppercase tracking-wide">
                                    {{-- Sticky Name Column Header --}}
                                    <th class="p-3 sticky left-0 bg-surface-muted z-20 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] w-[220px]">
                                        Nama Karyawan
                                    </th>
                                    {{-- Date Columns Header --}}
                                    @foreach($matrixDays as $day)
                                        <th class="p-2 text-center border-l border-border/60 {{ ($day['is_sunday'] || $day['is_holiday']) ? 'bg-rose-50/50 dark:bg-rose-950/20 text-rose-500 font-bold' : '' }} w-[40px]"
                                            @if($day['is_holiday']) title="{{ $day['holiday_name'] }}" @endif>
                                            <span class="block text-[10px] opacity-70">{{ $day['day_name'] }}</span>
                                            <span class="block text-xs mt-0.5 {{ $day['is_holiday'] ? 'underline decoration-rose-500 decoration-dotted cursor-help' : '' }}">{{ $day['day'] }}</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border/60">
                                @foreach($matrixUsers as $user)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-white/[0.02] transition-colors">
                                        {{-- Sticky Name Cell --}}
                                        <td class="p-3 sticky left-0 bg-surface z-10 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] label-sm whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-fg">{{ $user->name }}</span>
                                                <span class="text-[10px] text-fg-subtle">ID: {{ $user->employee_id ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        {{-- Date cells --}}
                                        @foreach($matrixDays as $day)
                                            @php
                                                $key = $user->id . '_' . $day['date_string'];
                                                $log = $matrixLogs->get($key)?->first();
                                                $leaveType = $matrixLeaves[$key] ?? null;
                                                $tzSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
                                                $tzLabel = 'WIB';
                                                if ($tzSetting === 'Asia/Makassar') $tzLabel = 'WITA';
                                                if ($tzSetting === 'Asia/Jayapura') $tzLabel = 'WIT';
                                            @endphp
                                            <td class="p-1 text-center border-l border-border/60 {{ ($day['is_sunday'] || $day['is_holiday']) ? 'bg-rose-50/25 dark:bg-rose-950/15' : '' }}">
                                                @if($log)
                                                    {{-- Present --}}
                                                    <button type="button" @click="selectedLog = {{ \Illuminate\Support\Js::from([
                                                        'id' => $log->id,
                                                        'type' => $log->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar',
                                                        'timestamp' => \Carbon\Carbon::parse($log->timestamp)->timezone($tzSetting)->translatedFormat('H:i:s, d F Y') . ' ' . $tzLabel,
                                                        'latitude' => $log->latitude,
                                                        'longitude' => $log->longitude,
                                                        'accuracy' => $log->accuracy,
                                                        'ip_address' => $log->ip_address,
                                                        'work_mode' => strtoupper($log->work_mode ?? 'office'),
                                                        'risk_score' => $log->risk_score ?? 0,
                                                        'risk_level' => $log->risk_level === 'high' ? 'Tinggi' : ($log->risk_level === 'medium' ? 'Sedang' : 'Rendah'),
                                                        'risk_class' => $log->risk_level,
                                                        'status' => $log->status === 'approved' ? 'Disetujui' : ($log->status === 'flagged' ? 'Dicurigai' : 'Diproses'),
                                                        'status_class' => $log->status,
                                                        'is_late' => $log->is_late,
                                                        'selfie_url' => $log->selfie_path ? asset('storage/' . $log->selfie_path) : null,
                                                        'notes' => $log->notes ?? 'Tidak ada catatan tambahan.',
                                                        'branch_name' => $log->branch->name ?? 'HQ Workspace',
                                                        'device_hash' => substr(md5($log->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                                                        'employee_name' => $log->user->name ?? 'Karyawan',
                                                        'resolved_address' => $log->metadata['resolved_address'] ?? null
                                                    ]) }}; showModal = true; initDetailMap();"
                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold transition-all hover:scale-110 active:scale-95 cursor-pointer
                                                        {{ $log->is_late 
                                                            ? 'bg-amber-50 text-amber-600 border border-amber-250 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-500/20' 
                                                            : 'bg-emerald-50 text-emerald-600 border border-emerald-250 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-500/20' }}"
                                                        title="Klik untuk detail. Hadir pada {{ \Carbon\Carbon::parse($log->timestamp)->format('H:i') }}{{ $log->is_late ? ' (Terlambat)' : '' }}">
                                                        ✓
                                                    </button>
                                                @elseif($leaveType)
                                                    {{-- On Leave --}}
                                                    <div class="inline-flex items-center justify-center w-7 h-7 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-600 border border-blue-205 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-500/20 uppercase"
                                                        title="Cuti: {{ ucfirst($leaveType) }}">
                                                        C
                                                    </div>
                                                @elseif($day['is_sunday'])
                                                    {{-- Sunday --}}
                                                    <span class="text-[10px] font-bold text-rose-500 dark:text-rose-500/80 select-none">M</span>
                                                @elseif($day['is_holiday'])
                                                    {{-- National Holiday --}}
                                                    <span class="text-[10px] font-bold text-rose-500 dark:text-rose-500/80 select-none cursor-help" title="{{ $day['holiday_name'] }}">H</span>
                                                @else
                                                    {{-- Absent / Empty --}}
                                                    <span class="text-slate-350 dark:text-slate-700 select-none">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Legend & Pagination --}}
                    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs">
                            <span class="text-slate-400 dark:text-slate-500 font-medium">Keterangan:</span>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-slate-600 dark:text-slate-400">Hadir Tepat Waktu</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-slate-600 dark:text-slate-400">Hadir Terlambat</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <span class="text-slate-600 dark:text-slate-400">Cuti / Izin Disetujui</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-rose-500 font-bold">M</span>
                                <span class="text-slate-600 dark:text-slate-400">Hari Minggu</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-rose-500 font-bold">H</span>
                                <span class="text-slate-600 dark:text-slate-400">Hari Libur Nasional</span>
                            </div>
                        </div>
                        <div>{{ $matrixUsers->links() }}</div>
                    </div>
                @endif
            </div>

            <div x-show="viewMode === 'list'" x-cloak>
                {{-- List View --}}
                @if($recapLogs->isEmpty())
                    <div class="text-sm text-fg-muted text-center py-12 rounded-xl bg-surface-muted">Tidak ada kecocokan data untuk filter saat ini.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-border label-xs uppercase tracking-wide">
                                    <th class="pb-3 pr-3" style="width:40px;"><input type="checkbox" wire:model.live="selectAll" class="rounded text-primary cursor-pointer" style="min-height:auto;width:auto;"></th>
                                    <th class="pb-3 pr-3" style="width:64px;">Foto</th>
                                    <th class="pb-3 pr-3">Karyawan</th>
                                    <th class="pb-3 pr-3">{!! $sortBtn('timestamp', 'Waktu') !!}</th>
                                    <th class="pb-3 pr-3">{!! $sortBtn('type', 'Tipe') !!}</th>
                                    <th class="pb-3 pr-3">Lokasi</th>
                                    <th class="pb-3 pr-3">{!! $sortBtn('accuracy', 'Presisi') !!}</th>
                                    <th class="pb-3 pr-3">{!! $sortBtn('risk_level', 'Kerawanan') !!}</th>
                                    <th class="pb-3 pr-3">{!! $sortBtn('status', 'Status') !!}</th>
                                    <th class="pb-3 text-right">{!! $sortBtn('is_late', 'Ketepatan') !!}</th>
                                    <th class="pb-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($recapLogs as $log)
                                    <tr class="hover:bg-surface-muted transition-colors">
                                        <td class="py-3 pr-3"><input type="checkbox" value="{{ $log->id }}" wire:model.live="selectedLogs" class="rounded text-primary cursor-pointer" style="min-height:auto;width:auto;"></td>
                                        <td class="py-3 pr-3">
                                            @if($log->selfie_path)
                                                <div class="h-10 w-10 overflow-hidden rounded-lg border border-border bg-surface-muted"><img src="{{ asset('storage/' . $log->selfie_path) }}" class="w-full h-full object-cover" style="transform: scaleX(-1);"></div>
                                            @else
                                                <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-border bg-surface text-xs text-fg-subtle">—</div>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-3 label-sm">
                                            <span class="block font-medium text-fg">{{ $log->user->name ?? 'N/A' }}</span>
                                            <span class="block label-xs">ID: {{ $log->user->employee_id ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-3 pr-3 label-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($log->timestamp)->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->translatedFormat('d M Y, H:i') }}</td>
                                        <td class="py-3 pr-3">
                                            @if($log->type === 'checkin')<span class="badge-rect-success">Masuk</span>@else<span class="badge-rect-info">Keluar</span>@endif
                                        </td>
                                        <td class="py-3 pr-3 label-sm">{{ $log->branch->name ?? 'Mobile / WFH' }}</td>
                                        <td class="py-3 pr-3 label-sm whitespace-nowrap">± {{ $log->accuracy ?? '0' }}m</td>
                                        <td class="py-3 pr-3">
                                            @if($log->risk_level === 'high')<span class="badge-rect-danger">Tinggi</span>
                                            @elseif($log->risk_level === 'medium')<span class="badge-rect-warning">Sedang</span>
                                            @else<span class="badge-rect-success">Rendah</span>@endif
                                        </td>
                                        <td class="py-3 pr-3">
                                            @if($log->status === 'approved')<span class="badge-rect-success">Disetujui</span>
                                            @elseif($log->status === 'flagged')<span class="badge-rect-danger">Dicurigai</span>
                                            @elseif($log->status === 'rejected')<span class="badge-rect-danger">Ditolak</span>
                                            @else<span class="badge-rect-warning">Diproses</span>@endif
                                        </td>
                                        <td class="py-3 text-right">
                                            @if($log->is_late)<span class="badge-rect-danger">Terlambat</span>@else<span class="badge-rect-success">Tepat waktu</span>@endif
                                        </td>
                                        <td class="py-3 text-right whitespace-nowrap">
                                            @php
                                                $tzSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
                                                $tzLabel = 'WIB';
                                                if ($tzSetting === 'Asia/Makassar') $tzLabel = 'WITA';
                                                if ($tzSetting === 'Asia/Jayapura') $tzLabel = 'WIT';
                                            @endphp
                                            <button type="button" @click="selectedLog = {{ \Illuminate\Support\Js::from([
                                                'id' => $log->id,
                                                'type' => $log->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar',
                                                'timestamp' => \Carbon\Carbon::parse($log->timestamp)->timezone($tzSetting)->translatedFormat('H:i:s, d F Y') . ' ' . $tzLabel,
                                                'latitude' => $log->latitude,
                                                'longitude' => $log->longitude,
                                                'accuracy' => $log->accuracy,
                                                'ip_address' => $log->ip_address,
                                                'work_mode' => strtoupper($log->work_mode ?? 'office'),
                                                'risk_score' => $log->risk_score ?? 0,
                                                'risk_level' => $log->risk_level === 'high' ? 'Tinggi' : ($log->risk_level === 'medium' ? 'Sedang' : 'Rendah'),
                                                'risk_class' => $log->risk_level,
                                                'status' => $log->status === 'approved' ? 'Disetujui' : ($log->status === 'flagged' ? 'Dicurigai' : 'Diproses'),
                                                'status_class' => $log->status,
                                                'is_late' => $log->is_late,
                                                'selfie_url' => $log->selfie_path ? asset('storage/' . $log->selfie_path) : null,
                                                'notes' => $log->notes ?? 'Tidak ada catatan tambahan.',
                                                'branch_name' => $log->branch->name ?? 'HQ Workspace',
                                                'device_hash' => substr(md5($log->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                                                'employee_name' => $log->user->name ?? 'Karyawan',
                                                'resolved_address' => $log->metadata['resolved_address'] ?? null
                                            ]) }}; showModal = true; initDetailMap();"
                                            class="text-xs font-semibold text-primary hover:underline cursor-pointer">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">{{ $recapLogs->links() }}</div>
                @endif
            </div>
        </div>
    </div>
    <x-attendance.detail-modal :is-admin="true" />

    {{-- Device / Login Telemetry Detail Modal --}}
    <div x-show="showDeviceModal" 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
        @click.away="showDeviceModal = false"
        @keydown.escape.window="showDeviceModal = false">
        
        <div class="relative w-full max-w-lg overflow-hidden rounded-xl border border-border bg-surface shadow-xl flex flex-col max-h-[85vh]">
            {{-- Modal Header --}}
            <div class="p-4 border-b border-border flex items-center justify-between bg-surface-muted">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/></svg>
                    <div>
                        <h4 class="font-bold text-fg text-sm">Detail Telemetri Perangkat</h4>
                        <p class="text-[10px] text-fg-subtle">Informasi fingerprint unik perangkat login</p>
                    </div>
                </div>
                <button type="button" @click="showDeviceModal = false" class="p-1 rounded-lg text-fg-subtle hover:bg-surface-muted hover:text-fg transition-all cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-5 space-y-4 overflow-y-auto" x-data="{ copied: false }">
                <template x-if="selectedDevice">
                    <div class="space-y-4">
                        {{-- Karyawan Info --}}
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-surface-muted border border-border/60">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                                <span x-text="(selectedDevice.user_name || '').charAt(0)"></span>
                            </div>
                            <div>
                                <h5 class="text-xs font-semibold text-fg" x-text="selectedDevice.user_name"></h5>
                                <p class="text-[10px] text-fg-subtle">ID Karyawan: <span x-text="selectedDevice.employee_id"></span></p>
                            </div>
                        </div>

                        {{-- Technical Specs Grid --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div class="p-2.5 rounded-lg border border-border/50 bg-surface">
                                <span class="text-[10px] text-fg-muted block">Sistem Operasi</span>
                                <span class="text-xs font-semibold text-fg block mt-0.5" x-text="selectedDevice.os"></span>
                            </div>
                            <div class="p-2.5 rounded-lg border border-border/50 bg-surface">
                                <span class="text-[10px] text-fg-muted block">Browser</span>
                                <span class="text-xs font-semibold text-fg block mt-0.5" x-text="selectedDevice.browser"></span>
                            </div>
                            <div class="p-2.5 rounded-lg border border-border/50 bg-surface">
                                <span class="text-[10px] text-fg-muted block">Platform</span>
                                <span class="text-xs font-semibold text-fg block mt-0.5" x-text="selectedDevice.platform"></span>
                            </div>
                            <div class="p-2.5 rounded-lg border border-border/50 bg-surface">
                                <span class="text-[10px] text-fg-muted block">Zona Waktu</span>
                                <span class="text-xs font-semibold text-fg block mt-0.5" x-text="selectedDevice.timezone"></span>
                            </div>
                            <div class="p-2.5 rounded-lg border border-border/50 bg-surface">
                                <span class="text-[10px] text-fg-muted block">Resolusi Layar</span>
                                <span class="text-xs font-semibold text-fg block mt-0.5" x-text="selectedDevice.screen_resolution"></span>
                            </div>
                            <div class="p-2.5 rounded-lg border border-border/50 bg-surface">
                                <span class="text-[10px] text-fg-muted block">Bahasa</span>
                                <span class="text-xs font-semibold text-fg block mt-0.5" x-text="selectedDevice.language"></span>
                            </div>
                        </div>

                        {{-- Advanced Telemetry --}}
                        <div class="space-y-2 pt-2">
                            <h6 class="text-[11px] font-bold uppercase tracking-wider text-fg-muted">Detail Hardware</h6>
                            <div class="divide-y divide-border/40 border-t border-b border-border/40">
                                <div class="flex items-center justify-between py-2 text-xs">
                                    <span class="text-fg-subtle">Core CPU</span>
                                    <span class="font-semibold text-fg" x-text="selectedDevice.hardware_concurrency"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 text-xs">
                                    <span class="text-fg-subtle">Informasi GPU</span>
                                    <span class="font-semibold text-fg text-right max-w-[250px] truncate" x-text="selectedDevice.gpu_info" :title="selectedDevice.gpu_info"></span>
                                </div>
                                <div class="flex items-center justify-between py-2 text-xs">
                                    <span class="text-fg-subtle">Status Verifikasi</span>
                                    <template x-if="selectedDevice.trusted">
                                        <span class="text-emerald-500 font-bold">Tepercaya (Device Terdaftar)</span>
                                    </template>
                                    <template x-if="!selectedDevice.trusted">
                                        <span class="text-amber-500 font-bold">Tidak Terverifikasi (Kredensial Baru)</span>
                                    </template>
                                </div>
                                <div class="flex items-center justify-between py-2 text-xs">
                                    <span class="text-fg-subtle">Terakhir Aktif</span>
                                    <span class="font-medium text-fg" x-text="selectedDevice.last_used"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Fingerprint Hash --}}
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-fg-muted block">Device Hash (Fingerprint ID)</label>
                            <div class="flex items-center gap-2 p-2 rounded bg-slate-900 text-slate-100 font-mono text-[10px] select-all relative overflow-hidden border border-slate-800">
                                <span class="truncate flex-1" x-text="selectedDevice.device_hash"></span>
                                <button type="button" @click="navigator.clipboard.writeText(selectedDevice.device_hash); copied = true; setTimeout(() => copied = false, 2000)" 
                                    class="p-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-slate-100 cursor-pointer">
                                    <svg x-show="!copied" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    <svg x-show="copied" class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            {{-- Modal Footer --}}
            <div class="p-3 border-t border-border flex justify-end bg-surface-muted">
                <button type="button" @click="showDeviceModal = false" class="btn-secondary btn-sm px-4 cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>
</div>
