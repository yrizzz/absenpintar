<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="heading-1">Laporan & Telemetri Kehadiran</h1>
            <p class="mt-1 label-sm">Analisis koordinat tim, tingkat akurasi GPS, dan kepatuhan perimeter keamanan</p>
        </div>

        <!-- Telemetry Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Avg Accuracy -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-blue-500/20 rounded-2xl p-6 shadow-[0_0_15px_rgba(59,130,246,0.05)] hover:shadow-[0_0_25px_rgba(59,130,246,0.2)] hover:border-blue-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-400 opacity-80"></div>
                <span class="label-xs font-black uppercase tracking-wider text-blue-400/90 group-hover:text-blue-300 transition-colors">Rata-rata Akurasi GPS</span>
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-blue-200 drop-shadow-[0_0_8px_rgba(59,130,246,0.455)] mt-2">± {{ $avg_accuracy }} <span class="label-sm text-slate-400 font-medium">meter</span></div>
                <div class="mt-4 label-xs text-emerald-400 font-bold flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Presisi GPS Terkalibrasi
                </div>
            </div>

            <!-- Total Present -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-emerald-500/20 rounded-2xl p-6 shadow-[0_0_15px_rgba(16,185,129,0.05)] hover:shadow-[0_0_25px_rgba(16,185,129,0.2)] hover:border-emerald-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-emerald-500 via-teal-500 to-green-400 opacity-80"></div>
                <span class="label-xs font-black uppercase tracking-wider text-emerald-400/90 group-hover:text-emerald-300 transition-colors">Total Log Hadir (WFO)</span>
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-200 drop-shadow-[0_0_8px_rgba(16,185,129,0.455)] mt-2">{{ $total_presence_logs }} <span class="label-sm text-slate-400 font-medium">absen</span></div>
                <div class="mt-4 label-xs text-emerald-400 font-bold flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Semua Data Validated
                </div>
            </div>

            <!-- Risk Events -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-rose-500/20 rounded-2xl p-6 shadow-[0_0_15px_rgba(244,63,94,0.05)] hover:shadow-[0_0_25px_rgba(244,63,94,0.2)] hover:border-rose-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-rose-500 via-pink-500 to-red-400 opacity-80"></div>
                <span class="label-xs font-black uppercase tracking-wider text-rose-400/90 group-hover:text-rose-300 transition-colors">Deteksi Pelanggaran</span>
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-rose-200 drop-shadow-[0_0_8px_rgba(244,63,94,0.455)] mt-2 {{ $risk_events > 0 ? 'text-rose-400' : 'text-emerald-400' }}">{{ $risk_events }} <span class="label-sm text-slate-400 font-medium text-slate-400">kasus</span></div>
                <div class="mt-4 label-xs font-bold text-slate-400">
                    Tingkat Keamanan: {{ $risk_events > 0 ? 'Waspada' : 'Sangat Aman' }}
                </div>
            </div>

            <!-- Overtime -->
            <div class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-purple-500/20 rounded-2xl p-6 shadow-[0_0_15px_rgba(168,85,247,0.05)] hover:shadow-[0_0_25px_rgba(168,85,247,0.2)] hover:border-purple-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-400 opacity-80"></div>
                <span class="label-xs font-black uppercase tracking-wider text-purple-400/90 group-hover:text-purple-300 transition-colors">Akumulasi Lembur</span>
                <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-purple-200 drop-shadow-[0_0_8px_rgba(168,85,247,0.455)] mt-2">{{ $overtime_hours }} <span class="label-sm text-slate-400 font-medium">jam</span></div>
                <div class="mt-4 label-xs font-bold text-blue-400">Terhitung Otomatis</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Custom Report Generator -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-1">
                <h3 class="heading-3 mb-6">Penyusun Laporan Telemetri</h3>
                
                @if (session()->has('success'))
                    <div class="mb-4 p-3.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-[11px] font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit.prevent="generateReport" class="space-y-5">
                    <div>
                        <label class="block label-xs mb-2">Tipe Laporan</label>
                        <select wire:model.live="report_type" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="presence_summary">Ringkasan Kehadiran Biometrik</option>
                            <option value="coordinates_log">Telemetri Pelanggaran Geofence</option>
                            <option value="leaves_audit">Ledger Cuti Tahunan & Lembur</option>
                            <option value="system_logs">Audit Sidik Jari Perangkat Tepercaya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block label-xs mb-2">Rentang Waktu</label>
                        <select wire:model.live="report_period" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="weekly">Rentang Minggu Ini</option>
                            <option value="monthly">Rentang Bulan Ini (Mei 2026)</option>
                            <option value="quarterly">Ledger Fiskal Q2 2026</option>
                            <option value="annual">Rentang Tahunan FY 2026</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-sm btn-primary w-full">
                        Susun Data Telemetri
                    </button>
                </form>

                <!-- Download Actions -->
                <div class="mt-6 pt-6 border-t border-white/5 space-y-2">
                    <a href="{{ route('reports.print', ['type' => $report_type, 'period' => $report_period]) }}" target="_blank" class="btn-sm btn-secondary w-full flex items-center justify-center cursor-pointer decoration-none">
                        <svg class="w-4 h-4 mr-2 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Cetak Laporan / Simpan PDF
                    </a>
                    <button wire:click="downloadExcel" class="btn-sm btn-secondary w-full flex items-center justify-center cursor-pointer">
                        <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Unduh Spreadsheet Excel (.xlsx)
                    </button>
                </div>
            </div>

            <!-- Right: Beautiful Visual Telemetry Charts & Details -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="heading-3">Peta Intensitas Kehadiran Mingguan</h3>
                    <span class="badge-info">Aliran Data Aktif</span>
                </div>

                <!-- Mock telemetry visual representation -->
                <div class="bg-[#0d1527] border border-white/5 rounded-2xl p-4 mb-6">
                    <div class="h-48 flex items-end justify-between space-x-2 pt-4 px-2">
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500/20 rounded-t-lg transition-all hover:bg-blue-500/30" style="height: {{ $heights['Monday'] }}px;"></div>
                            <span class="label-xs mt-2">Sen</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500/40 rounded-t-lg transition-all hover:bg-blue-500/50" style="height: {{ $heights['Tuesday'] }}px;"></div>
                            <span class="label-xs mt-2">Sel</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500/80 rounded-t-lg transition-all hover:bg-blue-500" style="height: {{ $heights['Wednesday'] }}px;"></div>
                            <span class="label-xs mt-2">Rab</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500/60 rounded-t-lg transition-all hover:bg-blue-500/70" style="height: {{ $heights['Thursday'] }}px;"></div>
                            <span class="label-xs mt-2">Kam</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-emerald-500/80 rounded-t-lg transition-all hover:bg-emerald-500" style="height: {{ $heights['Friday'] }}px;"></div>
                            <span class="label-xs mt-2">Jum</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="label-xs">Audit Integritas Sidik Jari Perangkat (Terbaru)</h4>
                    
                    @if($latest_devices->isEmpty())
                        <div class="label-xs text-slate-500 font-bold text-center py-4 bg-[#0d1527]/50 rounded-2xl">
                            Belum ada telemetri perangkat terdaftar.
                        </div>
                    @else
                        <div class="divide-y divide-white/5 text-xs text-slate-300">
                            @foreach($latest_devices as $d)
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex flex-col">
                                        <span class="label-sm font-bold text-white">{{ $d->browser }} on {{ $d->os }}</span>
                                        <span class="label-xs text-slate-500">Karyawan: {{ $d->user->name ?? 'N/A' }}</span>
                                    </div>
                                    <span class="badge-rect-success">
                                        {{ $d->trusted ? 'Tepercaya' : 'Tidak Terverifikasi' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rekapitulasi Kehadiran & Foto Biometrik Section -->
        <div class="mt-8 bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-6 border-b border-white/5">
                <div>
                    <h3 class="heading-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span>Rekapitulasi Kehadiran & Foto Biometrik</span>
                        @if(!empty($selectedLogs))
                            <span class="ml-3 badge-info animate-pulse">
                                {{ count($selectedLogs) }} Item Terpilih
                            </span>
                        @endif
                    </h3>
                    <p class="label-sm mt-1">Kelola, saring, dan ekspor log kehadiran lengkap beserta bukti foto biometrik wajah karyawan ke Spreadsheet Excel</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-2">
                    <button wire:click="downloadExcel" class="btn-sm btn-success flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Ekspor Terpilih (Excel .xlsx)
                    </button>
                </div>
            </div>

            <!-- Filter Panel -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 bg-[#0d1527]/60 border border-white/5 rounded-2xl p-4">
                <div>
                    <label class="block label-xs mb-1.5">Saring Karyawan</label>
                    <select wire:model.live="filter_user_id" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block label-xs mb-1.5">Saring Kantor</label>
                    <select wire:model.live="filter_branch_id" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}">{{ $br->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block label-xs mb-1.5">Tanggal Mulai</label>
                    <input type="date" wire:model.live="filter_start_date" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block label-xs mb-1.5">Tanggal Selesai</label>
                    <input type="date" wire:model.live="filter_end_date" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                </div>

                <div class="flex items-end">
                    <button wire:click="resetFilters" class="btn-sm btn-secondary w-full py-2 rounded-xl">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                @if($recapLogs->isEmpty())
                    <div class="label-xs text-slate-500 font-bold text-center py-10 bg-[#0d1527]/50 rounded-2xl">
                        Tidak ada kecocokan data absensi untuk saringan filter saat ini.
                    </div>
                @else
                    <table class="w-full min-w-max text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 label-xs font-bold text-slate-400">
                                <th class="pb-3" style="width: 40px;">
                                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-white/10 bg-[#0d1527] text-blue-500 focus:ring-blue-500 cursor-pointer">
                                </th>
                                <th class="pb-3" style="width: 80px;">Foto selfie</th>
                                <th class="pb-3" style="width: 180px;">Karyawan</th>
                                <th class="pb-3" style="width: 160px;">Waktu kehadiran</th>
                                <th class="pb-3" style="width: 90px;">Aksi</th>
                                <th class="pb-3" style="width: 150px;">Lokasi / cabang</th>
                                <th class="pb-3" style="width: 100px;">Presisi GPS</th>
                                <th class="pb-3" style="width: 120px;">IP address</th>
                                <th class="pb-3" style="width: 100px;">Kerawanan</th>
                                <th class="pb-3 text-right" style="width: 110px;">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-xs text-slate-300">
                            @foreach($recapLogs as $log)
                                <tr class="hover:bg-white/5 transition-all">
                                    <td class="py-3">
                                        <input type="checkbox" value="{{ $log->id }}" wire:model.live="selectedLogs" class="rounded border-white/10 bg-[#0d1527] text-blue-500 focus:ring-blue-500 cursor-pointer">
                                    </td>
                                    <td class="py-3">
                                        @if($log->selfie_path)
                                            <div class="relative w-10 h-10 bg-slate-800 rounded-lg overflow-hidden border border-white/10">
                                                <img src="{{ asset('storage/' . $log->selfie_path) }}" class="w-full h-full object-cover transform scaleX(-1)">
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-slate-900/60 rounded-lg flex items-center justify-center border border-white/5 text-[9px] font-bold text-slate-500">
                                                Tanpa foto
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 label-sm font-bold text-white">
                                        <span class="block">{{ $log->user->name ?? 'N/A' }}</span>
                                        <span class="block label-xs font-medium text-slate-400">ID: {{ $log->user->employee_id ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-3 label-sm text-slate-300">
                                        {{ \Carbon\Carbon::parse($log->timestamp)->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->translatedFormat('d M Y, H:i:s') }}
                                    </td>
                                    <td class="py-3">
                                        @if($log->type === 'checkin')
                                            <span class="badge-rect-success">Masuk</span>
                                        @else
                                            <span class="badge-rect-info">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="py-3 label-sm text-slate-300">
                                        {{ $log->branch->name ?? 'Mobile / WFH' }}
                                    </td>
                                    <td class="py-3 label-sm text-slate-400">
                                        ± {{ $log->accuracy ?? '0' }}m
                                    </td>
                                    <td class="py-3 label-xs font-mono text-slate-400">
                                        {{ $log->ip_address }}
                                    </td>
                                    <td class="py-3">
                                        @if($log->risk_level === 'high')
                                            <span class="badge-rect-danger">Tinggi</span>
                                        @elseif($log->risk_level === 'medium')
                                            <span class="badge-rect-warning">Sedang</span>
                                        @else
                                            <span class="badge-rect-success">Rendah</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-right">
                                        @if($log->is_late)
                                            <span class="badge-rect-danger">Terlambat</span>
                                        @else
                                            <span class="badge-rect-success">Tepat waktu</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>
</div>
