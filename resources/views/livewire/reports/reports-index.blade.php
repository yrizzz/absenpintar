<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<div class="py-8 min-h-screen">
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
                    <a href="{{ route('reports.print', ['type' => $report_type, 'period' => $report_period]) }}" target="_blank" class="btn-secondary btn-sm w-full">
                        <svg class="h-4 w-4 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Cetak / Simpan PDF
                    </a>
                    <button wire:click="downloadExcel" class="btn-secondary btn-sm w-full">
                        <svg class="h-4 w-4 text-success" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Unduh Excel (.xlsx)
                    </button>
                </div>
            </div>

            <div class="card p-6 lg:col-span-2">
                <h3 class="heading-3 mb-4">Audit Integritas Perangkat (Terbaru)</h3>
                @if($latest_devices->isEmpty())
                    <div class="text-sm text-fg-muted text-center py-8 rounded-xl bg-surface-muted">Belum ada telemetri perangkat terdaftar.</div>
                @else
                    <div class="divide-y divide-border">
                        @foreach($latest_devices as $d)
                            <div class="flex items-center justify-between py-3">
                                <div class="flex flex-col">
                                    <span class="label-sm font-medium text-fg">{{ $d->browser }} on {{ $d->os }}</span>
                                    <span class="label-xs">Karyawan: {{ $d->user->name ?? 'N/A' }}</span>
                                </div>
                                <span class="badge-rect-success">{{ $d->trusted ? 'Tepercaya' : 'Tidak Terverifikasi' }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Recap: filters + sort + pagination --}}
        <div class="card p-6 sm:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <div>
                    <h3 class="heading-3 flex items-center gap-2">
                        Rekapitulasi Kehadiran
                        @if(!empty($selectedLogs))<span class="badge-info">{{ count($selectedLogs) }} terpilih</span>@endif
                    </h3>
                    <p class="label-sm mt-1">Saring, urutkan, dan ekspor log kehadiran lengkap beserta bukti biometrik.</p>
                </div>
                <button wire:click="downloadExcel" class="btn-success btn-sm">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Ekspor Terpilih
                </button>
            </div>

            {{-- Filter bar --}}
            <div class="rounded-xl border border-border bg-surface-muted p-4 mb-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="lg:col-span-2">
                        <label class="label">Cari karyawan</label>
                        <input type="text" wire:model.live.debounce.400ms="search" placeholder="Nama atau ID karyawan…">
                    </div>
                    <div>
                        <label class="label">Karyawan</label>
                        <select wire:model.live="filter_user_id" class="cursor-pointer">
                            <option value="">Semua</option>
                            @foreach($employees as $emp)<option value="{{ $emp->id }}">{{ $emp->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Cabang</label>
                        <select wire:model.live="filter_branch_id" class="cursor-pointer">
                            <option value="">Semua</option>
                            @foreach($branches as $br)<option value="{{ $br->id }}">{{ $br->name }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <label class="label">Tipe</label>
                        <select wire:model.live="filter_type" class="cursor-pointer">
                            <option value="">Semua</option>
                            <option value="checkin">Masuk</option>
                            <option value="checkout">Keluar</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Kerawanan</label>
                        <select wire:model.live="filter_risk" class="cursor-pointer">
                            <option value="">Semua</option>
                            <option value="low">Rendah</option>
                            <option value="medium">Sedang</option>
                            <option value="high">Tinggi</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Status</label>
                        <select wire:model.live="filter_status" class="cursor-pointer">
                            <option value="">Semua</option>
                            <option value="approved">Disetujui</option>
                            <option value="pending">Diproses</option>
                            <option value="flagged">Dicurigai</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">Per halaman</label>
                        <select wire:model.live="perPage" class="cursor-pointer">
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="label">Tanggal mulai</label>
                        <input type="date" wire:model.live="filter_start_date">
                    </div>
                    <div>
                        <label class="label">Tanggal selesai</label>
                        <input type="date" wire:model.live="filter_end_date">
                    </div>
                    <div class="flex items-end">
                        <button wire:click="resetFilters" class="btn-secondary btn-sm w-full">Reset Semua Filter</button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
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
