<div class="py-8 min-h-screen">
    <div class="px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="heading-1">Pengajuan Izin &amp; Ketidakhadiran</h1>
                <p class="mt-1 label-sm">Pengajuan ketidakhadiran kerja penuh atau dispensasi waktu (datang terlambat, pulang awal, setengah hari) dengan alur persetujuan ganda.</p>
            </div>
            <div>
                @if($step === 'index')
                    <button wire:click="$set('step', 'create')" type="button" class="btn-primary btn-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        Buat Pengajuan Baru
                    </button>
                @else
                    <button wire:click="$set('step', 'index')" type="button" class="btn-secondary btn-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Kembali ke Riwayat
                    </button>
                @endif
            </div>
        </div>

        {{-- Flash messages --}}
        @if (session()->has('success'))
            <div class="mb-6 flex items-center gap-2 rounded-xl border border-success/30 bg-success-soft p-4 text-sm font-medium text-success">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="mb-6 flex items-center gap-2 rounded-xl border border-danger/30 bg-danger-soft p-4 text-sm font-medium text-danger">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- FORM PENGAJUAN IZIN --}}
        {{-- ============================================ --}}
        @if($step === 'create')
            <div class="card p-6 sm:p-8 max-w-3xl mx-auto">
                <h3 class="heading-3 mb-6 flex items-center gap-2">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Formulir Pengajuan Izin / Ketidakhadiran
                </h3>

                <form wire:submit.prevent="submitRequest" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="label">Kategori Izin</label>
                            <select wire:model.live="type" required class="w-full cursor-pointer h-10 text-xs">
                                <option value="ijin_tidak_masuk">Tidak Masuk (Penuh)</option>
                                <option value="ijin_datang_terlambat">Izin Datang Terlambat</option>
                                <option value="ijin_pulang_awal">Izin Pulang Awal</option>
                                <option value="ijin_setengah_hari">Izin Setengah Hari (1/2 Hari)</option>
                            </select>
                            @error('type') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                            <p class="label-xs text-fg-subtle">
                                @if($type === 'ijin_tidak_masuk')
                                    Mencatat ketidakhadiran kerja penuh hari.
                                @elseif($type === 'ijin_datang_terlambat')
                                    Izin masuk terlambat kerja (maksimal durasi sesuai ketentuan).
                                @elseif($type === 'ijin_pulang_awal')
                                    Izin pulang sebelum jam kerja berakhir (maksimal durasi sesuai ketentuan).
                                @elseif($type === 'ijin_setengah_hari')
                                    Izin meninggalkan pekerjaan selama setengah hari.
                                @endif
                            </p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="label">Tanggal Pengajuan</label>
                            <input wire:model="date" type="date" required class="cursor-pointer">
                            @error('date') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @if($type !== 'ijin_tidak_masuk')
                        <div class="grid grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="label">Waktu Mulai</label>
                                <input wire:model="start_time" type="time" required class="cursor-pointer">
                                @error('start_time') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="label">Waktu Selesai</label>
                                <input wire:model="end_time" type="time" required class="cursor-pointer">
                                @error('end_time') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="space-y-1.5">
                        <label class="label">Alasan Pengajuan</label>
                        <textarea wire:model="reason" rows="4" required placeholder="Tuliskan detail alasan pengajuan Anda di sini secara jelas…" class="resize-none"></textarea>
                        @error('reason') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="label">Dokumen Pendukung / Lampiran <span class="text-fg-subtle font-normal">(Opsional)</span></label>
                        <label class="flex flex-col items-center justify-center w-full h-32 rounded-xl border-2 border-dashed border-border bg-surface-muted cursor-pointer transition-colors hover:border-primary">
                            <div class="flex flex-col items-center justify-center py-5 text-center">
                                <svg class="w-7 h-7 mb-2 text-fg-subtle" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <p class="text-xs text-fg-muted"><span class="font-medium text-primary">Klik untuk upload</span> atau seret berkas</p>
                                <p class="text-xs text-fg-subtle mt-0.5">PDF, JPG, PNG (Maks. 2MB)</p>
                            </div>
                            <input wire:model="attachment" type="file" class="hidden">
                        </label>
                        @if ($attachment)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-primary/30 bg-primary-soft p-3">
                                <span class="text-xs font-medium text-primary truncate">{{ $attachment->getClientOriginalName() }}</span>
                                <button type="button" wire:click="$set('attachment', null)" class="text-xs font-medium text-danger hover:underline">Hapus</button>
                            </div>
                        @endif
                        @error('attachment') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="attachment" class="text-xs text-primary">Mengunggah file…</div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-border">
                        <button wire:click="$set('step', 'index')" type="button" class="btn-secondary btn-sm">Batal</button>
                        <button type="submit" class="btn-primary btn-sm">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>

        {{-- ============================================ --}}
        {{-- DAFTAR & TINJAUAN UTAMA --}}
        {{-- ============================================ --}}
        @else
            {{-- Stats KPI --}}
            @php
                $totalReq = auth()->user()->permissionRequests()->count();
                $approvedReq = auth()->user()->permissionRequests()->where('status', 'approved')->count();
                $pendingReq = auth()->user()->permissionRequests()->where('status', 'pending')->count();
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                @foreach ([
                    ['Total Pengajuan Anda', $totalReq, 'bg-info-soft text-info', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                    ['Disetujui Resmi', $approvedReq, 'bg-success-soft text-success', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Menunggu Persetujuan', $pendingReq, 'bg-warning-soft text-warning', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ] as [$title, $value, $iconClass, $iconPath])
                    <div class="card p-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="label-xs uppercase tracking-wide">{{ $title }}</span>
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg {{ $iconClass }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" /></svg>
                            </span>
                        </div>
                        <div class="heading-value">{{ $value }} <span class="label-sm font-normal">Sesi</span></div>
                    </div>
                @endforeach
            </div>

            {{-- Alur persetujuan --}}
            <div class="card p-6 sm:p-7 mb-8">
                <h3 class="heading-3 mb-5">Alur Persetujuan Ganda</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach ([
                        ['01', 'Karyawan Mengajukan', 'Mengisi pengajuan izin atau ketidakhadiran disertai alasan & berkas pendukung.'],
                        ['02', 'Kepala Divisi (Kadiv)', 'Melakukan review kesesuaian operasional & beban kerja di divisi terkait.'],
                        ['03', 'HR Manager (HRD)', 'Persetujuan akhir & sinkronisasi data dispensasi kehadiran sistem.'],
                        ['04', 'Cetak Surat Resmi', 'Sistem menerbitkan surat izin resmi bertanda tangan digital ber-barkod pengaman.'],
                    ] as [$no, $stepTitle, $stepDesc])
                        <div class="rounded-xl border border-border bg-surface-muted p-5">
                            <div class="text-2xl font-semibold tabular-nums text-primary leading-none mb-3">{{ $no }}</div>
                            <h4 class="label-md">{{ $stepTitle }}</h4>
                            <p class="label-xs mt-1.5 leading-relaxed normal-case tracking-normal">{{ $stepDesc }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tab selector (admins/managers) --}}
            @if($isAdmin)
                <div class="flex gap-1 p-1 rounded-xl bg-surface-muted border border-border max-w-md mb-6">
                    <button wire:click="$set('activeTab', 'my')" type="button"
                        class="flex-1 inline-flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'my' ? 'tab-active' : 'text-fg-muted hover:text-fg' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat Saya
                    </button>
                    <button wire:click="$set('activeTab', 'review')" type="button"
                        class="relative flex-1 inline-flex items-center justify-center gap-2 py-2 text-sm font-medium rounded-lg transition-colors {{ $activeTab === 'review' ? 'tab-active' : 'text-fg-muted hover:text-fg' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Tinjau Karyawan
                        @if($rawPendingCount > 0)
                            <span class="inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-danger px-1.5 text-xs font-semibold text-white">{{ $rawPendingCount }}</span>
                        @endif
                    </button>
                </div>
            @endif

            {{-- ============================================ --}}
            {{-- Tab: Tinjau Pengajuan Karyawan --}}
            {{-- ============================================ --}}
            @if($isAdmin && $activeTab === 'review')
                <div class="card p-6 sm:p-8">
                    <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4 mb-6 pb-6 border-b border-border">
                        <div>
                            <h3 class="heading-3">Tinjau Pengajuan Izin &amp; Ketidakhadiran</h3>
                            <p class="label-sm mt-1">Daftar permohonan izin/tidak masuk aktif yang memerlukan verifikasi Kepala Divisi dan HR Manager.</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                            <div class="relative flex-1 min-w-[200px] xl:max-w-xs">
                                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-fg-subtle" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                <input wire:model.live.debounce.350ms="reviewSearch" type="text" placeholder="Cari nama / ID karyawan…" class="pl-10">
                            </div>
                            <select wire:model.live="reviewStatusFilter" class="w-auto cursor-pointer">
                                <option value="pending">Pending Review</option>
                                <option value="approved">Disetujui</option>
                                <option value="rejected">Ditolak</option>
                                <option value="all">Semua Riwayat</option>
                            </select>
                            @if($reviewSearch || $reviewTypeFilter !== 'all' || $reviewStatusFilter !== 'pending')
                                <button wire:click="clearReviewFilters" type="button" class="btn-danger-outline btn-sm">Reset</button>
                            @endif
                        </div>
                    </div>

                    @if($reviewRequests->isEmpty())
                        <div class="py-16 flex flex-col items-center justify-center text-center rounded-xl bg-surface-muted">
                            <div class="w-12 h-12 rounded-xl bg-success-soft text-success flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <h4 class="heading-3">Antrean Bersih</h4>
                            <p class="label-sm mt-1 max-w-xs">Tidak ditemukan pengajuan izin kerja yang cocok dengan kriteria filter Anda saat ini.</p>
                        </div>
                    @else
                        {{-- Desktop table --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-border label-xs uppercase tracking-wide">
                                        <th class="px-5 py-3.5 w-[240px]">Karyawan</th>
                                        <th class="px-5 py-3.5 w-[140px]">Kategori</th>
                                        <th class="px-5 py-3.5 w-[160px]">Tanggal</th>
                                        <th class="px-5 py-3.5">Alasan & Lampiran</th>
                                        <th class="px-5 py-3.5 text-center w-[150px]">Persetujuan Ganda</th>
                                        <th class="px-5 py-3.5 text-right w-[200px]">Aksi Review</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border">
                                    @foreach($reviewRequests as $req)
                                        <tr class="hover:bg-surface-muted transition-colors align-middle">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-lg bg-primary text-primary-fg flex items-center justify-center font-semibold text-sm uppercase">{{ strtoupper(substr($req->user->name, 0, 1)) }}</div>
                                                    <div>
                                                        <div class="label-md">{{ $req->user->name }}</div>
                                                        <div class="text-xs font-mono text-fg-subtle mt-0.5">#{{ $req->user->employee_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                @switch($req->type)
                                                    @case('ijin_datang_terlambat') <span class="badge-rect-warning">Terlambat</span> @break
                                                    @case('ijin_pulang_awal') <span class="badge-rect-danger">Pulang Awal</span> @break
                                                    @case('ijin_setengah_hari') <span class="badge-rect-info">Setengah Hari</span> @break
                                                    @default <span class="badge-rect-neutral">Tidak Masuk</span>
                                                @endswitch
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="label-md">{{ $req->date->translatedFormat('d M Y') }}</div>
                                                @if($req->type !== 'ijin_tidak_masuk')
                                                    <div class="label-xs mt-1 normal-case tracking-normal">{{ substr($req->start_time, 0, 5) }} – {{ substr($req->end_time, 0, 5) }}</div>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="max-w-[260px] truncate label-sm" title="{{ $req->reason }}">{{ $req->reason }}</div>
                                                @if($req->attachment_path)
                                                    <a href="{{ asset('storage/' . $req->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:underline mt-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                                        Lihat Lampiran
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex flex-col gap-1 max-w-[130px] mx-auto">
                                                    @php
                                                        $stageBadge = fn($s) => match($s) {
                                                            'approved' => ['text-success', '✓ ACC'],
                                                            'rejected' => ['text-danger', '✗ REJ'],
                                                            default => ['text-warning', '⏳ PND'],
                                                        };
                                                        $kadiv = $stageBadge($req->status_dept_head);
                                                        $hrd = $stageBadge($req->status_hr);
                                                    @endphp
                                                    <div class="flex items-center justify-between gap-2 px-2 py-1 rounded-md bg-surface-muted border border-border">
                                                        <span class="text-xs font-medium text-fg-muted uppercase">Kadiv</span>
                                                        <span class="text-xs font-semibold {{ $kadiv[0] }}">{{ $kadiv[1] }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between gap-2 px-2 py-1 rounded-md bg-surface-muted border border-border">
                                                        <span class="text-xs font-medium text-fg-muted uppercase">HRD</span>
                                                        <span class="text-xs font-semibold {{ $hrd[0] }}">{{ $hrd[1] }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-5 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <button wire:click="viewDetail({{ $req->id }})" type="button" class="btn-secondary btn-xs">Detail</button>
                                                    @if($req->user_id === auth()->id())
                                                        <span class="badge-neutral cursor-not-allowed" title="Anda tidak dapat memverifikasi pengajuan Anda sendiri">🔒 Sendiri</span>
                                                    @elseif($req->status === 'pending')
                                                        @if($isManager && $req->status_dept_head === 'pending')
                                                            <button wire:click="approveDeptHead({{ $req->id }})" type="button" class="btn-success btn-xs">ACC Kadiv</button>
                                                            <button wire:click="openApprovalModal({{ $req->id }}, 'dept_head')" type="button" class="btn-success-outline btn-xs" title="Setujui dengan Catatan">💬</button>
                                                        @endif
                                                        @if($isHr && $req->status_hr === 'pending' && $req->status_dept_head === 'approved')
                                                            <button wire:click="approveHr({{ $req->id }})" type="button" class="btn-primary btn-xs">ACC HR</button>
                                                            <button wire:click="openApprovalModal({{ $req->id }}, 'hr')" type="button" class="btn-primary-outline btn-xs" title="Setujui dengan Catatan">💬</button>
                                                        @endif
                                                        <button wire:click="openRejectionModal({{ $req->id }})" type="button" class="btn-danger-outline btn-xs">Tolak</button>
                                                    @else
                                                        <span class="label-xs">Selesai</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Mobile cards --}}
                        <div class="md:hidden space-y-3">
                            @foreach($reviewRequests as $req)
                                <div class="rounded-xl border border-border bg-surface-muted p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <div class="w-9 h-9 flex-shrink-0 rounded-lg bg-primary text-primary-fg flex items-center justify-center font-semibold text-sm uppercase">{{ strtoupper(substr($req->user->name, 0, 1)) }}</div>
                                            <div class="min-w-0">
                                                <div class="label-md truncate">{{ $req->user->name }}</div>
                                                <div class="text-xs font-mono text-fg-subtle mt-0.5">#{{ $req->user->employee_id }}</div>
                                            </div>
                                        </div>
                                        @switch($req->type)
                                            @case('ijin_datang_terlambat') <span class="badge-rect-warning flex-shrink-0">Terlambat</span> @break
                                            @case('ijin_pulang_awal') <span class="badge-rect-danger flex-shrink-0">Pulang Awal</span> @break
                                            @case('ijin_setengah_hari') <span class="badge-rect-info flex-shrink-0">Setengah Hari</span> @break
                                            @default <span class="badge-rect-neutral flex-shrink-0">Tidak Masuk</span>
                                        @endswitch
                                    </div>

                                    <div class="mt-3 flex items-center gap-2">
                                        <span class="label-md">{{ $req->date->translatedFormat('d M Y') }}</span>
                                        @if($req->type !== 'ijin_tidak_masuk')
                                            <span class="label-xs normal-case tracking-normal">{{ substr($req->start_time, 0, 5) }} – {{ substr($req->end_time, 0, 5) }}</span>
                                        @endif
                                    </div>

                                    @if($req->reason)
                                        <p class="mt-2 label-sm line-clamp-2">{{ $req->reason }}</p>
                                    @endif
                                    @if($req->attachment_path)
                                        <a href="{{ asset('storage/' . $req->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:underline mt-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                                            Lihat Lampiran
                                        </a>
                                    @endif

                                    <div class="mt-3 flex items-center gap-2">
                                        @php $kadiv = $stageBadge($req->status_dept_head); $hrd = $stageBadge($req->status_hr); @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-surface border border-border text-xs">
                                            <span class="font-medium text-fg-muted uppercase">Kadiv</span><span class="font-semibold {{ $kadiv[0] }}">{{ $kadiv[1] }}</span>
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-surface border border-border text-xs">
                                            <span class="font-medium text-fg-muted uppercase">HRD</span><span class="font-semibold {{ $hrd[0] }}">{{ $hrd[1] }}</span>
                                        </span>
                                    </div>

                                    <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-border pt-3">
                                        <button wire:click="viewDetail({{ $req->id }})" type="button" class="btn-secondary btn-xs">Detail</button>
                                        @if($req->user_id === auth()->id())
                                            <span class="badge-neutral cursor-not-allowed">🔒 Pengajuan Sendiri</span>
                                        @elseif($req->status === 'pending')
                                            @if($isManager && $req->status_dept_head === 'pending')
                                                <button wire:click="approveDeptHead({{ $req->id }})" type="button" class="btn-success btn-xs">ACC Kadiv</button>
                                            @endif
                                            @if($isHr && $req->status_hr === 'pending' && $req->status_dept_head === 'approved')
                                                <button wire:click="approveHr({{ $req->id }})" type="button" class="btn-primary btn-xs">ACC HR</button>
                                            @endif
                                            <button wire:click="openRejectionModal({{ $req->id }})" type="button" class="btn-danger-outline btn-xs">Tolak</button>
                                        @else
                                            <span class="label-xs">Selesai</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">{{ $reviewRequests->links() }}</div>
                    @endif
                </div>

            {{-- ============================================ --}}
            {{-- Tab: Riwayat Pengajuan Izin Saya --}}
            {{-- ============================================ --}}
            @else
                <div class="card p-6 sm:p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-border">
                        <div>
                            <h3 class="heading-3">Riwayat Pengajuan Izin Saya</h3>
                            <p class="label-sm mt-1">Lacak riwayat dispensasi kerja dan status persetujuan berjenjang Anda.</p>
                        </div>
                        <div class="flex items-center gap-1 p-1 rounded-xl bg-surface-muted border border-border">
                            @foreach (['all' => 'Semua', 'approved' => 'Disetujui', 'pending' => 'Pending'] as $val => $lbl)
                                <button wire:click="$set('statusFilter', '{{ $val }}')" type="button" class="px-4 py-1.5 text-sm font-medium rounded-lg transition-colors {{ $statusFilter === $val ? 'tab-active' : 'text-fg-muted hover:text-fg' }}">{{ $lbl }}</button>
                            @endforeach
                        </div>
                    </div>

                    @if($myPermissions->isEmpty())
                        <div class="py-16 flex flex-col items-center justify-center text-center rounded-xl bg-surface-muted">
                            <div class="w-12 h-12 rounded-xl bg-info-soft text-info flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" /></svg>
                            </div>
                            <h4 class="heading-3">Belum Ada Pengajuan</h4>
                            <p class="label-sm mt-1 max-w-xs">Anda tidak memiliki pengajuan izin dengan status terpilih untuk saat ini.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($myPermissions as $perm)
                                <div class="rounded-xl border border-border bg-surface-muted p-5 flex flex-col">
                                    <div class="space-y-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="label-xs uppercase tracking-wide">
                                                @switch($perm->type)
                                                    @case('ijin_datang_terlambat') Izin Telat @break
                                                    @case('ijin_pulang_awal') Izin Pulang Awal @break
                                                    @case('ijin_setengah_hari') Izin 1/2 Hari @break
                                                    @default Izin Tidak Masuk
                                                @endswitch
                                            </span>
                                            @switch($perm->status)
                                                @case('approved') <span class="badge-rect-success">Disetujui</span> @break
                                                @case('rejected') <span class="badge-rect-danger">Ditolak</span> @break
                                                @default <span class="badge-rect-warning">Diproses</span>
                                            @endswitch
                                        </div>

                                        <div class="rounded-xl border border-border bg-surface p-3.5 space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-fg-muted">Tanggal Izin</span>
                                                <span class="label-md">{{ $perm->date->translatedFormat('d M Y') }}</span>
                                            </div>
                                            @if($perm->type !== 'ijin_tidak_masuk')
                                                <div class="flex justify-between">
                                                    <span class="text-fg-muted">Waktu Dispensasi</span>
                                                    <span class="font-medium text-primary">{{ substr($perm->start_time, 0, 5) }}–{{ substr($perm->end_time, 0, 5) }}</span>
                                                </div>
                                            @endif
                                            <div class="pt-2 border-t border-border">
                                                <span class="label-xs uppercase tracking-wide">Alasan Pengajuan</span>
                                                <p class="mt-1 label-sm line-clamp-2" title="{{ $perm->reason }}">{{ $perm->reason }}</p>
                                            </div>
                                            @if($perm->approval_notes)
                                                <div class="pt-2 border-t border-border">
                                                    <span class="label-xs uppercase tracking-wide">Catatan Peninjau</span>
                                                    <p class="mt-1 text-sm font-medium text-danger italic">{{ $perm->approval_notes }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Progress tracker --}}
                                        <div class="rounded-xl border border-border bg-surface p-3 space-y-2">
                                            <div class="label-xs uppercase tracking-wide">Tahap Verifikasi</div>
                                            <div class="relative flex items-start justify-between px-2 pt-1">
                                                <div class="absolute top-[10px] left-[26px] right-[26px] h-[2px] bg-border rounded-full overflow-hidden">
                                                    <div class="absolute inset-y-0 left-0 bg-primary transition-all" style="width: {{ $perm->status === 'approved' ? '100%' : ($perm->status_dept_head === 'approved' ? '50%' : '0%') }}"></div>
                                                </div>
                                                @php
                                                    $node = function ($state, $label) {
                                                        return match ($state) {
                                                            'approved' => ['bg-primary text-primary-fg', '✓', 'text-primary'],
                                                            'rejected' => ['bg-danger text-white', '✗', 'text-danger'],
                                                            default => ['bg-surface border-2 border-border-strong text-fg-subtle', '·', 'text-fg-subtle'],
                                                        };
                                                    };
                                                @endphp
                                                <div class="relative z-10 flex flex-col items-center">
                                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-semibold bg-primary text-primary-fg">✓</div>
                                                    <span class="text-xs font-medium mt-1 uppercase text-primary">Ajukan</span>
                                                </div>
                                                @php $k = $node($perm->status_dept_head, 'Kadiv'); @endphp
                                                <div class="relative z-10 flex flex-col items-center">
                                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-semibold {{ $k[0] }}">{{ $k[1] }}</div>
                                                    <span class="text-xs font-medium mt-1 uppercase {{ $k[2] }}">Kadiv</span>
                                                </div>
                                                @php $h = $node($perm->status_hr, 'HRD'); @endphp
                                                <div class="relative z-10 flex flex-col items-center">
                                                    <div class="w-5 h-5 rounded-full flex items-center justify-center text-xs font-semibold {{ $h[0] }}">{{ $h[1] }}</div>
                                                    <span class="text-xs font-medium mt-1 uppercase {{ $h[2] }}">HRD</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pt-4 mt-4 border-t border-border flex items-center gap-2">
                                        <button wire:click="viewDetail({{ $perm->id }})" type="button" class="btn-secondary btn-xs flex-1">Detail & Lacak</button>
                                        @if($perm->status === 'approved')
                                            <a href="{{ route('letters.permission', $perm->id) }}" target="_blank" class="btn-primary btn-xs flex-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                                Cetak Surat
                                            </a>
                                        @else
                                            <span class="btn-disabled btn-xs flex-1">Menunggu</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">{{ $myPermissions->links() }}</div>
                    @endif
                </div>
            @endif
        @endif

    </div>

    {{-- ============================================================ --}}
    {{-- MODALS --}}
    {{-- ============================================================ --}}

    {{-- Modal 1: Detail & Lacak --}}
    <div x-data="{ open: @entangle('showDetailModal') }" x-show="open" x-cloak
        class="fixed inset-0 z-[120] overflow-y-auto flex items-center justify-center p-4 modal-overlay" x-transition>
        <div @click.away="open = false" class="modal-glass w-full max-w-3xl p-6 sm:p-8 flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between mb-6 border-b border-border pb-4">
                <h3 class="heading-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Informasi Detail Pengajuan Izin
                </h3>
                <button @click="open = false" type="button" class="btn-ghost btn-xs text-lg leading-none">&times;</button>
            </div>

            @if($selectedRequestForDetail)
                <div class="space-y-6 overflow-y-auto pr-1 flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        {{-- Left: info --}}
                        <div class="md:col-span-7 space-y-4">
                            <div class="rounded-xl border border-border bg-surface-muted p-4 space-y-3">
                                <h4 class="label-xs uppercase tracking-wide border-b border-border pb-2">Profil Karyawan</h4>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-primary text-primary-fg flex items-center justify-center font-semibold text-sm uppercase">{{ strtoupper(substr($selectedRequestForDetail->user->name, 0, 1)) }}</div>
                                    <div>
                                        <div class="label-md">{{ $selectedRequestForDetail->user->name }}</div>
                                        <div class="text-xs font-mono text-fg-subtle mt-0.5">ID: {{ $selectedRequestForDetail->user->employee_id }} · Cabang: {{ $selectedRequestForDetail->user->branch->name ?? 'Default' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="rounded-xl border border-border bg-surface-muted p-4 space-y-3">
                                <h4 class="label-xs uppercase tracking-wide border-b border-border pb-2">Rincian Dispensasi</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="label-xs uppercase tracking-wide">Kategori Izin</span>
                                        <div class="label-md mt-0.5">
                                            @switch($selectedRequestForDetail->type)
                                                @case('ijin_datang_terlambat') Izin Datang Terlambat @break
                                                @case('ijin_pulang_awal') Izin Pulang Awal @break
                                                @case('ijin_setengah_hari') Izin Setengah Hari @break
                                                @default Izin Tidak Masuk
                                            @endswitch
                                        </div>
                                    </div>
                                    <div>
                                        <span class="label-xs uppercase tracking-wide">Tanggal Izin</span>
                                        <div class="label-md mt-0.5">{{ $selectedRequestForDetail->date->translatedFormat('d F Y') }}</div>
                                    </div>
                                    @if($selectedRequestForDetail->type !== 'ijin_tidak_masuk')
                                        <div>
                                            <span class="label-xs uppercase tracking-wide">Jam Mulai</span>
                                            <div class="font-medium text-primary mt-0.5">{{ substr($selectedRequestForDetail->start_time, 0, 5) }}</div>
                                        </div>
                                        <div>
                                            <span class="label-xs uppercase tracking-wide">Jam Selesai</span>
                                            <div class="font-medium text-primary mt-0.5">{{ substr($selectedRequestForDetail->end_time, 0, 5) }}</div>
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-3 border-t border-border">
                                    <span class="label-xs uppercase tracking-wide">Alasan Pengajuan</span>
                                    <p class="mt-1 label-sm leading-relaxed rounded-lg border border-border bg-surface p-3">{{ $selectedRequestForDetail->reason }}</p>
                                </div>

                                @if($selectedRequestForDetail->attachment_path)
                                    @php
                                        $ext = pathinfo($selectedRequestForDetail->attachment_path, PATHINFO_EXTENSION);
                                        $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                    @endphp
                                    <div class="pt-3 border-t border-border space-y-2">
                                        <span class="label-xs uppercase tracking-wide">Dokumen Lampiran</span>
                                        @if($isImg)
                                            <div class="rounded-lg overflow-hidden border border-border bg-surface flex items-center justify-center max-h-40">
                                                <img src="{{ asset('storage/' . $selectedRequestForDetail->attachment_path) }}" class="max-h-40 object-contain w-full" alt="Lampiran">
                                            </div>
                                        @endif
                                        <a href="{{ asset('storage/' . $selectedRequestForDetail->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:underline">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                            Buka Dokumen di Tab Baru
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right: approval timeline --}}
                        <div class="md:col-span-5 space-y-4">
                            <div class="rounded-xl border border-border bg-surface-muted p-4 space-y-4">
                                <h4 class="label-xs uppercase tracking-wide border-b border-border pb-2">Jejak Persetujuan</h4>
                                <div class="relative pl-6 space-y-6">
                                    <div class="absolute left-2 top-2 bottom-2 w-0.5 bg-border"></div>

                                    <div class="relative flex items-start gap-3">
                                        <div class="absolute -left-6 mt-1 w-4 h-4 rounded-full bg-primary ring-4 ring-surface-muted"></div>
                                        <div>
                                            <span class="label-md block">Pengajuan Terkirim</span>
                                            <span class="text-xs font-mono text-fg-subtle">{{ $selectedRequestForDetail->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                        </div>
                                    </div>

                                    {{-- Kadiv --}}
                                    <div class="relative flex items-start gap-3">
                                        @if($selectedRequestForDetail->status_dept_head === 'approved')
                                            <div class="absolute -left-6 mt-1 w-4 h-4 rounded-full bg-success ring-4 ring-surface-muted"></div>
                                            <div>
                                                <span class="font-medium text-success block">Disetujui Kepala Divisi</span>
                                                <span class="text-xs font-mono text-fg-subtle block mt-0.5">Oleh: {{ $selectedRequestForDetail->deptHead->name ?? 'N/A' }}</span>
                                                @if($selectedRequestForDetail->dept_head_approved_at)
                                                    <span class="text-xs font-mono text-fg-subtle block">{{ $selectedRequestForDetail->dept_head_approved_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                                @endif
                                            </div>
                                        @elseif($selectedRequestForDetail->status_dept_head === 'rejected')
                                            <div class="absolute -left-6 mt-1 w-4 h-4 rounded-full bg-danger ring-4 ring-surface-muted"></div>
                                            <div>
                                                <span class="font-medium text-danger block">Ditolak Kepala Divisi</span>
                                                <span class="text-xs font-mono text-fg-subtle block mt-0.5">Oleh: {{ $selectedRequestForDetail->deptHead->name ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <div class="absolute -left-6 mt-1 w-4 h-4 rounded-full bg-surface border-2 border-border-strong"></div>
                                            <div>
                                                <span class="font-medium text-fg-subtle block">Menunggu Kepala Divisi</span>
                                                <span class="text-xs text-fg-subtle block mt-0.5">Review operasional / Kadiv</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- HR --}}
                                    <div class="relative flex items-start gap-3">
                                        @if($selectedRequestForDetail->status_hr === 'approved')
                                            <div class="absolute -left-6 mt-1 w-4 h-4 rounded-full bg-success ring-4 ring-surface-muted"></div>
                                            <div>
                                                <span class="font-medium text-success block">Disetujui HR Manager</span>
                                                <span class="text-xs font-mono text-fg-subtle block mt-0.5">Oleh: {{ $selectedRequestForDetail->hr->name ?? 'N/A' }}</span>
                                                @if($selectedRequestForDetail->hr_approved_at)
                                                    <span class="text-xs font-mono text-fg-subtle block">{{ $selectedRequestForDetail->hr_approved_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                                @endif
                                            </div>
                                        @elseif($selectedRequestForDetail->status_hr === 'rejected')
                                            <div class="absolute -left-6 mt-1 w-4 h-4 rounded-full bg-danger ring-4 ring-surface-muted"></div>
                                            <div>
                                                <span class="font-medium text-danger block">Ditolak HR Manager</span>
                                                <span class="text-xs font-mono text-fg-subtle block mt-0.5">Oleh: {{ $selectedRequestForDetail->hr->name ?? 'N/A' }}</span>
                                            </div>
                                        @else
                                            <div class="absolute -left-6 mt-1 w-4 h-4 rounded-full bg-surface border-2 border-border-strong"></div>
                                            <div>
                                                <span class="font-medium text-fg-subtle block">Menunggu HR Manager</span>
                                                <span class="text-xs text-fg-subtle block mt-0.5">Persetujuan akhir & database sync</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($selectedRequestForDetail->approval_notes)
                                    <div class="pt-3 border-t border-border">
                                        <span class="label-xs uppercase tracking-wide mb-1.5 block">Catatan Verifikator</span>
                                        <p class="text-sm font-medium text-warning rounded-lg border border-warning/20 bg-warning-soft p-3 leading-relaxed italic">{{ $selectedRequestForDetail->approval_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-3 pt-5 border-t border-border mt-6">
                    <div>
                        @if($selectedRequestForDetail->status === 'approved')
                            <a href="{{ route('letters.permission', $selectedRequestForDetail->id) }}" target="_blank" class="btn-primary btn-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                Cetak Surat Resmi
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if($selectedRequestForDetail->status === 'pending' && $selectedRequestForDetail->user_id !== auth()->id())
                            @if($isManager && $selectedRequestForDetail->status_dept_head === 'pending')
                                <button wire:click="approveDeptHead({{ $selectedRequestForDetail->id }})" type="button" class="btn-success btn-sm">Setujui Kadiv</button>
                            @endif
                            @if($isHr && $selectedRequestForDetail->status_hr === 'pending' && $selectedRequestForDetail->status_dept_head === 'approved')
                                <button wire:click="approveHr({{ $selectedRequestForDetail->id }})" type="button" class="btn-primary btn-sm">Setujui HRD</button>
                            @endif
                            <button wire:click="openRejectionModal({{ $selectedRequestForDetail->id }})" type="button" class="btn-danger-outline btn-sm">Tolak Izin</button>
                        @endif
                        <button @click="open = false" type="button" class="btn-secondary btn-sm">Tutup</button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal 2: Rejection --}}
    <div x-data="{ open: @entangle('showRejectionModal') }" x-show="open" x-cloak
        class="fixed inset-0 z-[130] overflow-y-auto flex items-center justify-center p-4 modal-overlay" x-transition>
        <div @click.away="open = false" class="modal-glass w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4 border-b border-border pb-3">
                <h3 class="heading-3 text-danger">Alasan Penolakan</h3>
                <button @click="open = false" type="button" class="btn-ghost btn-xs text-lg leading-none">&times;</button>
            </div>
            <form wire:submit.prevent="submitRejection" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="label">Catatan Penolakan / Alasan</label>
                    <textarea wire:model="actionNotes" rows="3" required placeholder="Contoh: Lampiran tidak terbaca / operasional divisi sedang tinggi…" class="resize-none"></textarea>
                    @error('actionNotes') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button @click="open = false" type="button" class="btn-secondary btn-sm">Batal</button>
                    <button type="submit" class="btn-danger btn-sm">Tolak Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal 3: Approval with notes --}}
    <div x-data="{ open: @entangle('showApprovalModal') }" x-show="open" x-cloak
        class="fixed inset-0 z-[130] overflow-y-auto flex items-center justify-center p-4 modal-overlay" x-transition>
        <div @click.away="open = false" class="modal-glass w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4 border-b border-border pb-3">
                <h3 class="heading-3 text-success">Setujui dengan Catatan</h3>
                <button @click="open = false" type="button" class="btn-ghost btn-xs text-lg leading-none">&times;</button>
            </div>
            <form wire:submit.prevent="submitApproval" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="label">Tulis Catatan Persetujuan <span class="text-fg-subtle font-normal">(opsional)</span></label>
                    <textarea wire:model="actionNotes" rows="3" placeholder="Contoh: Disetujui karena ada tugas luar divisi…" class="resize-none"></textarea>
                    @error('actionNotes') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button @click="open = false" type="button" class="btn-secondary btn-sm">Batal</button>
                    <button type="submit" class="btn-success btn-sm">Setujui Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

</div>
