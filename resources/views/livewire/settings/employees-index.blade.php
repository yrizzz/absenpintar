<div class="py-8 min-h-screen">
    <div class="px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="heading-1 flex items-center gap-2">
                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Kelola Karyawan
                </h1>
                <p class="mt-1 label-sm">Registrasi karyawan baru, kelola template biometrik wajah, kelola perangkat terpercaya, dan atur peran sistem.</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="$set('showRegisterModal', true)" type="button"
                    class="btn-sm btn-primary py-2 shadow-md hover:scale-[1.02] active:scale-[0.98] transition-transform">
                    <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg> Tambah Karyawan
                </button>
                <span class="badge-success shadow-sm px-3 py-1 text-xs">
                    <span class="w-2 h-2 bg-success rounded-full mr-1.5 animate-pulse"></span>
                    Edge Vision Aktif
                </span>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 rounded-xl border border-success/30 bg-success-soft p-4 text-xs font-semibold text-success flex items-center gap-2">
                <svg class="w-4 h-4 text-success flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 rounded-xl border border-danger/30 bg-danger-soft p-4 text-xs font-semibold text-danger flex items-center gap-2">
                <svg class="w-4 h-4 text-danger flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Enrollment Statistics Widget -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="card card-hover p-5 relative overflow-hidden border-l-4 border-primary bg-surface flex items-center justify-between shadow-sm">
                <div>
                    <div class="label-xs text-fg-muted font-bold uppercase tracking-wider">Total Tenaga Kerja</div>
                    <div class="heading-value mt-2 font-bold text-fg">{{ $stats['total'] }}</div>
                    <div class="label-xs text-fg-subtle mt-1">Akun personel terdaftar</div>
                </div>
                <div class="p-3 bg-primary-soft rounded-xl text-primary">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            <div class="card card-hover p-5 relative overflow-hidden border-l-4 border-success bg-surface flex items-center justify-between shadow-sm">
                <div>
                    <div class="label-xs text-fg-muted font-bold uppercase tracking-wider">Identitas Terverifikasi</div>
                    <div class="heading-value mt-2 font-bold text-success">{{ $stats['enrolled'] }}</div>
                    <div class="label-xs text-fg-subtle mt-1">Verifikasi wajah aktif</div>
                </div>
                <div class="p-3 bg-success-soft rounded-xl text-success">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>

            <div class="card card-hover p-5 relative overflow-hidden border-l-4 border-warning bg-surface flex items-center justify-between shadow-sm">
                <div>
                    <div class="label-xs text-fg-muted font-bold uppercase tracking-wider">Belum Registrasi Wajah</div>
                    <div class="heading-value mt-2 font-bold text-warning">{{ $stats['pending'] }}</div>
                    <div class="label-xs text-fg-subtle mt-1">Dibatasi dari absensi mandiri</div>
                </div>
                <div class="p-3 bg-warning-soft rounded-xl text-warning">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <div class="card card-hover p-5 relative overflow-hidden border-l-4 border-primary bg-surface flex items-center justify-between shadow-sm">
                <div>
                    <div class="label-xs text-fg-muted font-bold uppercase tracking-wider">Tingkat Kepatuhan Kunci</div>
                    <div class="heading-value mt-2 font-bold text-primary">{{ $stats['rate'] }}%</div>
                    <div class="w-24 bg-surface-muted h-2 rounded-full mt-2.5 overflow-hidden">
                        <div class="bg-primary h-full rounded-full" style="width: {{ $stats['rate'] }}%"></div>
                    </div>
                </div>
                <div class="p-3 bg-primary-soft rounded-xl text-primary">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 01-2 2h-2a2 2 0 01-2-2zm9-1a1 1 0 100-2 1 1 0 000 2zm-9 0a1 1 0 100-2 1 1 0 000 2zm3-3a1 1 0 100-2 1 1 0 000 2zm3-3a1 1 0 100-2 1 1 0 000 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="card p-6 sm:p-8 shadow-sm">
            <!-- Search, Filter & controls -->
            <div class="bg-surface-muted border border-border rounded-xl p-4 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 shadow-sm">
                <div>
                    <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Cari Nama / ID Karyawan</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-fg-subtle">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="Masukkan nama, email, atau ID..."
                            class="w-full text-xs pl-9 pr-8 rounded-lg border border-border bg-surface text-fg py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        @if ($search)
                            <button @click="$wire.set('search', '')"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-fg-muted hover:text-fg text-xs font-bold">×</button>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Filter Status Wajah</label>
                    <select wire:model.live="statusFilter"
                        class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="all">Semua Karyawan</option>
                        <option value="registered">Wajah Terverifikasi (Aktif)</option>
                        <option value="pending">Belum Registrasi Wajah</option>
                    </select>
                </div>

                <div>
                    <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Penempatan Kantor Cabang</label>
                    <select wire:model.live="branchFilter"
                        class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        <option value="all">Semua Cabang</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Main Ledger Table (Desktop) -->
            <div class="hidden md:block border border-border rounded-xl overflow-hidden bg-surface shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-border bg-surface-muted label-xs font-bold text-fg-muted">
                                <x-sort-th field="name" :sort="$userSortField" :dir="$userSortDir" method="sortUsers" class="whitespace-nowrap px-5 py-4" style="width: 40%;">Karyawan</x-sort-th>
                                <x-sort-th field="branch" :sort="$userSortField" :dir="$userSortDir" method="sortUsers" class="whitespace-nowrap px-5 py-4" style="width: 25%;">Cabang & Mode Kerja</x-sort-th>
                                <x-sort-th field="is_registered" :sort="$userSortField" :dir="$userSortDir" method="sortUsers" align="center" class="whitespace-nowrap px-5 py-4" style="width: 20%;">Status Registrasi Wajah</x-sort-th>
                                <th class="px-5 py-4 text-right whitespace-nowrap" style="width: 15%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border font-medium text-fg-muted">
                            @forelse($users as $u)
                                <tr class="hover:bg-surface-hover/30 transition-colors duration-150">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center space-x-3.5">
                                            @if ($u->hasRegisteredFace())
                                                <div class="relative w-10 h-10 rounded-xl border-2 border-success/30 overflow-hidden bg-surface-muted flex-shrink-0 shadow-sm">
                                                    <img src="{{ $u->getMasterFaceUrl() }}" class="w-full h-full object-cover -scale-x-100">
                                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-success ring-2 ring-surface" title="Wajah Terverifikasi"></span>
                                                </div>
                                            @else
                                                <div class="relative w-10 h-10 rounded-xl bg-surface-muted border-2 border-warning/30 flex items-center justify-center font-bold text-warning flex-shrink-0 shadow-sm">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-warning ring-2 ring-surface" title="Wajah Belum Terdaftar"></span>
                                                </div>
                                            @endif
                                            <div>
                                                <span class="label-sm font-bold text-fg block whitespace-nowrap">{{ $u->name }}</span>
                                                <span class="label-xs text-fg-subtle block mt-0.5 font-medium whitespace-nowrap">#{{ $u->employee_id }} · {{ strtolower($u->email) }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        <span class="label-sm font-bold text-fg block whitespace-nowrap">{{ $u->branch->name ?? 'Belum Ditentukan' }}</span>
                                        <span class="badge-rect-info mt-1 inline-block whitespace-nowrap text-[10px] font-bold">
                                            {{ ucfirst($u->work_mode) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        @if ($u->is_registered)
                                            <span class="badge-success whitespace-nowrap shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-success mr-1.5 animate-pulse"></span>
                                                Kunci Wajah Aktif
                                            </span>
                                        @else
                                            <span class="badge-danger whitespace-nowrap shadow-sm">
                                                <span class="w-1.5 h-1.5 rounded-full bg-danger mr-1.5"></span>
                                                Belum Terdaftar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button wire:click="openUserEditModal({{ $u->id }})"
                                                class="btn-secondary btn-xs shadow-sm whitespace-nowrap hover:scale-[1.02] active:scale-[0.98] transition-transform">
                                                <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>Kelola
                                            </button>

                                            @if ($u->is_registered)
                                                <button wire:click="revokeBiometrics({{ $u->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menghapus dan membatalkan kunci biometrik untuk {{ $u->name }}? Karyawan ini tidak akan bisa absensi sebelum didaftarkan ulang."
                                                    class="btn-danger-outline btn-xs whitespace-nowrap hover:scale-[1.02] active:scale-[0.98] transition-transform">
                                                    <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>Hapus Wajah
                                                </button>
                                            @else
                                                <div class="flex items-center justify-end" x-data="{ uploading: false }">
                                                    <label class="btn-primary-outline btn-xs relative cursor-pointer whitespace-nowrap hover:scale-[1.02] active:scale-[0.98] transition-transform">
                                                        <span x-show="!uploading" class="flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                            </svg>
                                                            Unggah Wajah
                                                        </span>
                                                        <span x-show="uploading" class="animate-pulse flex items-center gap-1">
                                                            <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                            Mengunggah...
                                                        </span>
                                                        <input type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                                                            @change="
                                                            const file = $event.target.files[0];
                                                            if (file) {
                                                                uploading = true;
                                                                const reader = new FileReader();
                                                                reader.onload = (e) => {
                                                                    @this.call('enrollUserFace', {{ $u->id }}, e.target.result)
                                                                        .then(() => { uploading = false; })
                                                                        .catch(() => { uploading = false; });
                                                                };
                                                                reader.readAsDataURL(file);
                                                            }
                                                        ">
                                                    </label>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-12 text-center text-fg-subtle font-bold tracking-wider">
                                        <svg class="w-8 h-8 mx-auto mb-2 text-fg-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Tidak ada data karyawan yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View (Separate Cards) -->
            <div class="md:hidden space-y-4">
                @forelse($users as $u)
                    <div class="p-4 bg-surface hover:bg-surface-hover/10 transition-colors border border-border rounded-2xl shadow-sm">
                        <div class="flex items-start gap-3">
                            @if ($u->hasRegisteredFace())
                                <div class="relative w-11 h-11 rounded-xl border-2 border-success/30 overflow-hidden bg-surface-muted flex-shrink-0 shadow-sm">
                                    <img src="{{ $u->getMasterFaceUrl() }}" class="w-full h-full object-cover">
                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-success ring-2 ring-surface"></span>
                                </div>
                            @else
                                <div class="relative w-11 h-11 rounded-xl bg-surface-muted border-2 border-warning/30 flex items-center justify-center font-bold text-warning flex-shrink-0 shadow-sm">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                    <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-warning ring-2 ring-surface"></span>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <span class="label-sm font-bold text-fg block truncate">{{ $u->name }}</span>
                                <span class="label-xs text-fg-subtle block mt-0.5 font-medium truncate">#{{ $u->employee_id }} · {{ strtolower($u->email) }}</span>
                            </div>
                            @if ($u->is_registered)
                                <span class="badge-success flex-shrink-0 shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success mr-1 animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="badge-danger flex-shrink-0 shadow-sm">Belum</span>
                            @endif
                        </div>

                        <div class="mt-3 flex items-center justify-between bg-surface-muted/50 p-3 rounded-lg border border-border/50 text-xs">
                            <div>
                                <div class="label-xs text-fg-subtle font-semibold uppercase tracking-wider">Cabang Penempatan</div>
                                <div class="label-sm font-bold text-fg truncate mt-0.5">{{ $u->branch->name ?? 'Belum Ditentukan' }}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="label-xs text-fg-subtle font-semibold uppercase tracking-wider mb-1">Mode Kerja</div>
                                <span class="badge-rect-info inline-block text-[9.5px] font-bold">{{ strtoupper($u->work_mode) }}</span>
                            </div>
                        </div>

                        <div class="mt-3 flex items-center justify-end gap-2 border-t border-border pt-3">
                            <button wire:click="openUserEditModal({{ $u->id }})" class="btn-secondary btn-xs shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-transform">Kelola</button>
                            @if ($u->is_registered)
                                <button wire:click="revokeBiometrics({{ $u->id }})"
                                    wire:confirm="Apakah Anda yakin ingin menghapus dan membatalkan kunci biometrik untuk {{ $u->name }}?"
                                    class="btn-danger-outline btn-xs hover:scale-[1.02] active:scale-[0.98] transition-transform">Hapus Wajah</button>
                            @else
                                <div x-data="{ uploading: false }">
                                    <label class="btn-primary-outline btn-xs relative cursor-pointer hover:scale-[1.02] active:scale-[0.98] transition-transform">
                                        <span x-show="!uploading" class="flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>+ Wajah
                                        </span>
                                        <span x-show="uploading" class="animate-pulse">...</span>
                                        <input type="file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
                                            @change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                uploading = true;
                                                const reader = new FileReader();
                                                reader.onload = (e) => {
                                                    @this.call('enrollUserFace', {{ $u->id }}, e.target.result)
                                                        .then(() => { uploading = false; })
                                                        .catch(() => { uploading = false; });
                                                };
                                                reader.readAsDataURL(file);
                                            }
                                        ">
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-fg-subtle font-bold tracking-wider label-xs bg-surface border border-border rounded-2xl shadow-sm">
                        Tidak ada data karyawan yang cocok.
                    </div>
                @endforelse
            </div>

            <div class="mt-5">{{ $users->links() }}</div>
        </div>
    </div>

    <!-- Create Employee Modal -->
    <div x-data="{ open: @entangle('showRegisterModal') }" x-show="open"
        class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 modal-overlay"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-lg max-h-[85vh] modal-glass rounded-xl p-6 sm:p-8 relative overflow-hidden flex flex-col shadow-2xl">

            <div class="flex items-center justify-between mb-6 border-b border-border pb-4">
                <h3 class="heading-3 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-primary inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg> Registrasi Karyawan Baru
                </h3>
                <button @click="open = false" class="text-fg-muted hover:text-fg text-xl font-bold">&times;</button>
            </div>

            <form wire:submit.prevent="registerUser" class="space-y-4 flex-1 overflow-y-auto pr-1 scrollbar-thin">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Nama Lengkap</label>
                        <input wire:model="new_name" type="text" placeholder="Masukkan nama lengkap..." required class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('new_name')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">ID Karyawan (NIP)</label>
                        <input wire:model="new_employee_id" type="text" placeholder="Contoh: EMP-2026-001" required class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('new_employee_id')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Alamat Email</label>
                        <input wire:model="new_email" type="email" placeholder="Contoh: nama@domain.com" required class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('new_email')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Nomor Telepon</label>
                        <input wire:model="new_phone" type="text" placeholder="Contoh: 08123456789" class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('new_phone')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Sandi Akses Default</label>
                        <input wire:model="new_password" type="text" required class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                        @error('new_password')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Cabang Penempatan</label>
                        <select wire:model="new_branch_id" required class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="">Pilih Cabang</option>
                            @foreach ($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                        @error('new_branch_id')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Mode Kerja Default</label>
                        <select wire:model="new_work_mode" required class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="wfo">WFO (Di Kantor)</option>
                            <option value="wfh">WFH (Di Rumah)</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        @error('new_work_mode')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Peran Sistem (Role)</label>
                        <select wire:model="new_role" required class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                            <option value="employee">Employee (Karyawan)</option>
                            <option value="manager">Manager</option>
                            <option value="hr_admin">HR Admin</option>
                        </select>
                        @error('new_role')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-border">
                    <button @click="open = false" type="button" class="btn-sm btn-secondary hover:scale-[1.02] active:scale-[0.98] transition-transform">Batal</button>
                    <button type="submit" class="btn-sm btn-primary shadow hover:scale-[1.02] active:scale-[0.98] transition-transform">Buat Akun Karyawan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit/Manage Employee Modal -->
    <div x-data="{ open: @entangle('showUserEditModal') }" x-show="open"
        class="fixed inset-0 z-[100] flex items-start justify-center p-4 py-8 overflow-y-auto modal-overlay"
        style="display: none;"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div @click.away="open = false"
            class="w-full max-w-3xl my-auto rounded-xl relative overflow-hidden modal-glass shadow-2xl"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">

            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 sm:px-8 py-5 border-b border-border">
                <div>
                    <h3 class="heading-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Kelola Akun Karyawan
                    </h3>
                    <p class="label-xs text-fg-muted mt-0.5">Ubah rincian profil, penempatan cabang, biometrik wajah, perangkat terpercaya, dan peran</p>
                </div>
                <button @click="open = false" type="button" class="btn-ghost btn-xs text-xl leading-none font-bold">&times;</button>
            </div>

            @php
                $selectedUser = $selectedUserId ? \App\Models\User::find($selectedUserId) : null;
            @endphp
            <form wire:submit.prevent="saveUser" class="px-6 sm:px-8 py-6 space-y-6">
                <!-- Two Column Layout: Face + Details -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                    <!-- Left Column: Master Face Photos (3 Angles) -->
                    <div class="md:col-span-4 flex flex-col items-center">
                        <span class="block label-xs mb-2.5 text-center font-bold uppercase tracking-wider text-fg-muted">Template Wajah</span>

                        @if ($selectedUser && $selectedUser->hasRegisteredFace())
                            <div class="relative w-full aspect-[3/4] max-w-[170px] rounded-2xl overflow-hidden border-4 border-emerald-500/25 shadow-lg shadow-emerald-500/10 group">
                                <img src="{{ $selectedUser->getMasterFaceUrl() }}"
                                    class="w-full h-full object-cover -scale-x-100 transition-transform duration-300 group-hover:scale-110">
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-emerald-950/80 to-transparent p-2.5 pt-6 flex items-center justify-center">
                                    <span class="badge-success text-[9.5px] font-bold shadow-sm backdrop-blur-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success mr-1.5 animate-pulse"></span>
                                        WAJAH TERVERIFIKASI
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="relative w-full aspect-[3/4] max-w-[170px] bg-surface-muted border border-dashed border-border rounded-2xl overflow-hidden flex flex-col items-center justify-center shadow-inner group">
                                <div class="absolute inset-0 bg-gradient-to-tr from-warning/5 to-transparent"></div>
                                <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-warning/40 rounded-tl-lg"></div>
                                <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-warning/40 rounded-tr-lg"></div>
                                <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-warning/40 rounded-bl-lg"></div>
                                <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-warning/40 rounded-br-lg"></div>
                                
                                <div class="text-center text-fg-subtle p-4 relative z-10">
                                    <svg class="w-10 h-10 mx-auto text-warning/60 mb-2.5 animate-pulse" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="label-xs text-warning font-bold block uppercase tracking-wider">Unregistered</span>
                                    <span class="text-[9.5px] text-fg-subtle mt-1 block">Belum Ada Wajah</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Personal & Employee details -->
                    <div class="md:col-span-8 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Nama Lengkap</label>
                                <input wire:model="edit_name" type="text" required class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_name')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Nomor Induk / ID Karyawan</label>
                                <input wire:model="edit_employee_id" type="text" required class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_employee_id')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Alamat Email</label>
                                <input wire:model="edit_email" type="email" required class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_email')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Nomor Telepon (WhatsApp)</label>
                                <input wire:model="edit_phone" type="text" placeholder="Contoh: 081234567890" class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_phone')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Ubah Sandi Baru (Opsional)</label>
                                <input wire:model="edit_password" type="password" placeholder="Kosongkan jika tidak diubah" class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_password')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Tanggal Lahir</label>
                                <input wire:model="edit_date_of_birth" type="date" class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_date_of_birth')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Tanggal Masuk Kerja (Joined)</label>
                                <input wire:model="edit_joined_at" type="date" class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_joined_at')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Kuota Cuti Tahunan</label>
                                <input wire:model="edit_annual_leave_quota" type="number" required min="0" max="100" class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                @error('edit_annual_leave_quota')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Cabang Kantor</label>
                                <select wire:model="edit_branch_id" required class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('edit_branch_id')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Mode Kerja</label>
                                <select wire:model="edit_work_mode" required class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="wfo">WFO (Di Kantor)</option>
                                    <option value="wfh">WFH (Di Rumah)</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                                @error('edit_work_mode')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold text-fg-muted">Peran Sistem</label>
                                <select wire:model="edit_role" required class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                    <option value="employee">Employee</option>
                                    <option value="manager">Manager</option>
                                    <option value="hr_admin">HR Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                                @error('edit_role')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trusted Devices / Fingerprint Section -->
                <div class="border-t border-border pt-5">
                    <span class="block label-xs mb-3 font-bold uppercase tracking-wider text-fg-muted flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 009 11.57V10c0-2.454-1.564-4.52-3.741-5.26M12 11a22.95 22.95 0 003.44 9.571M17.25 18a13.9 13.9 0 01-3.23-6.43V10c0-2.52-1.688-4.646-4.02-5.328M12 2a9.961 9.961 0 017 2.828A9.961 9.961 0 0122 10c0 2.213-.72 4.257-1.93 5.923" />
                        </svg>
                        Telemetri Perangkat & Browser Karyawan
                    </span>

                    @if (empty($userDevices))
                        <div class="bg-surface-muted border border-border rounded-xl p-6 text-center label-xs font-bold text-fg-subtle shadow-inner">
                            <svg class="w-8 h-8 mx-auto mb-2 text-fg-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Tidak ada perangkat yang terdaftar atau digunakan karyawan ini.
                        </div>
                    @else
                        <div class="max-h-[160px] overflow-y-auto space-y-2 pr-1.5 divide-y divide-border/60 bg-surface-muted border border-border rounded-xl p-4 text-xs shadow-inner scrollbar-thin">
                            @foreach ($userDevices as $index => $device)
                                <div class="flex justify-between items-center pt-3 {{ $index === 0 ? 'pt-0' : '' }}">
                                    <div class="space-y-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="label-sm font-bold text-fg flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-fg-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $device['browser'] ?? 'Browser' }} on {{ $device['os'] ?? 'OS' }}
                                            </span>
                                            @if ($device['trusted'])
                                                <span class="badge-success text-[10px] py-0 px-2 font-bold shadow-sm">Trusted</span>
                                            @else
                                                <span class="badge-danger text-[10px] py-0 px-2 font-bold shadow-sm">Pending Approval</span>
                                            @endif
                                        </div>
                                        <div class="label-xs font-mono text-fg-subtle tracking-wider bg-surface/50 border border-border/40 px-2 py-0.5 rounded inline-block">
                                            HASH: {{ substr($device['device_hash'], 0, 12) }}... · PLATFORM: {{ strtoupper($device['platform'] ?? 'N/A') }}
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right label-xs text-fg-muted">
                                            <span>Terakhir absensi:</span>
                                            <span class="block label-sm font-bold text-primary mt-0.5">
                                                {{ $device['last_used_at'] ? \Carbon\Carbon::parse($device['last_used_at'])->translatedFormat('d M Y, H:i') : 'Belum Pernah' }}
                                            </span>
                                        </div>
                                        <button type="button" wire:click="toggleDeviceTrust({{ $device['id'] }})"
                                            class="btn-xs {{ $device['trusted'] ? 'btn-danger-outline' : 'btn-success' }} min-w-[80px] shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-transform">
                                            {{ $device['trusted'] ? 'Cabut' : 'Setujui' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Footer Section: Toggles & Permanently Delete -->
                <div class="pt-5 border-t border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center">
                        <button type="button" @click="$wire.edit_is_active = !$wire.edit_is_active"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="$wire.edit_is_active ? 'bg-primary' : 'bg-surface-muted'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="$wire.edit_is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="ml-3 label-sm text-fg-muted font-bold">Akun Karyawan Aktif</span>
                    </div>

                    <button type="button" wire:click="deleteUser({{ $selectedUserId ?? 0 }})"
                        wire:confirm="PERINGATAN KERAS! Apakah Anda benar-benar yakin ingin menghapus permanen akun karyawan ini? Semua riwayat absensi dan data terkait akan dihapus secara permanen dari basis data."
                        class="btn-danger-outline btn-sm shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-transform">
                        <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>Hapus Akun Karyawan
                    </button>
                </div>

                <div class="flex justify-end space-x-3 pt-5 border-t border-border">
                    <button @click="open = false" type="button" class="btn-sm btn-secondary hover:scale-[1.02] active:scale-[0.98] transition-transform">Batal</button>
                    <button type="submit" class="btn-sm btn-primary shadow hover:scale-[1.02] active:scale-[0.98] transition-transform">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
