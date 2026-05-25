<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Laporan & Telemetri Kehadiran</h1>
            <p class="mt-1 text-sm text-slate-400 font-medium">Analisis koordinat tim, tingkat akurasi GPS, dan kepatuhan perimeter keamanan</p>
        </div>

        <!-- Telemetry Key Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Avg Accuracy -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Rata-rata Akurasi GPS</span>
                <div class="text-3xl font-black text-white font-display">± {{ $avg_accuracy }} <span class="text-xs text-slate-400 font-medium">meter</span></div>
                <div class="mt-2 text-[10px] text-emerald-400 font-bold flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Presisi GPS Terkalibrasi
                </div>
            </div>

            <!-- Total Present -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Total Log Hadir (WFO)</span>
                <div class="text-3xl font-black text-white font-display">{{ $total_presence_logs }} <span class="text-xs text-slate-400 font-medium">absen</span></div>
                <div class="mt-2 text-[10px] text-emerald-400 font-bold flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    Semua Data Validated
                </div>
            </div>

            <!-- Risk Events -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Deteksi Pelanggaran</span>
                <div class="text-3xl font-black {{ $risk_events > 0 ? 'text-rose-400' : 'text-emerald-400' }} font-display">{{ $risk_events }} <span class="text-xs text-slate-400 font-medium">kasus</span></div>
                <div class="mt-2 text-[10px] text-slate-400 font-bold">
                    Tingkat Keamanan: {{ $risk_events > 0 ? 'Waspada' : 'Sangat Aman' }}
                </div>
            </div>

            <!-- Overtime -->
            <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Akumulasi Lembur</span>
                <div class="text-3xl font-black text-white font-display">{{ $overtime_hours }} <span class="text-xs text-slate-400 font-medium">jam</span></div>
                <div class="mt-2 text-[10px] text-blue-400 font-bold">Terhitung Otomatis</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Custom Report Generator -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-1">
                <h3 class="text-lg font-bold text-white tracking-tight font-display mb-6">Penyusun Laporan Telemetri</h3>
                
                @if (session()->has('success'))
                    <div class="mb-4 p-3.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-[11px] font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                <form wire:submit.prevent="generateReport" class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe Laporan</label>
                        <select wire:model.live="report_type" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="presence_summary">Ringkasan Kehadiran Biometrik</option>
                            <option value="coordinates_log">Telemetri Pelanggaran Geofence</option>
                            <option value="leaves_audit">Ledger Cuti Tahunan & Lembur</option>
                            <option value="system_logs">Audit Sidik Jari Perangkat Tepercaya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Rentang Waktu</label>
                        <select wire:model.live="report_period" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="weekly">Rentang Minggu Ini</option>
                            <option value="monthly">Rentang Bulan Ini (Mei 2026)</option>
                            <option value="quarterly">Ledger Fiskal Q2 2026</option>
                            <option value="annual">Rentang Tahunan FY 2026</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-button-primary transition-all cursor-pointer">
                        Susun Data Telemetri
                    </button>
                </form>

                <!-- Download Actions -->
                <div class="mt-6 pt-6 border-t border-white/5 space-y-2">
                    <a href="{{ route('reports.print', ['type' => $report_type, 'period' => $report_period]) }}" target="_blank" class="w-full py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-2xl text-xs font-bold text-slate-300 hover:text-white transition-all flex items-center justify-center cursor-pointer decoration-none">
                        <svg class="w-4 h-4 mr-2 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Cetak Laporan / Simpan PDF
                    </a>
                    <button wire:click="downloadExcel" class="w-full py-3 bg-white/5 border border-white/10 hover:border-white/20 rounded-2xl text-xs font-bold text-slate-300 hover:text-white transition-all flex items-center justify-center cursor-pointer">
                        <svg class="w-4 h-4 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Unduh Spreadsheet Excel (.xlsx)
                    </button>
                </div>
            </div>

            <!-- Right: Beautiful Visual Telemetry Charts & Details -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white tracking-tight font-display">Peta Intensitas Kehadiran Mingguan</h3>
                    <span class="text-xs font-bold text-sky-400 bg-sky-500/10 px-2.5 py-1 rounded-full border border-sky-500/20 uppercase tracking-widest text-[9px]">Aliran Data Aktif</span>
                </div>

                <!-- Mock telemetry visual representation -->
                <div class="bg-[#0d1527] border border-white/5 rounded-2xl p-4 mb-6">
                    <div class="h-48 flex items-end justify-between space-x-2 pt-4 px-2">
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500/20 rounded-t-lg transition-all hover:bg-blue-500/30" style="height: {{ $heights['Monday'] }}px;"></div>
                            <span class="text-[9px] font-bold text-slate-500 mt-2">Sen</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500/40 rounded-t-lg transition-all hover:bg-blue-500/50" style="height: {{ $heights['Tuesday'] }}px;"></div>
                            <span class="text-[9px] font-bold text-slate-500 mt-2">Sel</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-sky-400/80 rounded-t-lg transition-all hover:bg-sky-400" style="height: {{ $heights['Wednesday'] }}px;"></div>
                            <span class="text-[9px] font-bold text-slate-500 mt-2">Rab</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-blue-500/60 rounded-t-lg transition-all hover:bg-blue-500/70" style="height: {{ $heights['Thursday'] }}px;"></div>
                            <span class="text-[9px] font-bold text-slate-500 mt-2">Kam</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-full bg-emerald-500/80 rounded-t-lg transition-all hover:bg-emerald-500" style="height: {{ $heights['Friday'] }}px;"></div>
                            <span class="text-[9px] font-bold text-slate-500 mt-2">Jum</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Audit Integritas Sidik Jari Perangkat (Terbaru)</h4>
                    
                    @if($latest_devices->isEmpty())
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider text-center py-4 bg-[#0d1527]/50 rounded-2xl">
                            Belum ada telemetri perangkat terdaftar.
                        </div>
                    @else
                        <div class="divide-y divide-white/5 text-xs text-slate-300">
                            @foreach($latest_devices as $d)
                                <div class="flex items-center justify-between py-3">
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ $d->browser }} on {{ $d->os }}</span>
                                        <span class="text-[9px] text-slate-500">Karyawan: {{ $d->user->name ?? 'N/A' }}</span>
                                    </div>
                                    <span class="text-emerald-400 font-bold uppercase tracking-wider text-[9px] bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-full">
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
        <div class="mt-8 bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-6 border-b border-white/5">
                <div>
                    <h3 class="text-xl font-bold text-white tracking-tight font-display flex items-center">
                        📋 Rekapitulasi Kehadiran & Foto Biometrik
                        @if(!empty($selectedLogs))
                            <span class="ml-3 px-2.5 py-0.5 rounded-full text-[9px] font-black bg-sky-500/10 border border-sky-500/20 text-sky-400 uppercase tracking-widest animate-pulse">
                                {{ count($selectedLogs) }} Item Terpilih
                            </span>
                        @endif
                    </h3>
                    <p class="text-xs text-slate-400 font-medium mt-1">Kelola, saring, dan ekspor log kehadiran lengkap beserta bukti foto biometrik wajah karyawan ke Spreadsheet Excel</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center space-x-2">
                    <button wire:click="downloadExcel" class="px-5 py-2.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider rounded-xl hover:bg-emerald-500 hover:text-white transition-all cursor-pointer flex items-center shadow-lg">
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
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Saring Karyawan</label>
                    <select wire:model.live="filter_user_id" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                        <option value="">Semua Karyawan</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Saring Kantor</label>
                    <select wire:model.live="filter_branch_id" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $br)
                            <option value="{{ $br->id }}">{{ $br->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Mulai</label>
                    <input type="date" wire:model.live="filter_start_date" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Selesai</label>
                    <input type="date" wire:model.live="filter_end_date" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                </div>

                <div class="flex items-end">
                    <button wire:click="resetFilters" class="w-full py-2 bg-white/5 border border-white/10 rounded-xl text-slate-300 text-xs font-bold uppercase tracking-wider hover:text-white hover:bg-white/10 transition-all cursor-pointer">
                        Reset Filter
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                @if($recapLogs->isEmpty())
                    <div class="text-xs text-slate-500 font-bold uppercase tracking-wider text-center py-10 bg-[#0d1527]/50 rounded-2xl">
                        Tidak ada kecocokan data absensi untuk saringan filter saat ini.
                    </div>
                @else
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/5 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                <th class="pb-3" style="width: 40px;">
                                    <input type="checkbox" wire:model.live="selectAll" class="rounded border-white/10 bg-[#0d1527] text-sky-500 focus:ring-sky-500 cursor-pointer">
                                </th>
                                <th class="pb-3" style="width: 60px;">Foto Selfie</th>
                                <th class="pb-3">Karyawan</th>
                                <th class="pb-3">Waktu Kehadiran</th>
                                <th class="pb-3">Aksi</th>
                                <th class="pb-3">Lokasi / Cabang</th>
                                <th class="pb-3">Presisi GPS</th>
                                <th class="pb-3">IP Address</th>
                                <th class="pb-3">Kerawanan</th>
                                <th class="pb-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-xs text-slate-300">
                            @foreach($recapLogs as $log)
                                <tr class="hover:bg-white/5 transition-all">
                                    <td class="py-3">
                                        <input type="checkbox" value="{{ $log->id }}" wire:model.live="selectedLogs" class="rounded border-white/10 bg-[#0d1527] text-sky-500 focus:ring-sky-500 cursor-pointer">
                                    </td>
                                    <td class="py-3">
                                        @if($log->selfie_path)
                                            <div class="relative w-10 h-10 bg-slate-800 rounded-lg overflow-hidden border border-white/10">
                                                <img src="{{ asset('storage/' . $log->selfie_path) }}" class="w-full h-full object-cover transform scaleX(-1)">
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-slate-900/60 rounded-lg flex items-center justify-center border border-white/5 text-[8px] font-bold text-slate-500">
                                                NO PHOTO
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 font-semibold text-white">
                                        <span class="block">{{ $log->user->name ?? 'N/A' }}</span>
                                        <span class="block text-[9px] text-slate-400 font-normal">ID: {{ $log->user->employee_id ?? 'N/A' }}</span>
                                    </td>
                                    <td class="py-3">
                                        {{ \Carbon\Carbon::parse($log->timestamp)->translatedFormat('d M Y, H:i:s') }}
                                    </td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider {{ $log->type === 'checkin' ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400' : 'bg-sky-500/10 border border-sky-500/20 text-sky-400' }}">
                                            {{ strtoupper($log->type) }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        {{ $log->branch->name ?? 'Mobile / WFH' }}
                                    </td>
                                    <td class="py-3 text-slate-400">
                                        ± {{ $log->accuracy ?? '0' }}m
                                    </td>
                                    <td class="py-3 text-slate-400 font-mono text-[10px]">
                                        {{ $log->ip_address }}
                                    </td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider {{ $log->risk_level === 'high' ? 'bg-rose-500/10 border border-rose-500/20 text-rose-400' : ($log->risk_level === 'medium' ? 'bg-yellow-500/10 border border-yellow-500/20 text-yellow-400' : 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-400') }}">
                                            {{ $log->risk_level }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        @if($log->is_late)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-bold bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase tracking-wide">Terlambat</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[8px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase tracking-wide">Tepat Waktu</span>
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
