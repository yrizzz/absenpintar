<div class="py-8 min-h-screen text-slate-100 bg-transparent" x-data="{ selectedLog: null, showModal: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8 sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Riwayat Kehadiran</h1>
                <p class="mt-1 text-sm text-slate-400 font-medium">
                    {{ $isAdmin ? 'Telusuri, verifikasi, dan filter log kehadiran semua karyawan secara mendalam' : 'Telusuri, verifikasi, dan filter log kehadiran historis Anda secara mendalam' }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('attendance.checkin') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-button-primary hover:shadow-button-primary-hover hover:translate-y-[-1px] active:translate-y-[0px] transition-all">
                    <svg class="-ml-1 mr-2 h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Absen Masuk Baru
                </a>
            </div>
        </div>

        <!-- Filters Box -->
        <div class="mb-8 bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full blur-xl pointer-events-none"></div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Filter Catatan Log</h3>
            
            <div class="grid grid-cols-1 gap-4 {{ $isAdmin ? 'sm:grid-cols-4' : 'sm:grid-cols-3' }}">
                @if($isAdmin)
                    <div class="space-y-1.5">
                        <label for="searchEmployee" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Cari Karyawan</label>
                        <input wire:model.live="searchEmployee" 
                               type="text" 
                               id="searchEmployee"
                               placeholder="Nama atau ID Karyawan..."
                               class="w-full px-4 py-2.5 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all">
                    </div>
                @endif
                <div class="space-y-1.5">
                    <label for="filterMonth" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Bulan</label>
                    <input wire:model.live="filterMonth" 
                           type="month" 
                           id="filterMonth"
                           class="w-full px-4 py-2.5 bg-[#0d1527]/80 border border-white/10 rounded-2xl text-sm text-white focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 transition-all">
                </div>
                <div class="space-y-1.5">
                    <label for="filterType" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tipe Absen</label>
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
                    <label for="filterStatus" class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Validasi</label>
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
        <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl overflow-hidden">
            @if($attendances->isEmpty())
                <div class="text-center py-16 max-w-sm mx-auto">
                    <div class="mx-auto w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                        <svg class="h-6.5 w-6.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-white">Tidak ada catatan ditemukan</h4>
                    <p class="mt-1 text-xs text-slate-400">Cobalah melonggarkan filter Anda atau lakukan absensi baru untuk mengisi halaman riwayat.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/5">
                        <thead class="bg-white/5">
                            <tr>
                                @if($isAdmin)
                                    <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Karyawan</th>
                                @endif
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Tanggal & Waktu</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Metode Absensi</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Cabang & Presisi GPS</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Telemetri Risiko</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Status Kepatuhan</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-transparent">
                            @foreach($attendances as $attendance)
                                <tr class="hover:bg-white/5 transition-colors duration-150">
                                    @if($isAdmin)
                                        <td class="px-6 py-4.5 whitespace-nowrap">
                                            <div class="text-sm font-bold text-white">{{ $attendance->user->name ?? 'Karyawan' }}</div>
                                            <div class="text-[10px] text-slate-500 font-mono">#{{ $attendance->user->employee_id ?? 'N/A' }}</div>
                                        </td>
                                    @endif
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <div class="text-sm font-bold text-white">
                                            {{ $attendance->timestamp->translatedFormat('d M Y') }}
                                        </div>
                                        <div class="text-xs text-slate-400 font-semibold mt-0.5">
                                            {{ $attendance->timestamp->format('H:i:s') }} WIB
                                        </div>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold {{ $attendance->type === 'checkin' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-blue-500/10 text-sky-400 border border-blue-500/20' }}">
                                            {{ $attendance->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <div class="text-sm font-bold text-white">{{ $attendance->branch?->name ?? 'HQ Workspace' }}</div>
                                        <div class="text-[10px] text-slate-400 font-semibold mt-0.5">Akurasi GPS: ± {{ round($attendance->accuracy) }}m</div>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold {{ $attendance->risk_level === 'high' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : ($attendance->risk_level === 'medium' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20') }}">
                                            Risiko: {{ $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah') }} (Skor: {{ $attendance->risk_score }})
                                        </span>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap">
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-[10px] font-bold {{ $attendance->status === 'approved' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($attendance->status === 'flagged' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20') }}">
                                            {{ $attendance->status === 'approved' ? 'Disetujui' : ($attendance->status === 'flagged' ? 'Dicurigai' : 'Diproses') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4.5 whitespace-nowrap text-xs">
                                        <button @click="selectedLog = {{ json_encode([
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
                                        class="text-sky-400 hover:text-sky-300 font-bold hover:underline transition-all cursor-pointer">
                                            Lihat Detail
                                        </button>
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

</div>

<style>
@keyframes scanLine {
    0% { top: 0%; }
    50% { top: 100%; }
    100% { top: 0%; }
}
</style>
