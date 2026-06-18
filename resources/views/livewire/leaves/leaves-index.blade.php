<div class="py-8 min-h-screen">
    <div class="px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
            <div>
                <h1 class="heading-1">Ruang Kerja Cuti</h1>
                <p class="mt-1 label-sm">Ajukan permohonan cuti dan pantau saldo cuti tahunan Anda.</p>
            </div>
            <div>
                @if($step === 'index')
                    <button wire:click="$set('step', 'create')" class="btn-primary btn-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Ajukan Cuti Baru
                    </button>
                @else
                    <button wire:click="$set('step', 'index')" class="btn-secondary btn-sm">Kembali ke Ringkasan</button>
                @endif
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 flex items-center gap-2 rounded-xl border border-success/30 bg-success-soft p-4 text-sm font-medium text-success">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($step === 'index')
            @if (session()->has('error'))
                <div class="mb-6 flex items-center gap-2 rounded-xl border border-danger/30 bg-danger-soft p-4 text-sm font-medium text-danger">
                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Manager queue --}}
            @if($isManager && $managerQueue->isNotEmpty())
                <div class="card p-6 sm:p-8 mb-8 rounded-2xl">
                    <div class="flex items-center justify-between mb-5 gap-3">
                        <div>
                            <h3 class="heading-3 flex items-center gap-2">
                                <svg class="h-5 w-5 text-warning" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Persetujuan Tingkat Manajer
                            </h3>
                            <p class="label-sm">Tahap 1 dari 2 &mdash; setujui untuk meneruskan ke HR.</p>
                        </div>
                        <span class="badge-warning flex-shrink-0">{{ $managerQueue->count() }} Menunggu</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-border label-xs uppercase tracking-wide">
                                    <th class="px-5 py-3 w-[25%]">Karyawan</th>
                                    <th class="px-5 py-3 w-[15%]">Tipe Cuti</th>
                                    <th class="px-5 py-3 w-[20%]">Tanggal</th>
                                    <th class="px-5 py-3 w-[10%]">Durasi</th>
                                    <th class="px-5 py-3">Alasan</th>
                                    <th class="px-5 py-3 w-[15%] text-right font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($managerQueue as $pl)
                                    <tr class="hover:bg-surface-muted transition-colors">
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 items-center justify-center rounded-lg text-xs font-semibold bg-warning-soft text-warning">
                                                    {{ strtoupper(substr($pl->user->name ?? 'K', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-semibold text-fg leading-tight">{{ $pl->user->name ?? 'N/A' }}</span>
                                                    <span class="block text-[11px] text-fg-subtle mt-0.5">ID: {{ $pl->user->employee_id ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-md bg-surface-muted px-2.5 py-0.5 text-xs font-semibold text-fg border border-border">{{ ucfirst($pl->leave_type) }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-fg-muted font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($pl->start_date)->translatedFormat('d M') }} s/d {{ \Carbon\Carbon::parse($pl->end_date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-fg font-bold whitespace-nowrap">
                                            {{ $pl->total_days }} Hari
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-fg-muted max-w-[200px] truncate">
                                            "{{ $pl->reason }}"
                                        </td>
                                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                            <button wire:click="openAction({{ $pl->id }}, 'manager')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary/10 border border-primary/20 px-3 py-1.5 text-xs font-bold text-primary hover:bg-primary hover:text-white transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                Tinjau
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- HR queue --}}
            @if($isHr && $hrQueue->isNotEmpty())
                <div class="card p-6 sm:p-8 mb-8 rounded-2xl">
                    <div class="flex items-center justify-between mb-5 gap-3">
                        <div>
                            <h3 class="heading-3 flex items-center gap-2">
                                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4a2 2 0 00-2 2v1a2 2 0 01-2 2H8a2 2 0 01-2-2v-1a2 2 0 00-2-2H2" /></svg>
                                Finalisasi HR
                            </h3>
                            <p class="label-sm">Tahap 2 dari 2 &mdash; sudah disetujui Manajer, menunggu keputusan final HR.</p>
                        </div>
                        <span class="badge-info flex-shrink-0">{{ $hrQueue->count() }} Menunggu</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-border label-xs uppercase tracking-wide">
                                    <th class="px-5 py-3 w-[22%]">Karyawan</th>
                                    <th class="px-5 py-3 w-[12%]">Tipe Cuti</th>
                                    <th class="px-5 py-3 w-[18%]">Tanggal</th>
                                    <th class="px-5 py-3 w-[8%]">Durasi</th>
                                    <th class="px-5 py-3 w-[22%]">Alasan</th>
                                    <th class="px-5 py-3 w-[10%]">Manajer</th>
                                    <th class="px-5 py-3 w-[8%] text-right font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($hrQueue as $pl)
                                    <tr class="hover:bg-surface-muted transition-colors">
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                <div class="flex h-9 w-9 items-center justify-center rounded-lg text-xs font-semibold bg-info-soft text-info">
                                                    {{ strtoupper(substr($pl->user->name ?? 'K', 0, 2)) }}
                                                </div>
                                                <div>
                                                    <span class="block text-sm font-semibold text-fg leading-tight">{{ $pl->user->name ?? 'N/A' }}</span>
                                                    <span class="block text-[11px] text-fg-subtle mt-0.5">ID: {{ $pl->user->employee_id ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-md bg-surface-muted px-2.5 py-0.5 text-xs font-semibold text-fg border border-border">{{ ucfirst($pl->leave_type) }}</span>
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-fg-muted font-medium whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($pl->start_date)->translatedFormat('d M') }} s/d {{ \Carbon\Carbon::parse($pl->end_date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-fg font-bold whitespace-nowrap">
                                            {{ $pl->total_days }} Hari
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-fg-muted max-w-[180px] truncate">
                                            "{{ $pl->reason }}"
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-success font-medium whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 px-2 py-0.5 text-[11px] font-semibold text-emerald-600">
                                                {{ $pl->manager->name ?? 'Manajer' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                            <button wire:click="openAction({{ $pl->id }}, 'hr')" class="inline-flex items-center gap-1.5 rounded-lg bg-primary/10 border border-primary/20 px-3 py-1.5 text-xs font-bold text-primary hover:bg-primary hover:text-white transition-all">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                Tinjau
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            {{-- Overview cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @foreach ([
                    ['Saldo Cuti Tahunan', $annualBalance, 'hari tersisa', 'Berlaku s/d 31 Des 2026. Diperbarui otomatis setiap tahun.', 'bg-info-soft text-info'],
                    ['Cuti Sakit Terpakai', $sickDays, 'hari diambil', 'Tidak terbatas dengan melampirkan surat keterangan dokter.', 'bg-success-soft text-success'],
                    ['Cuti Khusus Terpakai', $specialDays, 'hari diambil', 'Mencakup pelatihan, berita duka, dan acara keluarga besar.', 'bg-info-soft text-info'],
                ] as [$title, $value, $unit, $caption, $iconClass])
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="label-xs uppercase tracking-wide">{{ $title }}</span>
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg text-sm font-semibold {{ $iconClass }}">{{ $value }}</span>
                        </div>
                        <div class="heading-value">{{ $value }} <span class="label-sm font-normal">{{ $unit }}</span></div>
                        <p class="label-xs mt-2">{{ $caption }}</p>
                    </div>
                @endforeach
            </div>

            {{-- History --}}
            <div class="card p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h3 class="heading-3">Riwayat Pengajuan Cuti Anda</h3>
                    <span class="label-sm">Menampilkan pengajuan terbaru.</span>
                </div>
                @if($myLeaves->isEmpty())
                    <div class="text-sm text-fg-muted text-center py-10 rounded-xl bg-surface-muted">Anda belum pernah mengajukan cuti.</div>
                @else
                    @php
                        $leaveStatusBadge = function ($status) {
                            return match ($status) {
                                'pending' => ['badge-rect-warning', 'Menunggu Manajer'],
                                'manager_approved' => ['badge-rect-info', 'Menunggu HR'],
                                'hr_approved' => ['badge-rect-success', 'Disetujui'],
                                default => ['badge-rect-danger', 'Ditolak'],
                            };
                        };
                    @endphp

                    {{-- Desktop table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-border label-xs uppercase tracking-wide">
                                    <th class="px-5 py-3.5 w-[12%]">Tipe Cuti</th>
                                    <th class="px-5 py-3.5 w-[14%]">Tanggal Mulai</th>
                                    <th class="px-5 py-3.5 w-[14%]">Tanggal Selesai</th>
                                    <th class="px-5 py-3.5 w-[8%]">Durasi</th>
                                    <th class="px-5 py-3.5">Alasan</th>
                                    <th class="px-5 py-3.5 w-[12%] text-right">Status</th>
                                    <th class="px-5 py-3.5 w-[8%] text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                @foreach($myLeaves as $ml)
                                    @php $lb = $leaveStatusBadge($ml->status); @endphp
                                    <tr class="hover:bg-surface-muted transition-colors">
                                        <td class="px-5 py-4 label-md whitespace-nowrap">{{ ucfirst($ml->leave_type) }}</td>
                                        <td class="px-5 py-4 label-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($ml->start_date)->translatedFormat('d F Y') }}</td>
                                        <td class="px-5 py-4 label-sm whitespace-nowrap">{{ \Carbon\Carbon::parse($ml->end_date)->translatedFormat('d F Y') }}</td>
                                        <td class="px-5 py-4 label-sm whitespace-nowrap">{{ $ml->total_days }} Hari</td>
                                        <td class="px-5 py-4 label-sm max-w-[260px] truncate">{{ $ml->reason }}</td>
                                        <td class="px-5 py-4 text-right"><span class="{{ $lb[0] }}">{{ $lb[1] }}</span></td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('letters.leave', $ml->id) }}" target="_blank" class="inline-flex items-center gap-1 text-sm font-medium text-primary hover:opacity-80 transition-opacity">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                                Cetak
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="md:hidden space-y-3">
                        @foreach($myLeaves as $ml)
                            @php $lb = $leaveStatusBadge($ml->status); @endphp
                            <div class="rounded-xl border border-border bg-surface-muted p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="label-md">{{ ucfirst($ml->leave_type) }}</div>
                                    <span class="{{ $lb[0] }} flex-shrink-0">{{ $lb[1] }}</span>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-2">
                                    <div><div class="label-xs uppercase tracking-wide">Mulai</div><div class="label-sm font-medium text-fg">{{ \Carbon\Carbon::parse($ml->start_date)->translatedFormat('d M Y') }}</div></div>
                                    <div><div class="label-xs uppercase tracking-wide">Selesai</div><div class="label-sm font-medium text-fg">{{ \Carbon\Carbon::parse($ml->end_date)->translatedFormat('d M Y') }}</div></div>
                                    <div><div class="label-xs uppercase tracking-wide">Durasi</div><div class="label-sm font-medium text-fg">{{ $ml->total_days }} Hari</div></div>
                                </div>
                                @if($ml->reason)
                                    <div class="mt-3"><div class="label-xs uppercase tracking-wide">Alasan</div><div class="label-sm">{{ $ml->reason }}</div></div>
                                @endif
                                <div class="mt-3 border-t border-border pt-3">
                                    <a href="{{ route('letters.leave', $ml->id) }}" target="_blank" class="inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:opacity-80 transition-opacity">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                        Cetak Surat Cuti
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            {{-- Request form --}}
            <div class="card p-6 sm:p-8 max-w-2xl mx-auto">
                <h3 class="heading-3 mb-6">Ajukan Permohonan Cuti</h3>
                <form wire:submit.prevent="submitRequest" class="space-y-5">
                    <div class="space-y-1.5">
                        <label class="label">Kategori Cuti</label>
                        <select wire:model.live="type" class="cursor-pointer">
                            <option value="annual">Cuti Tahunan (Kuota: {{ auth()->user()->annual_leave_quota ?? 12 }} hari)</option>
                            <option value="sick">Cuti Sakit (Wajib melampirkan keterangan medis)</option>
                            <option value="special">Cuti Khusus / Pelatihan</option>
                            <option value="unpaid">Cuti Di Luar Tanggungan (Unpaid Leave)</option>
                        </select>
                        @error('type') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="label">Tanggal Mulai</label>
                            <input wire:model="start_date" type="date" required>
                            @error('start_date') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="label">Tanggal Selesai</label>
                            <input wire:model="end_date" type="date" required>
                            @error('end_date') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="label">Alasan &amp; Keterangan</label>
                        <textarea wire:model="reason" rows="4" required placeholder="Berikan informasi mengenai penyerahan tugas atau urgensi cuti Anda…"></textarea>
                        @error('reason') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="label">
                            Lampiran
                            @if($type === 'sick')
                                <span class="text-danger font-medium">(Wajib &mdash; surat keterangan dokter)</span>
                            @else
                                <span class="text-fg-subtle font-normal">(Opsional &mdash; PDF/JPG/PNG, maks 2MB)</span>
                            @endif
                        </label>
                        <input wire:model="attachment" type="file" accept=".pdf,.jpg,.jpeg,.png"
                            class="file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-primary-soft file:text-primary">
                        <div wire:loading wire:target="attachment" class="text-xs text-primary">Mengunggah berkas…</div>
                        @error('attachment') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" wire:click="$set('step', 'index')" class="btn-secondary btn-sm">Batal</button>
                        <button type="submit" class="btn-primary btn-sm">Kirim Permohonan Cuti</button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Detailed Action Modal popup --}}
        @if($showActionModal && $selectedLeave)
            <div class="fixed inset-0 z-50 flex items-center justify-center p-4 modal-overlay" wire:click.self="$set('showActionModal', false)">
                <div class="modal-glass w-full max-w-lg p-6 rounded-2xl shadow-xl border border-border">
                    @php
                        $isManagerStage = $actionStage === 'manager';
                        $titleTxt = $isManagerStage ? 'Persetujuan Cuti — Tingkat Manajer' : 'Persetujuan Cuti — Finalisasi HR';
                    @endphp

                    <div class="flex items-center justify-between border-b border-border pb-4 mb-4">
                        <h3 class="text-base font-bold text-fg flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-md bg-primary/10 text-primary">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </span>
                            {{ $titleTxt }}
                        </h3>
                        <button wire:click="$set('showActionModal', false)" class="text-fg-subtle hover:text-fg transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>

                    {{-- Leave Request Details --}}
                    <div class="space-y-4 text-sm">
                        <div class="flex items-center gap-3 bg-surface-muted p-3.5 rounded-xl border border-border">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary-soft text-primary font-bold text-sm">
                                {{ strtoupper(substr($selectedLeave->user->name ?? 'K', 0, 2)) }}
                            </div>
                            <div>
                                <span class="block font-bold text-fg leading-none">{{ $selectedLeave->user->name ?? 'N/A' }}</span>
                                <span class="block text-xs text-fg-muted mt-1">ID Karyawan: {{ $selectedLeave->user->employee_id ?? 'N/A' }} &middot; Cabang: {{ $selectedLeave->user->branch->name ?? 'HQ' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-fg-subtle">Kategori Cuti</span>
                                <div class="mt-1 font-semibold text-fg">{{ ucfirst($selectedLeave->leave_type) }}</div>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-fg-subtle">Durasi</span>
                                <div class="mt-1 font-bold text-primary">{{ $selectedLeave->total_days }} Hari</div>
                            </div>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-fg-subtle">Rentang Tanggal</span>
                            <div class="mt-1 font-semibold text-fg">
                                {{ \Carbon\Carbon::parse($selectedLeave->start_date)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($selectedLeave->end_date)->translatedFormat('d F Y') }}
                            </div>
                        </div>

                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-fg-subtle">Alasan Pengajuan</span>
                            <div class="mt-1 p-3 rounded-lg bg-surface border border-border text-fg-muted italic leading-relaxed">
                                "{{ $selectedLeave->reason }}"
                            </div>
                        </div>

                        @if(!$isManagerStage && $selectedLeave->manager)
                            <div class="p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/15 text-xs text-emerald-700">
                                <span class="font-bold">Disetujui Manajer:</span> {{ $selectedLeave->manager->name }} &middot; {{ \Carbon\Carbon::parse($selectedLeave->manager_approved_at)->translatedFormat('d M Y, H:i') }}
                                @if($selectedLeave->manager_notes)
                                    <div class="mt-1 text-emerald-800/90 font-medium">"{{ $selectedLeave->manager_notes }}"</div>
                                @endif
                            </div>
                        @endif

                        @if($selectedLeave->attachment_path)
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-fg-subtle">Dokumen Pendukung</span>
                                <div class="mt-1">
                                    <a href="{{ asset('storage/' . $selectedLeave->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                        Lihat Surat Keterangan / Lampiran
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="border-t border-border pt-4">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-fg-subtle mb-1.5">Catatan Persetujuan / Penolakan</label>
                            <textarea wire:model="actionNotes" rows="2.5" class="w-full" placeholder="Tambahkan catatan jika diperlukan (wajib diisi jika menolak)…"></textarea>
                            @error('actionNotes') <span class="text-xs text-danger mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 border-t border-border pt-4">
                        <button type="button" wire:click="submitAction('reject')" class="btn-danger btn-sm px-4">
                            Tolak
                        </button>
                        <button type="button" wire:click="submitAction('approve')" class="btn-success btn-sm px-4">
                            {{ $isManagerStage ? 'Setujui (Manajer)' : 'Finalisasi (HR)' }}
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
