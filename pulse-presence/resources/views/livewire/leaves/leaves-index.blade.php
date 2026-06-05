<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="heading-1">Ruang Kerja Cuti</h1>
                <p class="mt-1 label-sm">Ajukan permohonan cuti dan pantau saldo cuti tahunan Anda</p>
            </div>
            <div class="mt-4 md:mt-0">
                @if($step === 'index')
                    <button wire:click="$set('step', 'create')" class="btn-sm btn-primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajukan Cuti Baru
                    </button>
                @else
                    <button wire:click="$set('step', 'index')" class="btn-sm btn-secondary">
                        Kembali ke Ringkasan
                    </button>
                @endif
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 sm:p-5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-bold flex items-center">
                <svg class="w-4.5 h-4.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($step === 'index')
            @if (session()->has('error'))
                <div class="mb-6 p-4 sm:p-5 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-xs font-bold flex items-center">
                    <svg class="w-4.5 h-4.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Stage 1: Manager Approval Queue -->
            @if($isManager && $managerQueue->isNotEmpty())
                <div class="mb-8 bg-[#121d33]/85 backdrop-blur-2xl border border-amber-500/20 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-amber-600/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="heading-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Persetujuan Tingkat Manajer
                            </h3>
                            <p class="label-sm">Tahap 1 dari 2 &mdash; setujui untuk meneruskan ke HR</p>
                        </div>
                        <span class="badge-warning">{{ $managerQueue->count() }} Menunggu</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($managerQueue as $pl)
                            @include('livewire.leaves.partials.approval-card', ['pl' => $pl, 'stage' => 'manager'])
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Stage 2: HR Finalization Queue -->
            @if($isHr && $hrQueue->isNotEmpty())
                <div class="mb-8 bg-[#121d33]/85 backdrop-blur-2xl border border-blue-500/20 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                    <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-blue-600/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="heading-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4a2 2 0 00-2 2v1a2 2 0 01-2 2H8a2 2 0 01-2-2v-1a2 2 0 00-2-2H2"/>
                                </svg>
                                Finalisasi HR
                            </h3>
                            <p class="label-sm">Tahap 2 dari 2 &mdash; sudah disetujui Manajer, menunggu keputusan final HR</p>
                        </div>
                        <span class="badge-info">{{ $hrQueue->count() }} Menunggu</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($hrQueue as $pl)
                            @include('livewire.leaves.partials.approval-card', ['pl' => $pl, 'stage' => 'hr'])
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Annual Balance -->
                <div class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-blue-500/20 rounded-2xl p-6 shadow-[0_0_15px_rgba(59,130,246,0.05)] hover:shadow-[0_0_25px_rgba(59,130,246,0.2)] hover:border-blue-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-blue-500 via-indigo-500 to-cyan-400 opacity-80"></div>
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-500/5 rounded-full blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="label-xs font-black uppercase tracking-wider text-blue-400/90 group-hover:text-blue-300 transition-colors">Saldo Cuti Tahunan</span>
                        <span class="w-8 h-8 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-400 font-bold label-sm">{{ $annualBalance }}</span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-blue-200 drop-shadow-[0_0_8px_rgba(59,130,246,0.45)]">{{ $annualBalance }} <span class="label-sm font-medium text-slate-400">hari tersisa</span></div>
                    <p class="label-xs mt-2 font-medium">Berlaku s/d 31 Des 2026. Diperbarui otomatis setiap tahun.</p>
                </div>

                <!-- Sick Leaves -->
                <div class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-emerald-500/20 rounded-2xl p-6 shadow-[0_0_15px_rgba(16,185,129,0.05)] hover:shadow-[0_0_25px_rgba(16,185,129,0.2)] hover:border-emerald-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-emerald-500 via-teal-500 to-green-400 opacity-80"></div>
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="label-xs font-black uppercase tracking-wider text-emerald-400/90 group-hover:text-emerald-300 transition-colors">Cuti Sakit Terpakai</span>
                        <span class="w-8 h-8 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold label-sm">{{ $sickDays }}</span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-200 drop-shadow-[0_0_8px_rgba(16,185,129,0.45)]">{{ $sickDays }} <span class="label-sm font-medium text-slate-400">hari diambil</span></div>
                    <p class="label-xs mt-2 font-medium">Tidak terbatas dengan melampirkan surat keterangan dokter.</p>
                </div>

                <!-- Special Leaves -->
                <div class="relative overflow-hidden bg-gradient-to-br from-[#121d33]/85 to-[#0b1222]/95 border border-purple-500/20 rounded-2xl p-6 shadow-[0_0_15px_rgba(168,85,247,0.05)] hover:shadow-[0_0_25px_rgba(168,85,247,0.2)] hover:border-purple-500/40 hover:translate-y-[-4px] transition-all duration-300 group">
                    <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-400 opacity-80"></div>
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-purple-500/5 rounded-full blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="label-xs font-black uppercase tracking-wider text-purple-400/90 group-hover:text-purple-300 transition-colors">Cuti Khusus Terpakai</span>
                        <span class="w-8 h-8 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400 font-bold label-sm">{{ $specialDays }}</span>
                    </div>
                    <div class="text-3xl sm:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-purple-200 drop-shadow-[0_0_8px_rgba(168,85,247,0.45)]">{{ $specialDays }} <span class="label-sm font-medium text-slate-400">hari diambil</span></div>
                    <p class="label-xs mt-2 font-medium">Mencakup pelatihan, berita duka, dan acara keluarga besar.</p>
                </div>
            </div>

            <!-- Leaves History Table -->
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h3 class="heading-3">Riwayat Pengajuan Cuti Anda</h3>
                    <span class="label-sm">Menampilkan pengajuan terbaru</span>
                </div>
                <div class="overflow-x-auto">
                    @if($myLeaves->isEmpty())
                        <div class="text-xs text-slate-500 font-bold text-center py-8 bg-[#0d1527]/50 rounded-2xl">
                            Anda belum pernah mengajukan cuti.
                        </div>
                    @else
                        <table class="w-full min-w-max text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 label-xs font-bold text-slate-400">
                                    <th class="pb-3 w-[150px]" style="width: 150px;">Tipe Cuti</th>
                                    <th class="pb-3 w-[150px]" style="width: 150px;">Tanggal Mulai</th>
                                    <th class="pb-3 w-[150px]" style="width: 150px;">Tanggal Selesai</th>
                                    <th class="pb-3 w-[100px]" style="width: 100px;">Durasi</th>
                                    <th class="pb-3 w-[250px]" style="width: 250px;">Alasan</th>
                                    <th class="pb-3 w-[120px] text-right" style="width: 120px;">Status</th>
                                    <th class="pb-3 w-[80px] text-right" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-xs text-slate-300">
                                @foreach($myLeaves as $ml)
                                    <tr>
                                        <td class="py-4 label-md font-bold text-white">{{ ucfirst($ml->leave_type) }}</td>
                                        <td class="py-4 label-sm">{{ \Carbon\Carbon::parse($ml->start_date)->translatedFormat('d F Y') }}</td>
                                        <td class="py-4 label-sm">{{ \Carbon\Carbon::parse($ml->end_date)->translatedFormat('d F Y') }}</td>
                                        <td class="py-4 label-sm">{{ $ml->total_days }} Hari</td>
                                        <td class="py-4 label-sm text-slate-400">{{ $ml->reason }}</td>
                                        <td class="py-4 text-right">
                                            @if($ml->status === 'pending')
                                                <span class="badge-rect-warning">Menunggu Manajer</span>
                                            @elseif($ml->status === 'manager_approved')
                                                <span class="badge-rect-info">Menunggu HR</span>
                                            @elseif($ml->status === 'hr_approved')
                                                <span class="badge-rect-success">Disetujui</span>
                                            @else
                                                <span class="badge-rect-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <a href="{{ route('letters.leave', $ml->id) }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-blue-400 hover:text-blue-300 font-bold transition-colors text-[11px]">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                                Cetak
                                            </a>
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
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl max-w-2xl mx-auto relative overflow-hidden">
                <h3 class="heading-3 mb-6">Ajukan Permohonan Cuti</h3>
                
                <form wire:submit.prevent="submitRequest" class="space-y-5">
                    <div>
                        <label class="block label-xs mb-2">Kategori Cuti</label>
                        <select wire:model.live="type" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="annual">Cuti Tahunan (Kuota: {{ auth()->user()->annual_leave_quota ?? 12 }} hari)</option>
                            <option value="sick">Cuti Sakit (Wajib melampirkan keterangan medis)</option>
                            <option value="special">Cuti Khusus / Pelatihan</option>
                            <option value="unpaid">Cuti Di Luar Tanggungan (Unpaid Leave)</option>
                        </select>
                        @error('type') <span class="label-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block label-xs mb-2">Tanggal Mulai</label>
                            <input wire:model="start_date" type="date" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                            @error('start_date') <span class="label-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block label-xs mb-2">Tanggal Selesai</label>
                            <input wire:model="end_date" type="date" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                            @error('end_date') <span class="label-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block label-xs mb-2">Alasan & Keterangan</label>
                        <textarea wire:model="reason" rows="4" required placeholder="Berikan informasi mengenai penyerahan tugas atau urgensi cuti Anda..." class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all resize-none"></textarea>
                        @error('reason') <span class="label-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-2">
                            Lampiran
                            @if($type === 'sick')
                                <span class="text-rose-400 font-bold">(Wajib &mdash; surat keterangan dokter)</span>
                            @else
                                <span class="text-slate-500">(Opsional &mdash; PDF/JPG/PNG, maks 2MB)</span>
                            @endif
                        </label>
                        <input wire:model="attachment" type="file" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-slate-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-500/20 file:text-blue-300 hover:file:bg-blue-500/30 focus:outline-none focus:border-blue-500 transition-all">
                        <div wire:loading wire:target="attachment" class="label-xs text-blue-400 mt-1">Mengunggah berkas...</div>
                        @error('attachment') <span class="label-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" wire:click="$set('step', 'index')" class="btn-sm btn-secondary">
                            Batal
                        </button>
                        <button type="submit" class="btn-sm btn-primary">
                            Kirim Permohonan Cuti
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Approval / Rejection Action Modal -->
        @if($showActionModal)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" wire:click.self="$set('showActionModal', false)">
                <div class="w-full max-w-md bg-[#121d33] border border-white/10 rounded-2xl p-6 shadow-2xl">
                    @php
                        $isReject = $actionMode === 'reject';
                        $titleTxt = $isReject
                            ? 'Tolak Permohonan Cuti'
                            : ($actionStage === 'manager' ? 'Setujui sebagai Manajer' : 'Finalisasi sebagai HR');
                    @endphp
                    <h3 class="heading-3 mb-1">{{ $titleTxt }}</h3>
                    <p class="label-sm mb-4">
                        {{ $isReject
                            ? 'Catatan alasan penolakan wajib diisi dan akan tampil pada riwayat karyawan.'
                            : 'Anda dapat menambahkan catatan (opsional) sebelum menyetujui.' }}
                    </p>

                    <label class="block label-xs mb-2">Catatan {{ $isReject ? '(wajib)' : '(opsional)' }}</label>
                    <textarea wire:model="actionNotes" rows="3" class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all resize-none" placeholder="Tulis catatan di sini..."></textarea>
                    @error('actionNotes') <span class="label-xs text-rose-400 block mt-1">{{ $message }}</span> @enderror

                    <div class="flex justify-end gap-3 mt-5">
                        <button type="button" wire:click="$set('showActionModal', false)" class="btn-sm btn-secondary">Batal</button>
                        <button type="button" wire:click="submitAction" class="btn-sm {{ $isReject ? 'btn-danger' : 'btn-primary' }}">
                            {{ $isReject ? 'Tolak Permohonan' : 'Konfirmasi Persetujuan' }}
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
