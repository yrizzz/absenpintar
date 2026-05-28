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
                            <span class="label-xs text-slate-500 mt-1 block">Pilih kategori dispensasi izin kerja.</span>
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
                        <textarea wire:model="reason" rows="4" required placeholder="Tuliskan detail alasan pengajuan izin Anda di sini..." class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all resize-none"></textarea>
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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left: Tinjau Pengajuan Karyawan (Dept Head & HR ACC) -->
                @if($isAdmin)
                    <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                            <div>
                                <h3 class="heading-3">Tinjau Pengajuan Izin Karyawan</h3>
                                <p class="label-sm mt-0.5">Lakukan verifikasi, ACC Kepala Divisi, dan ACC HR Manager untuk permohonan aktif.</p>
                            </div>
                            <span class="badge-info self-start sm:self-auto">
                                Total Menunggu: {{ $pendingRequests->count() }}
                            </span>
                        </div>

                        @if($pendingRequests->isEmpty())
                            <div class="py-12 flex flex-col items-center justify-center border border-white/5 bg-[#0d1527]/50 rounded-2xl">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 mb-3 shadow-lg shadow-emerald-500/5">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="label-sm font-bold text-slate-300">Semua bersih! Tidak ada antrean persetujuan izin.</span>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-max text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-white/5 label-xs font-bold text-slate-400">
                                            <th class="pb-3 w-[200px]" style="width: 200px;">Karyawan</th>
                                            <th class="pb-3 w-[120px]" style="width: 120px;">Tipe izin</th>
                                            <th class="pb-3 w-[150px]" style="width: 150px;">Tanggal & waktu</th>
                                            <th class="pb-3 w-[250px]" style="width: 250px;">Alasan</th>
                                            <th class="pb-3 w-[110px] text-center" style="width: 110px;">Acc divisi</th>
                                            <th class="pb-3 w-[110px] text-center" style="width: 110px;">Acc HR</th>
                                            <th class="pb-3 w-[120px] text-right" style="width: 120px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5 text-slate-200">
                                        @foreach($pendingRequests as $req)
                                            <tr class="align-middle">
                                                <td class="py-4">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="w-8.5 h-8.5 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs">
                                                            {{ substr($req->user->name, 0, 1) }}
                                                        </div>
                                                        <div>
                                                            <div class="label-sm font-bold text-white">{{ $req->user->name }}</div>
                                                            <div class="label-xs font-bold text-blue-400">{{ $req->user->employee_id }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 text-xs font-bold text-slate-300">
                                                    @if($req->type === 'ijin_datang_terlambat')
                                                        <span class="badge-rect-warning">Telat</span>
                                                    @elseif($req->type === 'ijin_pulang_awal')
                                                        <span class="badge-rect-danger">Pulang Awal</span>
                                                    @elseif($req->type === 'ijin_setengah_hari')
                                                        <span class="badge-rect-info">1/2 Hari</span>
                                                    @else
                                                        <span class="badge-rect-neutral">Tidak Masuk</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 label-sm font-medium text-slate-300">
                                                    <div class="font-bold">{{ $req->date->format('d M Y') }}</div>
                                                    @if($req->type !== 'ijin_tidak_masuk')
                                                        <div class="label-xs text-slate-500 mt-0.5">{{ substr($req->start_time, 0, 5) }} - {{ substr($req->end_time, 0, 5) }}</div>
                                                    @endif
                                                </td>
                                                <td class="py-4 label-sm text-slate-400 max-w-xs truncate" title="{{ $req->reason }}">
                                                    {{ $req->reason }}
                                                </td>
                                                <td class="py-4 text-center">
                                                    @if($req->status_dept_head === 'approved')
                                                        <span class="badge-rect-success">Disetujui</span>
                                                    @elseif($req->status_dept_head === 'rejected')
                                                        <span class="badge-rect-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge-rect-neutral">Tertunda</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 text-center">
                                                    @if($req->status_hr === 'approved')
                                                        <span class="badge-rect-success">Disetujui</span>
                                                    @elseif($req->status_hr === 'rejected')
                                                        <span class="badge-rect-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge-rect-neutral">Tertunda</span>
                                                    @endif
                                                </td>
                                                <td class="py-4 text-right">
                                                    <div class="flex items-center justify-end space-x-1.5">
                                                        <!-- Department Head ACC Button -->
                                                        @if($isManager && $req->status_dept_head === 'pending')
                                                            <button wire:click="approveDeptHead({{ $req->id }})" type="button" class="btn-success btn-xs shadow">
                                                                Acc Divisi
                                                            </button>
                                                        @endif

                                                        <!-- HR ACC Button -->
                                                        @if($isHr && $req->status_hr === 'pending')
                                                            <button wire:click="approveHr({{ $req->id }})" type="button" class="btn-primary btn-xs shadow">
                                                                Acc HR
                                                            </button>
                                                        @endif

                                                        <button wire:click="rejectRequest({{ $req->id }})" type="button" class="btn-danger-outline btn-xs">
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
                @endif

                <!-- Middle & Right: My Permission History -->
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-3 mt-4">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                        <div>
                            <h3 class="heading-3">Riwayat Pengajuan Izin Saya</h3>
                            <p class="label-sm mt-0.5">Daftar permohonan dispensasi izin kerja yang pernah Anda ajukan beserta status verifikasi ganda.</p>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex overflow-x-auto whitespace-nowrap bg-[#0d1527] border border-white/5 p-1 rounded-xl scrollbar-none">
                            <button wire:click="$set('statusFilter', 'all')" type="button" class="px-3 py-1.5 label-xs rounded-lg transition-all flex-shrink-0 {{ $statusFilter === 'all' ? 'tab-active' : 'text-slate-300 hover:text-white' }}">
                                Semua
                            </button>
                            <button wire:click="$set('statusFilter', 'approved')" type="button" class="px-3 py-1.5 label-xs rounded-lg transition-all flex-shrink-0 {{ $statusFilter === 'approved' ? 'tab-active' : 'text-slate-300 hover:text-white' }}">
                                Disetujui
                            </button>
                            <button wire:click="$set('statusFilter', 'pending')" type="button" class="px-3 py-1.5 label-xs rounded-lg transition-all flex-shrink-0 {{ $statusFilter === 'pending' ? 'tab-active' : 'text-slate-300 hover:text-white' }}">
                                Pending
                        </div>
                    </div>

                    @if($myPermissions->isEmpty())
                        <div class="py-12 flex flex-col items-center justify-center border border-white/5 bg-[#0d1527]/50 rounded-2xl">
                                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 mb-3 shadow-lg shadow-blue-500/5">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                    </svg>
                                </div>
                                <span class="label-sm font-bold text-slate-300">Belum ada data pengajuan izin kerja.</span>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($myPermissions as $perm)
                                <div class="bg-[#0d1527] border border-white/5 rounded-2xl p-5 relative overflow-hidden space-y-3.5">
                                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-blue-500/5 rounded-full blur-xl"></div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="label-md font-bold text-white">
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
                                        </div>
                                        
                                        <!-- Overall status badge -->
                                        @if($perm->status === 'approved')
                                            <span class="badge-rect-success">Disetujui</span>
                                        @elseif($perm->status === 'rejected')
                                            <span class="badge-rect-danger">Ditolak</span>
                                        @else
                                            <span class="badge-rect-warning">Menunggu acc</span>
                                        @endif
                                    </div>
 
                                    <div class="label-xs text-slate-400 space-y-1">
                                        <div class="flex justify-between">
                                            <span>Tanggal:</span>
                                            <span class="font-bold text-white">{{ $perm->date->format('d M Y') }}</span>
                                        </div>
                                        @if($perm->type !== 'ijin_tidak_masuk')
                                            <div class="flex justify-between">
                                                <span>Waktu dispensasi:</span>
                                                <span class="font-bold text-blue-400">{{ substr($perm->start_time, 0, 5) }} s/d {{ substr($perm->end_time, 0, 5) }}</span>
                                            </div>
                                        @endif
                                        <div class="flex flex-col pt-1.5 border-t border-white/5">
                                            <span class="label-xs font-bold text-slate-500">Alasan pengajuan:</span>
                                            <span class="mt-0.5 label-sm text-slate-300 leading-relaxed">{{ $perm->reason }}</span>
                                        </div>
                                    </div>
 
                                    <!-- Multi-level signature display -->
                                    <div class="grid grid-cols-2 gap-3 pt-3.5 border-t border-white/5 text-[10px]">
                                        <div class="p-2 bg-white/5 rounded-xl flex flex-col justify-center items-center text-center">
                                            <span class="label-xs text-slate-500">Kepala divisi</span>
                                            <span class="font-bold mt-1 label-xs {{ $perm->status_dept_head === 'approved' ? 'text-emerald-400' : ($perm->status_dept_head === 'rejected' ? 'text-rose-400' : 'text-slate-500') }}">
                                                {{ $perm->status_dept_head === 'approved' ? 'Disetujui' : ($perm->status_dept_head === 'rejected' ? 'Ditolak' : 'Pending') }}
                                            </span>
                                        </div>
 
                                        <div class="p-2 bg-white/5 rounded-xl flex flex-col justify-center items-center text-center">
                                            <span class="label-xs text-slate-500">HR manager</span>
                                            <span class="font-bold mt-1 label-xs {{ $perm->status_hr === 'approved' ? 'text-emerald-400' : ($perm->status_hr === 'rejected' ? 'text-rose-400' : 'text-slate-500') }}">
                                                {{ $perm->status_hr === 'approved' ? 'Disetujui' : ($perm->status_hr === 'rejected' ? 'Ditolak' : 'Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        @endif

    </div>
</div>
