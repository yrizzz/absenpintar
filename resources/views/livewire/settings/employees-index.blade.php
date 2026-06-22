<div class="py-8 min-h-screen">
    <div class="px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="heading-1">Kelola Karyawan</h1>
                <p class="mt-1 label-sm">Registrasi karyawan baru, kelola template biometrik wajah, kelola perangkat terpercaya, dan atur peran sistem.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button wire:click="$set('showRegisterModal', true)" type="button"
                    class="btn-sm btn-primary py-2 shadow-md">
                    <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg> Tambah Karyawan
                </button>
                <span class="badge-success">
                    <span class="w-1.5 h-1.5 bg-success rounded-full mr-1.5"></span>
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
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-surface-muted border border-border rounded-xl p-4 sm:p-5 relative overflow-hidden">
                <div class="label-xs text-fg-muted">Total Tenaga Kerja</div>
                <div class="heading-value mt-1">{{ $stats['total'] }}</div>
                <div class="label-xs text-fg-subtle mt-0.5">Akun personel terdaftar</div>
            </div>

            <div class="bg-surface-muted border border-border rounded-xl p-4 sm:p-5 relative overflow-hidden">
                <div class="label-xs text-fg-muted">Identitas Terverifikasi</div>
                <div class="heading-value text-success mt-1">{{ $stats['enrolled'] }}</div>
                <div class="label-xs text-fg-subtle mt-0.5">Verifikasi wajah aktif</div>
            </div>

            <div class="bg-surface-muted border border-border rounded-xl p-4 sm:p-5 relative overflow-hidden">
                <div class="label-xs text-fg-muted">Menunggu Registrasi Wajah</div>
                <div class="heading-value text-warning mt-1">{{ $stats['pending'] }}</div>
                <div class="label-xs text-fg-subtle mt-0.5">Dibatasi dari absensi mandiri</div>
            </div>

            <div class="bg-surface-muted border border-border rounded-xl p-4 sm:p-5 relative overflow-hidden">
                <div class="label-xs text-primary">Tingkat Kepatuhan Kunci</div>
                <div class="heading-value text-primary mt-1">{{ $stats['rate'] }}%</div>
                <div class="w-full bg-surface-muted h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-primary h-full rounded-full" style="width: {{ $stats['rate'] }}%"></div>
                </div>
            </div>
        </div>

        <div class="card p-6 sm:p-8">
            <!-- Search, Filter & Audit controls -->
            <div class="bg-surface-muted border border-border rounded-xl p-4 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block label-xs mb-1.5 font-semibold">Cari Nama / ID Karyawan</label>
                    <div class="relative">
                        <input wire:model.live.debounce.300ms="search" type="text"
                            placeholder="Masukkan nama, email, atau ID..."
                            class="w-full text-xs rounded-lg border border-border bg-surface text-fg px-3 py-2">
                        @if ($search)
                            <button @click="$wire.set('search', '')"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 text-fg-muted hover:text-fg text-xs font-bold">×</button>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block label-xs mb-1.5 font-semibold">Filter Status Wajah</label>
                    <select wire:model.live="statusFilter"
                        class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2">
                        <option value="all">Semua Karyawan</option>
                        <option value="registered">Wajah Terverifikasi (Aktif)</option>
                        <option value="pending">Belum Registrasi Wajah</option>
                    </select>
                </div>

                <div>
                    <label class="block label-xs mb-1.5 font-semibold">Penempatan Kantor Cabang</label>
                    <select wire:model.live="branchFilter"
                        class="w-full text-xs cursor-pointer rounded-lg border border-border bg-surface text-fg px-3 py-2">
                        <option value="all">Semua Cabang</option>
                        @foreach ($branches as $b)
                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Main Ledger Table -->
            <div class="border border-border rounded-xl overflow-hidden bg-surface-muted">
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="border-b border-border bg-surface-muted label-xs font-bold text-fg-muted">
                                <x-sort-th field="name" :sort="$userSortField" :dir="$userSortDir" method="sortUsers" class="whitespace-nowrap" style="width: 34%;">Karyawan</x-sort-th>
                                <x-sort-th field="branch" :sort="$userSortField" :dir="$userSortDir" method="sortUsers" class="whitespace-nowrap" style="width: 22%;">Cabang & Mode Kerja</x-sort-th>
                                <x-sort-th field="is_registered" :sort="$userSortField" :dir="$userSortDir" method="sortUsers" align="center" class="whitespace-nowrap" style="width: 18%;">Status Registrasi Wajah</x-sort-th>
                                <th class="px-5 py-3.5 text-center whitespace-nowrap" style="width: 14%;">Sudut Telemetri</th>
                                <th class="px-5 py-3.5 text-right whitespace-nowrap" style="width: 12%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border font-medium text-fg-muted">
                            @forelse($users as $u)
                                <tr class="hover:bg-surface-muted transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center space-x-3.5">
                                            @if ($u->hasRegisteredFace())
                                                <div class="relative w-9 h-9 rounded-xl border border-border overflow-hidden bg-surface-muted flex-shrink-0">
                                                    <img src="{{ $u->getMasterFaceUrl() }}" class="w-full h-full object-cover -scale-x-100">
                                                </div>
                                            @else
                                                <div class="w-9 h-9 rounded-xl bg-surface-muted border border-border flex items-center justify-center font-bold text-fg-muted flex-shrink-0">
                                                    {{ strtoupper(substr($u->name, 0, 1)) }}
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
                                        <span class="badge-rect-info mt-1 inline-block whitespace-nowrap">
                                            {{ ucfirst($u->work_mode) }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-4 text-center">
                                        @if ($u->is_registered)
                                            <span class="badge-success whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-success mr-1.5"></span>
                                                Kunci Wajah Aktif
                                            </span>
                                        @else
                                            <span class="badge-danger whitespace-nowrap">
                                                <span class="w-1.5 h-1.5 rounded-full bg-danger mr-1.5"></span>
                                                Belum Terdaftar
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-center text-xs">
                                        @if ($u->is_registered)
                                            <div class="flex items-center justify-center space-x-1">
                                                <span class="label-xs font-bold text-primary mr-1.5">{{ $u->registered_angles }}/3</span>
                                                <span class="w-2.5 h-2.5 rounded-full {{ $u->registered_angles >= 1 ? 'bg-primary' : 'bg-surface-muted border border-border' }}" title="Sudut Tengah"></span>
                                                <span class="w-2.5 h-2.5 rounded-full {{ $u->registered_angles >= 2 ? 'bg-info' : 'bg-surface-muted border border-border' }}" title="Profil Kiri"></span>
                                                <span class="w-2.5 h-2.5 rounded-full {{ $u->registered_angles >= 3 ? 'bg-success' : 'bg-surface-muted border border-border' }}" title="Profil Kanan"></span>
                                            </div>
                                        @else
                                            <span class="label-xs text-fg-subtle whitespace-nowrap">0 Sudut Terkunci</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button wire:click="openUserEditModal({{ $u->id }})"
                                                class="btn-primary btn-xs shadow-sm whitespace-nowrap">
                                                <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>Kelola
                                            </button>

                                            @if ($u->is_registered)
                                                <button wire:click="revokeBiometrics({{ $u->id }})"
                                                    wire:confirm="Apakah Anda yakin ingin menghapus dan membatalkan kunci biometrik untuk {{ $u->name }}? Karyawan ini tidak akan bisa absensi sebelum didaftarkan ulang."
                                                    class="btn-danger-outline btn-xs whitespace-nowrap">
                                                    <svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>Hapus Wajah
                                                </button>
                                            @else
                                                <div class="flex items-center justify-end" x-data="{ uploading: false }">
                                                    <label class="btn-primary-outline btn-xs relative cursor-pointer whitespace-nowrap">
                                                        <span x-show="!uploading"><svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                                        </svg>Unggah Wajah</span>
                                                        <span x-show="uploading" class="animate-pulse">Mengunggah...</span>
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

                <!-- Mobile View -->
                <div class="md:hidden divide-y divide-border">
                    @forelse($users as $u)
                        <div class="p-4 bg-surface">
                            <div class="flex items-start gap-3">
                                @if ($u->hasRegisteredFace())
                                    <div class="relative w-10 h-10 rounded-xl border border-border overflow-hidden bg-surface-muted flex-shrink-0">
                                        <img src="{{ $u->getMasterFaceUrl() }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-surface-muted border border-border flex items-center justify-center font-bold text-fg-muted flex-shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <span class="label-sm font-bold text-fg block truncate">{{ $u->name }}</span>
                                    <span class="label-xs text-fg-subtle block mt-0.5 font-medium truncate">#{{ $u->employee_id }} · {{ strtolower($u->email) }}</span>
                                </div>
                                @if ($u->is_registered)
                                    <span class="badge-success flex-shrink-0">Aktif</span>
                                @else
                                    <span class="badge-danger flex-shrink-0">Belum</span>
                                @endif
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                <div>
                                    <div class="label-xs text-fg-subtle font-semibold">Cabang & Mode</div>
                                    <div class="label-sm font-bold text-fg truncate">{{ $u->branch->name ?? 'Belum Ditentukan' }}</div>
                                    <span class="badge-rect-info mt-1 inline-block">{{ ucfirst($u->work_mode) }}</span>
                                </div>
                                <div>
                                    <div class="label-xs text-fg-subtle font-semibold">Telemetri</div>
                                    <div class="label-sm font-bold text-primary">{{ $u->is_registered ? $u->registered_angles . '/3 Terkunci' : '0 Terkunci' }}</div>
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-border pt-3">
                                <button wire:click="openUserEditModal({{ $u->id }})" class="btn-primary btn-xs shadow-sm">Kelola</button>
                                @if ($u->is_registered)
                                    <button wire:click="revokeBiometrics({{ $u->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus dan membatalkan kunci biometrik untuk {{ $u->name }}?"
                                        class="btn-danger-outline btn-xs">Hapus Wajah</button>
                                @else
                                    <div x-data="{ uploading: false }">
                                        <label class="btn-primary-outline btn-xs relative cursor-pointer">
                                            <span x-show="!uploading">+ Wajah</span>
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
                        <div class="px-5 py-12 text-center text-fg-subtle font-bold tracking-wider label-xs bg-surface">
                            Tidak ada data karyawan yang cocok.
                        </div>
                    @endforelse
                </div>
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
                        <label class="block label-xs mb-1.5 font-semibold">Nama Lengkap</label>
                        <input wire:model="new_name" type="text" placeholder="Masukkan nama lengkap..." required class="w-full text-xs">
                        @error('new_name')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold">ID Karyawan (NIP)</label>
                        <input wire:model="new_employee_id" type="text" placeholder="Contoh: EMP-2026-001" required class="w-full text-xs">
                        @error('new_employee_id')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5 font-semibold">Alamat Email</label>
                        <input wire:model="new_email" type="email" placeholder="Contoh: nama@domain.com" required class="w-full text-xs">
                        @error('new_email')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold">Nomor Telepon</label>
                        <input wire:model="new_phone" type="text" placeholder="Contoh: 08123456789" class="w-full text-xs">
                        @error('new_phone')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5 font-semibold">Sandi Akses Default</label>
                        <input wire:model="new_password" type="text" required class="w-full text-xs">
                        @error('new_password')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold">Cabang Penempatan</label>
                        <select wire:model="new_branch_id" required class="w-full text-xs cursor-pointer">
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
                        <label class="block label-xs mb-1.5 font-semibold">Mode Kerja Default</label>
                        <select wire:model="new_work_mode" required class="w-full text-xs cursor-pointer">
                            <option value="wfo">WFO (Di Kantor)</option>
                            <option value="wfh">WFH (Di Rumah)</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        @error('new_work_mode')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5 font-semibold">Peran Sistem (Role)</label>
                        <select wire:model="new_role" required class="w-full text-xs cursor-pointer">
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
                    <button @click="open = false" type="button" class="btn-sm btn-secondary">Batal</button>
                    <button type="submit" class="btn-sm btn-primary shadow">Buat Akun Karyawan</button>
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
                        <span class="block label-xs mb-2.5 text-center font-semibold">Template Wajah</span>

                        @if ($selectedUser && $selectedUser->hasRegisteredFace())
                            <div class="grid grid-cols-3 gap-2 w-full max-w-[220px]">
                                @foreach (['front' => 'Depan', 'left' => 'Kiri', 'right' => 'Kanan'] as $angle => $label)
                                    <div class="flex flex-col items-center">
                                        <div class="relative w-full aspect-square bg-surface-muted border border-border rounded-xl overflow-hidden flex items-center justify-center shadow-md">
                                            @if ($selectedUser->getFaceAngleUrl($angle))
                                                <img src="{{ $selectedUser->getFaceAngleUrl($angle) }}"
                                                    class="w-full h-full object-cover {{ $angle === 'front' ? '-scale-x-100' : '' }}">
                                            @else
                                                <svg class="w-5 h-5 text-fg-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="label-xs text-fg-subtle mt-1.5 text-center">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 text-center">
                                <span class="badge-rect-success font-bold text-xs">
                                    <svg class="w-3.5 h-3.5 mr-1 text-success inline-block" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>{{ $selectedUser->registered_angles }}/3 Sudut Terdaftar
                                </span>
                            </div>
                        @else
                            <div class="relative w-full aspect-square max-w-[180px] bg-surface-muted border border-border rounded-xl overflow-hidden flex items-center justify-center shadow-md">
                                <div class="text-center text-fg-subtle p-4">
                                    <svg class="w-10 h-10 mx-auto text-fg-subtle mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="label-xs text-fg-subtle block font-semibold">Wajah Belum Terdaftar</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Personal & Employee details -->
                    <div class="md:col-span-8 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Nama Lengkap</label>
                                <input wire:model="edit_name" type="text" required class="w-full text-xs">
                                @error('edit_name')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Nomor Induk / ID Karyawan</label>
                                <input wire:model="edit_employee_id" type="text" required class="w-full text-xs">
                                @error('edit_employee_id')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Alamat Email</label>
                                <input wire:model="edit_email" type="email" required class="w-full text-xs">
                                @error('edit_email')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Nomor Telepon (WhatsApp)</label>
                                <input wire:model="edit_phone" type="text" placeholder="Contoh: 081234567890" class="w-full text-xs">
                                @error('edit_phone')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Ubah Sandi Baru (Opsional)</label>
                                <input wire:model="edit_password" type="password" placeholder="Kosongkan jika tidak diubah" class="w-full text-xs">
                                @error('edit_password')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Tanggal Lahir</label>
                                <input wire:model="edit_date_of_birth" type="date" class="w-full text-xs">
                                @error('edit_date_of_birth')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Tanggal Masuk Kerja (Joined)</label>
                                <input wire:model="edit_joined_at" type="date" class="w-full text-xs">
                                @error('edit_joined_at')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Kuota Cuti Tahunan</label>
                                <input wire:model="edit_annual_leave_quota" type="number" required min="0" max="100" class="w-full text-xs">
                                @error('edit_annual_leave_quota')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Cabang Kantor</label>
                                <select wire:model="edit_branch_id" required class="w-full text-xs cursor-pointer">
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
                                <label class="block label-xs mb-1.5 font-semibold">Mode Kerja</label>
                                <select wire:model="edit_work_mode" required class="w-full text-xs cursor-pointer">
                                    <option value="wfo">WFO (Di Kantor)</option>
                                    <option value="wfh">WFH (Di Rumah)</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                                @error('edit_work_mode')
                                    <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5 font-semibold">Peran Sistem</label>
                                <select wire:model="edit_role" required class="w-full text-xs cursor-pointer">
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
                <div class="border-t border-border pt-4">
                    <span class="block label-xs mb-2.5 font-semibold"><svg class="w-5 h-5 mr-2 text-fg-muted inline-block"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>Telemetri Perangkat & Browser Karyawan</span>

                    @if (empty($userDevices))
                        <div class="bg-surface-muted border border-border rounded-xl p-4 text-center label-xs font-bold text-fg-subtle">
                            Tidak ada perangkat yang terdaftar atau digunakan karyawan ini.
                        </div>
                    @else
                        <div class="max-h-[140px] overflow-y-auto space-y-2 pr-1.5 divide-y divide-border bg-surface-muted border border-border rounded-xl p-3 text-xs">
                            @foreach ($userDevices as $index => $device)
                                <div class="flex justify-between items-center pt-2 {{ $index === 0 ? 'pt-0' : '' }}">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center space-x-2">
                                            <span class="label-sm font-bold text-fg">{{ $device['browser'] ?? 'Browser' }} on {{ $device['os'] ?? 'OS' }}</span>
                                            @if ($device['trusted'])
                                                <span class="badge-rect-success">Trusted</span>
                                            @else
                                                <span class="badge-rect-danger">Belum Disetujui</span>
                                            @endif
                                        </div>
                                        <div class="label-xs font-mono text-fg-subtle tracking-wider">
                                            Hash: {{ substr($device['device_hash'], 0, 12) }}... · Platform: {{ $device['platform'] ?? 'N/A' }}
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
                                            class="btn-xs {{ $device['trusted'] ? 'btn-danger-outline' : 'btn-success' }} min-w-[70px]">
                                            {{ $device['trusted'] ? 'Cabut' : 'Setujui' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Footer Section: Toggles & Permanently Delete -->
                <div class="pt-4 border-t border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center">
                        <button type="button" @click="$wire.edit_is_active = !$wire.edit_is_active"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="$wire.edit_is_active ? 'bg-primary' : 'bg-surface-muted'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="$wire.edit_is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="ml-3 label-sm text-fg-muted font-semibold">Akun Karyawan Aktif</span>
                    </div>

                    <button type="button" wire:click="deleteUser({{ $selectedUserId ?? 0 }})"
                        wire:confirm="PERINGATAN KERAS! Apakah Anda benar-benar yakin ingin menghapus permanen akun karyawan ini? Semua riwayat absensi dan data terkait akan dihapus secara permanen dari basis data."
                        class="btn-danger-outline btn-sm shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>Hapus Akun Karyawan
                    </button>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-border">
                    <button @click="open = false" type="button" class="btn-sm btn-secondary">Batal</button>
                    <button type="submit" class="btn-sm btn-primary shadow">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
