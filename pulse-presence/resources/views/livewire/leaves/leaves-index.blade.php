<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Ruang Kerja Cuti</h1>
                <p class="mt-1 text-sm text-slate-400 font-medium">Ajukan permohonan cuti dan pantau saldo cuti tahunan Anda</p>
            </div>
            <div class="mt-4 md:mt-0">
                @if($step === 'index')
                    <button wire:click="$set('step', 'create')" class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-button-primary hover:translate-y-[-1px] transition-all cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajukan Cuti Baru
                    </button>
                @else
                    <button wire:click="$set('step', 'index')" class="inline-flex items-center px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-2xl hover:text-white transition-all cursor-pointer">
                        Kembali ke Ringkasan
                    </button>
                @endif
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-bold flex items-center">
                <svg class="w-4.5 h-4.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($step === 'index')
            <!-- Admin Leave Approval Inbox Panel -->
            @if($isAdmin && $pendingLeaves->isNotEmpty())
                <div class="mb-8 bg-[#121d33]/85 backdrop-blur-2xl border border-sky-500/20 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-sky-500/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-white tracking-tight font-display">📥 Kotak Masuk Persetujuan Cuti</h3>
                            <p class="text-xs text-slate-400 font-medium">Tinjau dan proses permohonan cuti aktif karyawan</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-sky-500/10 border border-sky-500/20 text-sky-400 uppercase tracking-widest">
                            {{ $pendingLeaves->count() }} Menunggu
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pendingLeaves as $pl)
                            <div class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4 flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-9 h-9 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-400 font-black text-xs">
                                                {{ strtoupper(substr($pl->user->name ?? 'K', 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="block text-xs font-bold text-white">{{ $pl->user->name ?? 'N/A' }}</span>
                                                <span class="block text-[10px] text-slate-400">ID Karyawan: {{ $pl->user->employee_id ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-white/5 border border-white/10 text-slate-300 uppercase">
                                            {{ strtoupper($pl->leave_type) }}
                                        </span>
                                    </div>
                                    <div class="mt-3 bg-white/5 rounded-xl p-3 text-[11px] text-slate-300">
                                        <p class="font-semibold text-sky-300">Rentang: {{ \Carbon\Carbon::parse($pl->start_date)->translatedFormat('d M') }} s/d {{ \Carbon\Carbon::parse($pl->end_date)->translatedFormat('d M Y') }} ({{ $pl->total_days }} Hari)</p>
                                        <p class="mt-1 text-slate-400 font-medium leading-relaxed">"{{ $pl->reason }}"</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click="approveLeave({{ $pl->id }})" class="flex-1 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer text-center">
                                        Setujui
                                    </button>
                                    <button wire:click="rejectLeave({{ $pl->id }})" class="flex-1 py-2 bg-rose-500/10 border border-rose-500/20 hover:bg-rose-500 hover:text-white text-rose-400 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer text-center">
                                        Tolak
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Annual Balance -->
                <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/5 rounded-full blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Saldo Cuti Tahunan</span>
                        <span class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 font-bold">{{ $annualBalance }}</span>
                    </div>
                    <div class="text-3xl font-black text-white font-display">{{ $annualBalance }} <span class="text-xs text-slate-400 font-medium">hari tersisa</span></div>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Berlaku s/d 31 Des 2026. Diperbarui otomatis setiap tahun.</p>
                </div>

                <!-- Sick Leaves -->
                <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cuti Sakit Terpakai</span>
                        <span class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold">{{ $sickDays }}</span>
                    </div>
                    <div class="text-3xl font-black text-white font-display">{{ $sickDays }} <span class="text-xs text-slate-400 font-medium">hari diambil</span></div>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Tidak terbatas dengan melampirkan surat keterangan dokter.</p>
                </div>

                <!-- Special Leaves -->
                <div class="bg-[#121d33]/55 backdrop-blur-xl border border-white/5 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-500/5 rounded-full blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cuti Khusus Terpakai</span>
                        <span class="w-8 h-8 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 font-bold">{{ $specialDays }}</span>
                    </div>
                    <div class="text-3xl font-black text-white font-display">{{ $specialDays }} <span class="text-xs text-slate-400 font-medium">hari diambil</span></div>
                    <p class="text-[10px] text-slate-500 mt-2 font-medium">Mencakup pelatihan, berita duka, dan acara keluarga besar.</p>
                </div>
            </div>

            <!-- Leaves History Table -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-white tracking-tight font-display">Riwayat Pengajuan Cuti Anda</h3>
                    <span class="text-xs font-semibold text-slate-400">Menampilkan pengajuan terbaru</span>
                </div>
                <div class="overflow-x-auto">
                    @if($myLeaves->isEmpty())
                        <div class="text-xs text-slate-500 font-bold uppercase tracking-wider text-center py-8 bg-[#0d1527]/50 rounded-2xl">
                            Anda belum pernah mengajukan cuti.
                        </div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    <th class="pb-3">Tipe Cuti</th>
                                    <th class="pb-3">Tanggal Mulai</th>
                                    <th class="pb-3">Tanggal Selesai</th>
                                    <th class="pb-3">Durasi</th>
                                    <th class="pb-3">Alasan</th>
                                    <th class="pb-3 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-xs text-slate-300">
                                @foreach($myLeaves as $ml)
                                    <tr>
                                        <td class="py-4 font-bold text-white">{{ strtoupper($ml->leave_type) }}</td>
                                        <td class="py-4">{{ \Carbon\Carbon::parse($ml->start_date)->translatedFormat('d F Y') }}</td>
                                        <td class="py-4">{{ \Carbon\Carbon::parse($ml->end_date)->translatedFormat('d F Y') }}</td>
                                        <td class="py-4">{{ $ml->total_days }} Hari</td>
                                        <td class="py-4 text-slate-400">{{ $ml->reason }}</td>
                                        <td class="py-4 text-right">
                                            @if($ml->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 uppercase tracking-wider">Menunggu Tinjauan</span>
                                            @elseif($ml->status === 'hr_approved' || $ml->status === 'manager_approved')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase tracking-wider">Disetujui</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold bg-rose-500/10 border border-rose-500/20 text-rose-400 uppercase tracking-wider">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @else
            <!-- Request Leave Form -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl max-w-2xl mx-auto relative overflow-hidden">
                <h3 class="text-xl font-extrabold text-white tracking-tight font-display mb-6">Ajukan Permohonan Cuti</h3>
                
                <form wire:submit.prevent="submitRequest" class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori Cuti</label>
                        <select wire:model="type" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="annual">Cuti Tahunan (Kuota: 12 hari)</option>
                            <option value="sick">Cuti Sakit (Wajib melampirkan keterangan medis)</option>
                            <option value="special">Cuti Khusus / Pelatihan</option>
                            <option value="unpaid">Cuti Di Luar Tanggungan (Unpaid Leave)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Mulai</label>
                            <input wire:model="start_date" type="date" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                            @error('start_date') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                            <input wire:model="end_date" type="date" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                            @error('end_date') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Alasan & Keterangan</label>
                        <textarea wire:model="reason" rows="4" required placeholder="Berikan informasi mengenai penyerahan tugas atau urgensi cuti Anda..." class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-sky-500 transition-all resize-none"></textarea>
                        @error('reason') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="$set('step', 'index')" class="px-5 py-3 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-2xl hover:text-white transition-all cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-button-primary hover:translate-y-[-1px] transition-all cursor-pointer">
                            Kirim Permohonan Cuti
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>
