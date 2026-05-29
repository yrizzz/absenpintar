<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="heading-1">Sistem Izin Kerja</h1>
                <p class="mt-1 label-sm">Pengajuan izin datang terlambat, pulang awal, setengah hari, dan tidak masuk dengan alur persetujuan ganda.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                @if($step === 'index')
                    <button wire:click="$set('step', 'create')" type="button" class="btn-sm btn-primary flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Ajukan Izin Baru</span>
                    </button>
                @else
                    <button wire:click="$set('step', 'index')" type="button" class="btn-sm btn-secondary flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Riwayat</span>
                    </button>
                @endif
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 sm:p-5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-bold flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2.5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 sm:p-5 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-xs font-bold flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2.5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- FORM PENGAJUAN IZIN -->
        <!-- ========================================== -->
        @if($step === 'create')
            <div class="max-w-3xl mx-auto bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                <h3 class="heading-3 mb-6">Formulir Pengajuan Izin Kerja</h3>
                
                <form wire:submit.prevent="submitRequest" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block label-xs mb-2">Kategori Izin</label>
                            <select wire:model.live="type" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                <option value="ijin_datang_terlambat">Izin Datang Terlambat</option>
                                <option value="ijin_pulang_awal">Izin Pulang Awal</option>
                                <option value="ijin_tidak_masuk">Izin Tidak Masuk</option>
                                <option value="ijin_setengah_hari">Izin Setengah Hari</option>
                            </select>
                            <span class="label-xs text-slate-500 mt-1.5 block">Pilih kategori dispensasi izin kerja yang diajukan.</span>
                            
                            @if($type === 'ijin_datang_terlambat')
                                <span class="label-xs text-amber-400 font-bold mt-1.5 block">⚠️ Batas toleransi datang terlambat maksimal {{ cache()->get('settings.permission_max_late_hours', 2.0) }} jam.</span>
                            @elseif($type === 'ijin_pulang_awal')
                                <span class="label-xs text-amber-400 font-bold mt-1.5 block">⚠️ Batas toleransi pulang awal maksimal {{ cache()->get('settings.permission_max_early_hours', 2.0) }} jam.</span>
                            @elseif($type === 'ijin_setengah_hari')
                                <span class="label-xs text-amber-400 font-bold mt-1.5 block">⚠️ Batas toleransi setengah hari maksimal {{ cache()->get('settings.permission_max_half_day_hours', 4.0) }} jam.</span>
                            @endif
                        </div>

                        <div>
                            <label class="block label-xs mb-2">Tanggal Izin</label>
                            <input wire:model="date" type="date" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    @if($type !== 'ijin_tidak_masuk')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block label-xs mb-2">Jam Mulai Izin</label>
                                <input wire:model="start_time" type="time" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                            </div>

                            <div>
                                <label class="block label-xs mb-2">Jam Selesai Izin</label>
                                <input wire:model="end_time" type="time" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block label-xs mb-2">Alasan Izin</label>
                        <textarea wire:model="reason" rows="4" required placeholder="Tuliskan detail alasan pengajuan izin Anda di sini secara jelas..." class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all resize-none"></textarea>
                    </div>

                    <div>
                        <label class="block label-xs mb-2">Dokumen Pendukung / Lampiran (Opsional)</label>
                        <input wire:model="attachment" type="file" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        <span class="label-xs text-slate-500 mt-1 block">Format file: PDF, JPG, PNG (Maksimal 2MB).</span>
                        <div wire:loading wire:target="attachment" class="label-xs text-blue-400 font-bold mt-1">Mengunggah file...</div>
                    </div>

                    <div class="flex justify-end pt-4 space-x-3">
                        <button wire:click="$set('step', 'index')" type="button" class="btn-sm btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn-sm btn-primary">
                            Kirim Pengajuan Izin
                        </button>
                    </div>
                </form>
            </div>

        <!-- ========================================== -->
        <!-- DAFTAR & TINJAUAN UTAMA -->
        <!-- ========================================== -->
        @else
            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-xl relative overflow-hidden flex items-center space-x-4">
                    <div class="p-3 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-black text-slate-400 tracking-wider">Total Pengajuan Izin</div>
                        <div class="text-2xl font-bold text-white mt-0.5">{{ auth()->user()->permissionRequests()->count() }} <span class="text-xs text-slate-500 font-normal">Sesi</span></div>
                    </div>
                </div>

                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-xl relative overflow-hidden flex items-center space-x-4">
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-black text-slate-400 tracking-wider">Disetujui Resmi</div>
                        <div class="text-2xl font-bold text-emerald-400 mt-0.5">{{ auth()->user()->permissionRequests()->where('status', 'approved')->count() }} <span class="text-xs text-slate-500 font-normal">Sesi</span></div>
                    </div>
                </div>

                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-5 shadow-xl relative overflow-hidden flex items-center space-x-4">
                    <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-black text-slate-400 tracking-wider">Menunggu Persetujuan</div>
                        <div class="text-2xl font-bold text-amber-400 mt-0.5">{{ auth()->user()->permissionRequests()->where('status', 'pending')->count() }} <span class="text-xs text-slate-500 font-normal">Sesi</span></div>
                    </div>
                </div>
            </div>

            <!-- Alur Persetujuan Section -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-7 shadow-xl relative overflow-hidden mb-8">
                <div class="flex items-center space-x-2.5 mb-5">
                    <div class="h-6 w-1 bg-blue-500 rounded-full"></div>
                    <h4 class="text-xs sm:text-sm font-bold text-white uppercase tracking-wider">Alur Persetujuan Ganda (Double-Level Verification)</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="p-4 bg-[#0d1527]/40 border border-white/5 rounded-2xl relative">
                        <span class="absolute right-4 top-4 text-[10px] font-bold text-slate-600">01</span>
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-xs">A</div>
                        <h5 class="text-xs font-bold text-white mt-3">Karyawan Mengajukan</h5>
                        <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Mengisi tipe izin (telat, pulang awal, setengah hari, tidak masuk) disertai alasan & berkas.</p>
                    </div>

                    <div class="p-4 bg-[#0d1527]/40 border border-white/5 rounded-2xl relative">
                        <span class="absolute right-4 top-4 text-[10px] font-bold text-slate-600">02</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xs">B</div>
                        <h5 class="text-xs font-bold text-white mt-3">Kepala Divisi (Kadiv)</h5>
                        <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Melakukan review kesesuaian operasional & beban kerja di divisi terkait.</p>
                    </div>

                    <div class="p-4 bg-[#0d1527]/40 border border-white/5 rounded-2xl relative">
                        <span class="absolute right-4 top-4 text-[10px] font-bold text-slate-600">03</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold text-xs">C</div>
                        <h5 class="text-xs font-bold text-white mt-3">HR Manager (HRD)</h5>
                        <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Persetujuan akhir & sinkronisasi data dispensasi kehadiran sistem.</p>
                    </div>

                    <div class="p-4 bg-[#0d1527]/40 border border-white/5 rounded-2xl relative">
                        <span class="absolute right-4 top-4 text-[10px] font-bold text-slate-600">04</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">D</div>
                        <h5 class="text-xs font-bold text-white mt-3">Cetak Surat Resmi</h5>
                        <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">Sistem menerbitkan surat izin resmi bertanda tangan digital ber-barkod pengaman.</p>
                    </div>
                </div>
            </div>

            <!-- Tab Selector for Admins/Managers -->
            @if($isAdmin)
                <div class="permission-tab-container mb-6 flex space-x-1.5 p-1 bg-[#0d1527]/85 border border-white/5 rounded-2xl max-w-md">
                    <button wire:click="$set('activeTab', 'my')" type="button" 
                        class="permission-tab-btn flex-1 py-3 text-xs font-bold rounded-xl transition-all flex items-center justify-center space-x-2 {{ $activeTab === 'my' ? 'tab-active' : 'text-slate-350 hover:text-slate-900 dark:hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Riwayat Izin Saya</span>
                    </button>
                    <button wire:click="$set('activeTab', 'review')" type="button" 
                        class="permission-tab-btn flex-1 py-3 text-xs font-bold rounded-xl transition-all flex items-center justify-center space-x-2 relative {{ $activeTab === 'review' ? 'tab-active' : 'text-slate-350 hover:text-slate-900 dark:hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Tinjau Izin Karyawan</span>
                        @if($pendingRequests->count() > 0)
                            <span class="absolute -top-1.5 -right-1.5 flex h-4.5 w-4.5 items-center justify-center rounded-full bg-rose-600 text-[9px] font-black text-white ring-2 ring-[#0d1527]">
                                {{ $pendingRequests->count() }}
                            </span>
                        @endif
                    </button>
                </div>
            @endif

            <!-- Main Panel Content -->
            <div class="grid grid-cols-1 gap-8">
                
                <!-- Tab: Tinjau Pengajuan Karyawan (Only active when selected and user is Admin) -->
                @if($isAdmin && $activeTab === 'review')
                    <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="h-6 w-1 bg-indigo-500 rounded-full"></div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-white">Tinjau Pengajuan Izin Karyawan</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Daftar permohonan aktif yang memerlukan verifikasi Kepala Divisi dan HR Manager.</p>
                                </div>
                            </div>
                            <span class="badge-info self-start sm:self-auto font-black text-xs px-3.5 py-1.5 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-xl">
                                Total Antrean: {{ $pendingRequests->count() }}
                            </span>
                        </div>

                        @if($pendingRequests->isEmpty())
                            <div class="py-12 flex flex-col items-center justify-center border border-white/5 bg-[#0d1527]/50 rounded-2xl">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mb-3 shadow-lg shadow-emerald-500/5">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-400">Antrean bersih! Belum ada pengajuan baru yang butuh verifikasi.</span>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-max text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-white/5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                                            <th class="pb-3" style="width: 220px;">Karyawan</th>
                                            <th class="pb-3" style="width: 130px;">Tipe Izin</th>
                                            <th class="pb-3" style="width: 170px;">Waktu Izin</th>
                                            <th class="pb-3" style="width: 250px;">Alasan / Lampiran</th>
                                            <th class="pb-3 text-center" style="width: 140px;">Persetujuan Ganda</th>
                                            <th class="pb-3 text-right" style="width: 150px;">Aksi Cepat</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5 text-slate-200">
                                        @foreach($pendingRequests as $req)
                                            <tr class="align-middle">
                                                <td class="py-4">
                                                    <div class="flex items-center space-x-3.5">
                                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm uppercase shadow-sm">
                                                            {{ strtoupper(substr($req->user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-xs font-bold text-white">{{ $req->user->name }}</div>
                                                            <div class="text-[10px] font-bold text-blue-400 mt-0.5">{{ $req->user->employee_id }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 text-xs font-bold">
                                                    @if($req->type === 'ijin_datang_terlambat')
                                                        <span class="badge-rect-warning">Terlambat</span>
                                                    @elseif($req->type === 'ijin_pulang_awal')
                                                        <span class="badge-rect-danger">Pulang Awal</span>
                                                    @elseif($req->type === 'ijin_setengah_hari')
                                                        <span class="badge-rect-info">Setengah Hari</span>
                                                    @else
                                                        <span class="badge-rect-neutral">Tidak Masuk</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 text-xs font-medium text-slate-300">
                                                    <div class="font-bold">{{ $req->date->translatedFormat('d M Y') }}</div>
                                                    @if($req->type !== 'ijin_tidak_masuk')
                                                        <div class="text-[10px] text-slate-400 mt-1 flex items-center">
                                                            <svg class="w-3 h-3 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ substr($req->start_time, 0, 5) }} - {{ substr($req->end_time, 0, 5) }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-4 text-xs text-slate-300 max-w-xs">
                                                    <div class="truncate font-medium" title="{{ $req->reason }}">{{ $req->reason }}</div>
                                                    @if($req->attachment_path)
                                                        <a href="{{ asset('storage/' . $req->attachment_path) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-blue-400 hover:text-blue-300 mt-1.5">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                            Lihat Lampiran
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="py-4">
                                                    <div class="flex flex-col space-y-1.5 items-center justify-center">
                                                        <div class="flex items-center space-x-2 w-full justify-between px-2.5 py-1 bg-black/10 border border-white/5 rounded-lg">
                                                            <span class="text-[9px] font-bold text-slate-400 uppercase">Kadiv:</span>
                                                            @if($req->status_dept_head === 'approved')
                                                                <span class="text-[9px] font-black text-emerald-400 uppercase flex items-center">✓ OK</span>
                                                            @elseif($req->status_dept_head === 'rejected')
                                                                <span class="text-[9px] font-black text-rose-400 uppercase flex items-center">✗ NO</span>
                                                            @else
                                                                <span class="text-[9px] font-black text-amber-500 uppercase flex items-center animate-pulse">⏳ Pending</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center space-x-2 w-full justify-between px-2.5 py-1 bg-black/10 border border-white/5 rounded-lg">
                                                            <span class="text-[9px] font-bold text-slate-400 uppercase">HRD:</span>
                                                            @if($req->status_hr === 'approved')
                                                                <span class="text-[9px] font-black text-emerald-400 uppercase flex items-center">✓ OK</span>
                                                            @elseif($req->status_hr === 'rejected')
                                                                <span class="text-[9px] font-black text-rose-400 uppercase flex items-center">✗ NO</span>
                                                            @else
                                                                <span class="text-[9px] font-black text-amber-500 uppercase flex items-center animate-pulse">⏳ Pending</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 text-right">
                                                    <div class="flex items-center justify-end space-x-1.5">
                                                        <!-- Department Head ACC Button -->
                                                        @if($isManager && $req->status_dept_head === 'pending')
                                                            <button wire:click="approveDeptHead({{ $req->id }})" type="button" class="btn-success btn-xs shadow-sm font-extrabold px-3 py-1.5 rounded-xl">
                                                                ACC Kadiv
                                                            </button>
                                                        @endif

                                                        <!-- HR ACC Button -->
                                                        @if($isHr && $req->status_hr === 'pending')
                                                            <button wire:click="approveHr({{ $req->id }})" type="button" class="btn-primary btn-xs shadow-sm font-extrabold px-3 py-1.5 rounded-xl">
                                                                ACC HR
                                                            </button>
                                                        @endif

                                                        <button wire:click="rejectRequest({{ $req->id }})" type="button" class="btn-danger-outline btn-xs px-3 py-1.5 rounded-xl font-extrabold">
                                                            Tolak
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                
                <!-- Tab: Riwayat Pengajuan Izin Saya (Active by default or when clicked) -->
                @else
                    <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="h-6 w-1 bg-blue-500 rounded-full"></div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-bold text-white">Riwayat Pengajuan Izin Saya</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">Lacak riwayat dispensasi kerja dan status persetujuan berjenjang Anda.</p>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div class="permission-tab-container flex overflow-x-auto whitespace-nowrap bg-[#0d1527] border border-white/5 p-1 rounded-xl scrollbar-none">
                                <button wire:click="$set('statusFilter', 'all')" type="button" class="permission-tab-btn px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all flex-shrink-0 {{ $statusFilter === 'all' ? 'tab-active' : 'text-slate-350 hover:text-slate-900 dark:hover:text-white' }}">
                                    Semua
                                </button>
                                <button wire:click="$set('statusFilter', 'approved')" type="button" class="permission-tab-btn px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all flex-shrink-0 {{ $statusFilter === 'approved' ? 'tab-active' : 'text-slate-350 hover:text-slate-900 dark:hover:text-white' }}">
                                    Disetujui
                                </button>
                                <button wire:click="$set('statusFilter', 'pending')" type="button" class="permission-tab-btn px-3.5 py-1.5 text-xs font-bold rounded-lg transition-all flex-shrink-0 {{ $statusFilter === 'pending' ? 'tab-active' : 'text-slate-350 hover:text-slate-900 dark:hover:text-white' }}">
                                    Pending
                                </button>
                            </div>
                        </div>

                        @if($myPermissions->isEmpty())
                            <div class="py-12 flex flex-col items-center justify-center border border-white/5 bg-[#0d1527]/50 rounded-2xl">
                                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 mb-3 shadow-lg shadow-blue-500/5">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-400">Belum ada data pengajuan izin kerja.</span>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($myPermissions as $perm)
                                    <div class="bg-[#0d1527]/40 border border-white/5 rounded-2xl p-5 relative overflow-hidden space-y-4 flex flex-col justify-between hover:border-blue-500/20 transition-all duration-200">
                                        <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-blue-500/[0.02] rounded-full blur-xl"></div>
                                        
                                        <div class="space-y-3.5">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-black uppercase text-slate-400 tracking-wider">
                                                    @if($perm->type === 'ijin_datang_terlambat')
                                                        Izin Telat
                                                    @elseif($perm->type === 'ijin_pulang_awal')
                                                        Izin Pulang Awal
                                                    @elseif($perm->type === 'ijin_setengah_hari')
                                                        Izin 1/2 Hari
                                                    @else
                                                        Izin Tidak Masuk
                                                    @endif
                                                </span>
                                                
                                                <!-- Overall status badge -->
                                                @if($perm->status === 'approved')
                                                    <span class="badge-rect-success">Disetujui</span>
                                                @elseif($perm->status === 'rejected')
                                                    <span class="badge-rect-danger">Ditolak</span>
                                                @else
                                                    <span class="badge-rect-warning">Diproses</span>
                                                @endif
                                            </div>
                                            
                                            <div class="text-xs text-slate-400 space-y-1.5 bg-[#0d1527]/80 p-3 rounded-xl border border-white/5">
                                                <div class="flex justify-between">
                                                    <span>Tanggal Pengajuan:</span>
                                                    <span class="font-bold text-white">{{ $perm->date->translatedFormat('d M Y') }}</span>
                                                </div>
                                                @if($perm->type !== 'ijin_tidak_masuk')
                                                    <div class="flex justify-between">
                                                        <span>Waktu Dispensasi:</span>
                                                        <span class="font-bold text-blue-400">{{ substr($perm->start_time, 0, 5) }} s/d {{ substr($perm->end_time, 0, 5) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex flex-col pt-1.5 border-t border-white/5">
                                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Alasan Pengajuan:</span>
                                                    <span class="mt-1 text-slate-300 leading-relaxed font-medium">{{ $perm->reason }}</span>
                                                </div>
                                                @if($perm->attachment_path)
                                                    <div class="pt-1.5 border-t border-white/5">
                                                        <a href="{{ asset('storage/' . $perm->attachment_path) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-blue-400 hover:text-blue-300">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                            Dokumen Pendukung
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Visual Progress Tracking Line -->
                                            <div class="bg-black/20 border border-white/5 rounded-2xl p-3.5 space-y-2.5">
                                                <div class="flex items-center justify-between text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                                                    <span>Alur Persetujuan:</span>
                                                    <span class="{{ $perm->status === 'approved' ? 'text-emerald-400' : ($perm->status === 'rejected' ? 'text-rose-400' : 'text-amber-500') }}">
                                                        @if($perm->status === 'approved')
                                                            Selesai (Aktif)
                                                        @elseif($perm->status === 'rejected')
                                                            Ditolak
                                                        @else
                                                            Persetujuan Ganda
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex items-center justify-between px-2 pt-1 relative">
                                                    <!-- Background track line -->
                                                    <div class="absolute top-[13px] inset-x-6 h-[2px] bg-slate-800 z-0"></div>
                                                    <div class="absolute top-[13px] left-6 h-[2px] bg-gradient-to-r from-blue-600 to-emerald-500 z-0 transition-all duration-300"
                                                         style="width: {{ $perm->status === 'approved' ? '100' : ($perm->status_dept_head === 'approved' ? '50' : '0') }}%"></div>

                                                    <!-- Step 1: Draft / Diajukan -->
                                                    <div class="flex flex-col items-center z-10">
                                                        <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] bg-blue-600 text-white shadow shadow-blue-500/20">
                                                            ✓
                                                        </div>
                                                        <span class="text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Diajukan</span>
                                                    </div>

                                                    <!-- Step 2: Kadiv Acc -->
                                                    <div class="flex flex-col items-center z-10">
                                                        @if($perm->status_dept_head === 'approved')
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] bg-emerald-600 text-white shadow shadow-emerald-500/20">
                                                                ✓
                                                            </div>
                                                        @elseif($perm->status_dept_head === 'rejected')
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] bg-rose-600 text-white shadow shadow-rose-500/20">
                                                                ✗
                                                            </div>
                                                        @else
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] bg-slate-800 border border-slate-700 text-slate-400 animate-pulse">
                                                                ⏳
                                                            </div>
                                                        @endif
                                                        <span class="text-[9px] font-bold mt-1 uppercase tracking-wider {{ $perm->status_dept_head === 'approved' ? 'text-emerald-400' : ($perm->status_dept_head === 'rejected' ? 'text-rose-400' : 'text-slate-500') }}">Kadiv</span>
                                                    </div>

                                                    <!-- Step 3: HR Acc -->
                                                    <div class="flex flex-col items-center z-10">
                                                        @if($perm->status_hr === 'approved')
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] bg-emerald-600 text-white shadow shadow-emerald-500/20">
                                                                ✓
                                                            </div>
                                                        @elseif($perm->status_hr === 'rejected')
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] bg-rose-600 text-white shadow shadow-rose-500/20">
                                                                ✗
                                                            </div>
                                                        @else
                                                            <div class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-[9px] bg-slate-800 border border-slate-700 text-slate-400 {{ $perm->status_dept_head === 'approved' ? 'animate-pulse' : '' }}">
                                                                ⏳
                                                            </div>
                                                        @endif
                                                        <span class="text-[9px] font-bold mt-1 uppercase tracking-wider {{ $perm->status_hr === 'approved' ? 'text-emerald-400' : ($perm->status_hr === 'rejected' ? 'text-rose-400' : 'text-slate-500') }}">HR Manager</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Print Button -->
                                        @if($perm->status === 'approved')
                                            <div class="pt-3.5 border-t border-white/5">
                                                <a href="{{ route('letters.permission', $perm->id) }}" target="_blank"
                                                    class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 hover:bg-blue-500/20 hover:text-blue-300 font-extrabold text-[11px] transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                    Cetak Surat Izin Resmi
                                                </a>
                                            </div>
                                        @else
                                            <div class="pt-3.5 border-t border-white/5">
                                                <button disabled class="flex items-center justify-center gap-1.5 w-full py-2.5 rounded-xl bg-white/5 border border-white/5 text-slate-500 font-bold text-[11px] cursor-not-allowed">
                                                    Cetak Surat (Menunggu Persetujuan)
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        @endif

    </div>
</div>
