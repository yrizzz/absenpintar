<div class="py-8 min-h-screen text-fg bg-transparent">
    <div class="px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="heading-1">Kontrol & Parameter Sistem</h1>
            <p class="mt-1 label-sm">Konfigurasi profil keamanan global, margin geofence, dan skor risiko biometrik</p>
        </div>

        <!-- Two-Column Responsive Settings Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8 items-start">

            <!-- Left Column: Navigation Sidebar -->
            <div class="lg:col-span-1">
                <div
                    class="bg-transparent lg:bg-surface lg:border lg:border-border rounded-xl p-0 lg:p-4 lg:shadow-sm relative overflow-hidden">

                    <div
                        class="grid grid-cols-2 gap-2 lg:flex lg:flex-col lg:gap-0 lg:space-y-1.5 p-0.5">

                        <!-- Tab 1: Parameter Keamanan -->
                        <button wire:click="$set('activeTab', 'security')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'security' ? 'tab-active' : 'text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'security' ? 'text-primary-fg' : 'text-fg-muted' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Parameter Keamanan</span>
                        </button>

                        <!-- Tab 2: Jam Kerja & Lembur -->
                        <button wire:click="$set('activeTab', 'work_hours')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'work_hours' ? 'tab-active' : 'text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'work_hours' ? 'text-primary-fg' : 'text-fg-muted' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Jam Kerja & Lembur</span>
                        </button>

                        <!-- Tab 3: Batas Izin Kerja -->
                        <button wire:click="$set('activeTab', 'permission_rules')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'permission_rules' ? 'tab-active' : 'text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'permission_rules' ? 'text-primary-fg' : 'text-fg-muted' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Batas Izin Kerja</span>
                        </button>

                        <!-- Tab: Identitas Perusahaan -->
                        <button wire:click="$set('activeTab', 'company')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'company' ? 'tab-active' : 'text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'company' ? 'text-primary-fg' : 'text-fg-muted' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span>Perusahaan</span>
                        </button>

                        <!-- Tab: Hari Libur Nasional -->
                        <button wire:click="$set('activeTab', 'holidays')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'holidays' ? 'tab-active' : 'text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'holidays' ? 'text-primary-fg' : 'text-fg-muted' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Hari Libur Nasional</span>
                        </button>

                        <!-- Tab 4: Kantor Cabang & Geofence -->
                        <button wire:click="$set('activeTab', 'branches')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'branches' ? 'tab-active' : 'text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'branches' ? 'text-primary-fg' : 'text-fg-muted' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Kantor Cabang & Geofence</span>
                        </button>

                        <!-- Tab 5: Kelola Karyawan (Redirect Link) -->
                        <a href="{{ route('settings.employees') }}" wire:navigate
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 text-fg-muted"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kelola Karyawan</span>
                        </a>

                        <!-- Tab 6: Peran & Spatie -->
                        <button wire:click="$set('activeTab', 'roles')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'roles' ? 'tab-active' : 'text-fg-muted hover:text-fg hover:bg-surface-muted bg-surface-muted lg:bg-transparent border border-border lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'roles' ? 'text-primary-fg' : 'text-fg-muted' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>Peran & Otorisasi Spatie</span>
                        </button>

                        @if(auth()->user()->hasRole('super_admin'))
                        <!-- Tab 7: Hapus Riwayat Presensi (Super Admin Only) -->
                        <button wire:click="$set('activeTab', 'reset')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-xl transition-all flex flex-col lg:flex-row items-center justify-center lg:justify-start gap-1.5 lg:gap-2 text-center lg:text-left flex-shrink-0 min-h-[68px] lg:min-h-0 {{ $activeTab === 'reset' ? 'bg-danger-soft border border-danger/30 text-danger' : 'text-danger/70 hover:text-danger hover:bg-danger-soft bg-surface-muted lg:bg-transparent border border-danger/20 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'reset' ? 'text-danger' : 'text-danger/70' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Hapus Riwayat Presensi</span>
                        </button>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Right Column: Settings Content Panel -->
            <div class="lg:col-span-3">

                @if (session()->has('success'))
                    <div class="mb-4 p-3 bg-success-soft border border-success/20 text-success rounded-xl text-[11px] font-medium flex items-center gap-2 shadow-md">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-4 p-3 bg-danger-soft border border-danger/20 text-danger rounded-xl text-[11px] font-medium flex items-center gap-2 shadow-md">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB 1: PARAMETER KEAMANAN -->
                <!-- ========================================================== -->
                @if ($activeTab === 'security')
                    <div
                        class="card p-6 sm:p-8 max-w-4xl mx-auto">
                        <div class="mb-6">
                            <h3 class="heading-3">Parameter Keamanan & Akurasi GPS</h3>
                            <p class="label-sm mt-1">Konfigurasi radius geofence global, toleransi deviasi lokasi,
                                threshold deteksi liveness wajah AI, dan otentikasi perangkat (MFA).</p>
                        </div>

                        <form wire:submit.prevent="saveSettings" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Radius Geofence Maksimum (meter)</label>
                                    <input wire:model="radius" type="number" required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Jarak maksimum yang diizinkan dari
                                        titik koordinat pusat kantor cabang.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Toleransi Akurasi GPS (meter)</label>
                                    <input wire:model="gps_margin" type="number" required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Margin akurasi telemetry GPS
                                        perangkat. Nilai lebih rendah menjamin presisi lokasi lebih tinggi.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Ambang Batas Keaktifan Wajah AI
                                        (Liveness)</label>
                                    <input wire:model="biometric_liveness_threshold" type="number" step="0.01"
                                        min="0.80" max="1.00" required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Faktor pencocokan minimal dari
                                        mesin pemindai wajah. Rekomendasi: 0.95.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Pemicu Otentikasi Perangkat (MFA)</label>
                                    <div class="mt-2.5 flex items-center">
                                        <button type="button" @click="$wire.require_mfa = !$wire.require_mfa"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                            :class="$wire.require_mfa ? 'bg-primary' : 'bg-surface-muted'">
                                            <span
                                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                :class="$wire.require_mfa ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </button>
                                        <span class="ml-3 label-sm text-fg-muted">Wajibkan verifikasi MFA perangkat
                                            saat absen</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="btn-sm btn-primary">
                                    Terapkan Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB 2: JAM KERJA & LEMBUR -->
                <!-- ========================================================== -->
                @if ($activeTab === 'work_hours')
                    <div
                        class="card p-6 sm:p-8 max-w-4xl mx-auto">
                        <div class="mb-6">
                            <h3 class="heading-3">Kebijakan Jam Kerja & Lembur Otomatis</h3>
                            <p class="label-sm mt-1">Atur jam masuk dan pulang kerja reguler, toleransi keterlambatan,
                                zona waktu operasional, dan parameter minimal waktu lembur.</p>
                        </div>

                        <form wire:submit.prevent="saveSettings" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Mulai Jam Kerja</label>
                                    <input wire:model="work_hour_start" type="time" required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Waktu kerja reguler
                                        dimulai.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Selesai Jam Kerja</label>
                                    <input wire:model="work_hour_end" type="time" required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Waktu kerja reguler
                                        berakhir.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Toleransi Keterlambatan (menit)</label>
                                    <input wire:model="grace_period" type="number" required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Durasi tambahan bebas sanksi
                                        telat.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Zona Waktu Perusahaan</label>
                                    <select wire:model="timezone" required
                                        class="w-full text-xs cursor-pointer">
                                        <option value="Asia/Jakarta">WIB (Asia/Jakarta)</option>
                                        <option value="Asia/Makassar">WITA (Asia/Makassar)</option>
                                        <option value="Asia/Jayapura">WIT (Asia/Jayapura)</option>
                                    </select>
                                    <span class="label-xs text-fg-subtle mt-1 block">Zona waktu operasional
                                        perusahaan.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Min. Lembur (Jam)</label>
                                    <input wire:model="overtime_min_hours" type="number" step="0.5" required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Minimal durasi kerja tambahan
                                        untuk lembur.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Lembur Penuh (Jam)</label>
                                    <input wire:model="overtime_full_day_hours" type="number" step="0.5"
                                        required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Minimal jam lembur untuk terhitung
                                        penuh.</span>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="btn-sm btn-primary">
                                    Terapkan Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB 3: BATAS DURASI IZIN KERJA -->
                <!-- ========================================================== -->
                @if ($activeTab === 'permission_rules')
                    <div
                        class="card p-6 sm:p-8 max-w-4xl mx-auto">
                        <div class="mb-6">
                            <h3 class="heading-3">Batas Toleransi Durasi Izin Kerja</h3>
                            <p class="label-sm mt-1">Konfigurasi batas maksimal toleransi waktu dispensasi izin telat
                                datang, pulang cepat, maupun izin setengah hari kerja.</p>
                        </div>

                        <form wire:submit.prevent="saveSettings" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Batas Izin Telat (Jam)</label>
                                    <input wire:model="permission_max_late_hours" type="number" step="0.5"
                                        required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Batas jam kerja yang diperbolehkan
                                        untuk izin datang terlambat.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Batas Izin Pulang Awal (Jam)</label>
                                    <input wire:model="permission_max_early_hours" type="number" step="0.5"
                                        required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Batas jam kerja yang diperbolehkan
                                        untuk izin pulang awal.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Batas Izin 1/2 Hari (Jam)</label>
                                    <input wire:model="permission_max_half_day_hours" type="number" step="0.5"
                                        required
                                        class="w-full text-xs">
                                    <span class="label-xs text-fg-subtle mt-1 block">Batas jam kerja yang diperbolehkan
                                        untuk izin setengah hari.</span>
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="btn-sm btn-primary">
                                    Terapkan Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB: IDENTITAS PERUSAHAAN (kop surat resmi) -->
                <!-- ========================================================== -->
                @if ($activeTab === 'company')
                    <div
                        class="card p-6 sm:p-8 max-w-4xl mx-auto">
                        <div class="mb-6">
                            <h3 class="heading-3">Identitas Perusahaan</h3>
                            <p class="label-sm mt-1">Data ini tampil pada kop semua surat resmi (cuti, izin, dan
                                keterangan kehadiran) yang dicetak sistem.</p>
                        </div>

                        <form wire:submit.prevent="saveSettings" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block label-xs mb-2">Nama Perusahaan</label>
                                    <input wire:model="company_name" type="text" required
                                        class="w-full text-xs">
                                    @error('company_name') <span class="label-xs text-danger block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block label-xs mb-2">Alamat Perusahaan</label>
                                    <input wire:model="company_address" type="text" required
                                        class="w-full text-xs">
                                    @error('company_address') <span class="label-xs text-danger block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Telepon</label>
                                    <input wire:model="company_phone" type="text" required
                                        class="w-full text-xs">
                                    @error('company_phone') <span class="label-xs text-danger block mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Email HRD</label>
                                    <input wire:model="company_email" type="email" required
                                        class="w-full text-xs">
                                    @error('company_email') <span class="label-xs text-danger block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end pt-4">
                                <button type="submit" class="btn-sm btn-primary">
                                    Terapkan Konfigurasi
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB: HARI LIBUR NASIONAL -->
                <!-- ========================================================== -->
                @if ($activeTab === 'holidays')
                    <div class="card p-6 sm:p-8 max-w-4xl mx-auto space-y-8">
                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                            <div>
                                <h3 class="heading-3">Hari Libur Nasional</h3>
                                <p class="label-sm mt-1">Tanggal di sini ditandai <span class="font-bold text-danger">libur</span>
                                    pada ceklis bulanan Riwayat Kehadiran &amp; Laporan. Tambahkan libur yang berubah
                                    tiap tahun (Idul Fitri, Imlek, Nyepi, dll). Lima libur tanggal-tetap
                                    (Tahun Baru, Hari Buruh, Pancasila, Kemerdekaan, Natal) sudah otomatis tiap tahun.</p>
                            </div>
                            <div class="flex items-end gap-3 flex-shrink-0">
                                <div>
                                    <label class="block label-xs mb-2">Tahun</label>
                                    <select wire:model.live="holidayYear" class="text-xs">
                                        @foreach ($holidayYears as $y)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="button" wire:click="syncHolidays" wire:loading.attr="disabled" wire:target="syncHolidays"
                                    class="btn-sm btn-secondary whitespace-nowrap">
                                    <svg class="w-4 h-4" wire:loading.class="animate-spin" wire:target="syncHolidays" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span wire:loading.remove wire:target="syncHolidays">Sinkronkan Otomatis</span>
                                    <span wire:loading wire:target="syncHolidays">Menyinkronkan…</span>
                                </button>
                            </div>
                        </div>
                        <p class="label-xs text-fg-muted -mt-4">
                            Tombol <span class="font-semibold">Sinkronkan Otomatis</span> menarik hari libur resmi (termasuk Idul Fitri &amp; cuti bersama)
                            dari kalender nasional untuk {{ $holidayYears[1] ?? now()->year }}–{{ ($holidayYears[1] ?? now()->year) + 2 }}.
                            Sistem juga menyinkronkan ulang otomatis setiap minggu.
                        </p>

                        <!-- Add form -->
                        <form wire:submit.prevent="addHoliday" class="grid grid-cols-1 sm:grid-cols-[auto_1fr_auto] gap-4 sm:items-end bg-surface-muted rounded-xl p-4">
                            <div>
                                <label class="block label-xs mb-2">Tanggal</label>
                                <input wire:model="holiday_date" type="date" class="text-xs">
                                @error('holiday_date') <span class="label-xs text-danger block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block label-xs mb-2">Nama Hari Libur</label>
                                <input wire:model="holiday_name" type="text" placeholder="mis. Hari Raya Idul Fitri 1448 H" class="w-full text-xs">
                                @error('holiday_name') <span class="label-xs text-danger block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <button type="submit" class="btn-sm btn-primary w-full sm:w-auto">Tambah</button>
                            </div>
                        </form>

                        <!-- Holiday list -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="text-left label-xs border-b border-border">
                                        <th class="py-2 pr-4">Tanggal</th>
                                        <th class="py-2 pr-4">Hari</th>
                                        <th class="py-2 pr-4">Keterangan</th>
                                        <th class="py-2 pr-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($holidayRows as $row)
                                        <tr class="border-b border-border/60">
                                            <td class="py-2.5 pr-4 font-mono tabular-nums">{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                                            <td class="py-2.5 pr-4 text-fg-muted">{{ $row['day_name'] }}</td>
                                            <td class="py-2.5 pr-4">
                                                {{ $row['name'] }}
                                                @if ($row['source'] === 'manual')
                                                    <span class="ml-1.5 inline-flex items-center rounded-full bg-info-soft px-2 py-0.5 text-[10px] font-medium text-info">manual</span>
                                                @elseif ($row['source'] === 'fixed')
                                                    <span class="ml-1.5 inline-flex items-center rounded-full bg-surface-muted px-2 py-0.5 text-[10px] font-medium text-fg-muted">tetap</span>
                                                @else
                                                    <span class="ml-1.5 inline-flex items-center rounded-full bg-surface-muted px-2 py-0.5 text-[10px] font-medium text-fg-muted">otomatis</span>
                                                @endif
                                            </td>
                                            <td class="py-2.5 pr-4 text-right">
                                                @if ($row['id'])
                                                    <button type="button"
                                                        wire:click="deleteHoliday({{ $row['id'] }})"
                                                        wire:confirm="Hapus hari libur '{{ $row['name'] }}'?"
                                                        class="text-danger hover:underline font-medium">Hapus</button>
                                                @else
                                                    <span class="text-fg-subtle text-[11px]">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="py-6 text-center text-fg-muted">Belum ada hari libur untuk tahun ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB 4: KANTOR CABANG & GEOFENCE -->
                <!-- ========================================================== -->
                @if ($activeTab === 'branches')
                    <div class="space-y-8">
                        <!-- Kantor Cabang Aktif (Horizontal Cards) -->
                        <div
                            class="card p-6 sm:p-8">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="heading-3">Kantor Cabang Aktif</h3>
                                    <p class="label-sm mt-0.5">Daftar koordinat GPS dan status keaktifan perimeter
                                        geofence saat ini.</p>
                                </div>
                                <span class="badge-neutral">Total: {{ $branches->count() }} Cabang</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($branches as $b)
                                    <div
                                        class="p-5 bg-surface-muted border border-border rounded-xl space-y-3.5 hover:border-border transition-all">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="label-sm font-bold text-fg">{{ $b->name }}</span>
                                            @if ($b->is_active)
                                                <span class="badge-success text-[9px] py-0.5">Aktif</span>
                                            @else
                                                <span class="badge-neutral text-[9px] py-0.5">Nonaktif</span>
                                            @endif
                                        </div>
                                        <div
                                            class="label-xs font-mono text-fg-muted space-y-1 pt-1.5 border-t border-border">
                                            <div class="flex justify-between"><span>Latitude:</span> <span
                                                    class="text-fg">{{ $b->latitude }}</span></div>
                                            <div class="flex justify-between"><span>Longitude:</span> <span
                                                    class="text-fg">{{ $b->longitude }}</span></div>
                                            <div class="flex justify-between"><span>Radius Geofence:</span> <span
                                                    class="text-primary font-bold">{{ $b->radius }} meter</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kelola Kantor Cabang (CRUD Table) -->
                        <div
                            class="card p-6 sm:p-8">
                            <div class="flex flex-col gap-4 mb-6">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <h3 class="heading-3">Kelola Parameter Cabang</h3>
                                        <p class="label-sm mt-1">Tambah, ubah, dan hapus koordinat perimeter geofence
                                            kantor cabang perusahaan</p>
                                    </div>
                                    <button wire:click="openBranchModal()" type="button"
                                        class="btn-sm btn-primary flex-shrink-0 self-start sm:self-auto">
                                        <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 4v16m8-8H4" />
                                        </svg>Tambah Cabang Baru
                                    </button>
                                </div>
                                <div class="relative w-full sm:max-w-xs">
                                    <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-fg-subtle" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                    <input wire:model.live.debounce.350ms="branchSearch" type="text" placeholder="Cari nama, kode, atau alamat cabang…" class="text-xs pl-10">
                                </div>
                            </div>

                            <div class="border border-border rounded-xl overflow-hidden bg-surface-muted">
                                <div class="hidden md:block overflow-x-auto">
                                    <table class="w-full text-left text-xs border-collapse">
                                        <thead>
                                            <tr
                                                class="border-b border-border bg-surface-muted label-xs font-bold">
                                                <x-sort-th field="name" :sort="$branchSortField" :dir="$branchSortDir" method="sortBranches" class="whitespace-nowrap" style="width: 22%;">Nama & Kode Cabang</x-sort-th>
                                                <th class="px-5 py-3.5 whitespace-nowrap" style="width: 30%;">Alamat Kantor</th>
                                                <th class="px-5 py-3.5 whitespace-nowrap" style="width: 20%;">Koordinat (Lat / Lng)</th>
                                                <x-sort-th field="radius" :sort="$branchSortField" :dir="$branchSortDir" method="sortBranches" align="center" class="whitespace-nowrap" style="width: 12%;">Batas Radius</x-sort-th>
                                                <x-sort-th field="is_active" :sort="$branchSortField" :dir="$branchSortDir" method="sortBranches" align="center" class="whitespace-nowrap" style="width: 8%;">Status</x-sort-th>
                                                <th class="px-5 py-3.5 text-right whitespace-nowrap" style="width: 8%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-border font-medium text-fg-muted">
                                            @forelse($branchesTable as $b)
                                                <tr class="hover:bg-surface-muted transition-colors">
                                                    <td class="px-5 py-4">
                                                        <span
                                                            class="label-sm font-bold text-fg block">{{ $b->name }}</span>
                                                        <span
                                                            class="label-xs font-mono text-fg-subtle block tracking-wider mt-0.5">Kode:
                                                            {{ $b->code }}</span>
                                                    </td>

                                                    <td class="px-5 py-4 label-sm text-fg-muted max-w-xs truncate">
                                                        {{ $b->address }}
                                                    </td>

                                                    <td class="px-5 py-4 label-xs font-mono text-fg-muted">
                                                        <div>Lat: {{ $b->latitude }}</div>
                                                        <div>Lng: {{ $b->longitude }}</div>
                                                    </td>

                                                    <td class="px-5 py-4 text-center label-sm font-bold text-primary">
                                                        {{ $b->radius }} meter
                                                    </td>

                                                    <td class="px-5 py-4 text-center">
                                                        @if ($b->is_active)
                                                            <span class="badge-success">Aktif</span>
                                                        @else
                                                            <span class="badge-neutral">Nonaktif</span>
                                                        @endif
                                                    </td>

                                                    <td class="px-5 py-4 text-right">
                                                        <div class="flex items-center justify-end space-x-2">
                                                            <button wire:click="openBranchModal({{ $b->id }})"
                                                                type="button" class="btn-xs btn-primary">
                                                                <svg class="w-3.5 h-3.5 mr-1 inline-block"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                </svg>Ubah
                                                            </button>
                                                            <button wire:click="deleteBranch({{ $b->id }})"
                                                                wire:confirm="Apakah Anda yakin ingin menghapus cabang ini?"
                                                                type="button" class="btn-xs btn-danger-outline">
                                                                <svg class="w-3.5 h-3.5 mr-1 inline-block"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>Hapus
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6"
                                                        class="px-5 py-12 text-center text-fg-subtle font-bold label-xs">
                                                        Tidak ada data kantor cabang yang terdaftar di database.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Mobile: branch cards --}}
                                <div class="md:hidden divide-y divide-border">
                                    @forelse($branchesTable as $b)
                                        <div class="p-4">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <span class="label-sm font-bold text-fg block truncate">{{ $b->name }}</span>
                                                    <span class="label-xs font-mono text-fg-subtle block tracking-wider mt-0.5">Kode: {{ $b->code }}</span>
                                                </div>
                                                @if ($b->is_active)
                                                    <span class="badge-success flex-shrink-0">Aktif</span>
                                                @else
                                                    <span class="badge-neutral flex-shrink-0">Nonaktif</span>
                                                @endif
                                            </div>
                                            <div class="mt-3 label-sm text-fg-muted">{{ $b->address }}</div>
                                            <div class="mt-3 grid grid-cols-2 gap-2">
                                                <div>
                                                    <div class="label-xs">Koordinat</div>
                                                    <div class="label-xs font-mono text-fg-muted">{{ $b->latitude }}, {{ $b->longitude }}</div>
                                                </div>
                                                <div>
                                                    <div class="label-xs">Batas Radius</div>
                                                    <div class="label-sm font-bold text-primary">{{ $b->radius }} meter</div>
                                                </div>
                                            </div>
                                            <div class="mt-3 flex items-center gap-2 border-t border-border pt-3">
                                                <button wire:click="openBranchModal({{ $b->id }})" type="button" class="btn-xs btn-primary">Ubah</button>
                                                <button wire:click="deleteBranch({{ $b->id }})" wire:confirm="Apakah Anda yakin ingin menghapus cabang ini?" type="button" class="btn-xs btn-danger-outline">Hapus</button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-5 py-12 text-center text-fg-subtle font-bold label-xs">
                                            Tidak ada data kantor cabang yang cocok dengan pencarian Anda.
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="mt-5">{{ $branchesTable->links() }}</div>
                        </div>
                    </div>
                @endif



                <!-- ========================================================== -->
                <!-- TAB 3: PERAN & OTORISASI SPATIE -->
                <!-- ========================================================== -->
                @if ($activeTab === 'roles')
                    <div
                        class="card p-6 sm:p-8">
                        <div class="mb-8">
                            <h3 class="heading-3">Matriks Otorisasi Fitur (Spatie Rules)</h3>
                            <p class="label-sm">Batasi dan tentukan hak akses kapabilitas sistem secara detail untuk
                                masing-masing peran karyawan</p>

                            <div
                                class="mt-4 p-4 bg-warning-soft border border-warning/30 rounded-xl flex items-start gap-3 max-w-3xl">
                                <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-warning" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <span class="block mb-1 text-sm font-bold text-warning">Peringatan Keamanan Administratif</span>
                                    <p class="text-xs leading-relaxed text-warning/90 font-medium">
                                        Pemberian hak akses baru atau penolakan kapabilitas akan berdampak langsung seketika
                                        pada sesi aktif karyawan. Demi menjaga keamanan struktur otorisasi sistem, hanya akun
                                        Super Administrator yang diizinkan memodifikasi Spatie Matrix ini.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="border border-border rounded-xl overflow-hidden bg-surface-muted">
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-border bg-surface-muted label-xs font-bold">
                                            <th class="px-5 py-4 whitespace-nowrap" style="width: 40%;">Kunci Kapabilitas / Izin Sistem</th>
                                            @foreach ($roles as $r)
                                                <th class="px-5 py-4 text-center font-bold whitespace-nowrap" style="width: 15%;">
                                                    <span
                                                        class="label-sm font-bold text-fg block">{{ ucwords(str_replace('_', ' ', $r->name)) }}</span>
                                                    <span
                                                        class="label-xs text-fg-subtle font-bold mt-0.5">{{ $r->permissions->count() }}
                                                        Izin</span>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border font-medium text-fg-muted">
                                        @foreach ($allPermissions as $p)
                                            <tr class="hover:bg-surface-muted transition-colors">
                                                <td class="px-5 py-3">
                                                    <span
                                                        class="label-sm font-bold text-fg block">{{ ucwords(str_replace('view ', 'Lihat ', str_replace('create ', 'Buat ', str_replace('edit ', 'Ubah ', str_replace('delete ', 'Hapus ', str_replace('approve ', 'Setujui ', $p->name)))))) }}</span>
                                                    <span
                                                        class="label-xs text-fg-subtle normal-case mt-0.5">Kunci
                                                        teknis: {{ $p->name }}</span>
                                                </td>

                                                @foreach ($roles as $r)
                                                    <td class="px-5 py-3 text-center">
                                                        <button
                                                            wire:click="togglePermission({{ $r->id }}, '{{ $p->name }}')"
                                                            type="button"
                                                            class="relative inline-flex items-center focus:outline-none cursor-pointer">
                                                            <span
                                                                class="w-5 h-5 rounded-md border flex items-center justify-center transition-all {{ $r->hasPermissionTo($p->name) ? 'bg-primary-soft border-primary text-primary' : 'bg-surface-muted border-border text-transparent' }}">
                                                                ✓
                                                            </span>
                                                        </button>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Mobile: permission cards with per-role toggle chips --}}
                            <div class="md:hidden divide-y divide-border">
                                @foreach ($allPermissions as $p)
                                    <div class="p-4">
                                        <span class="label-sm font-bold text-fg block">{{ ucwords(str_replace('view ', 'Lihat ', str_replace('create ', 'Buat ', str_replace('edit ', 'Ubah ', str_replace('delete ', 'Hapus ', str_replace('approve ', 'Setujui ', $p->name)))))) }}</span>
                                        <span class="label-xs text-fg-subtle normal-case mt-0.5 block">Kunci teknis: {{ $p->name }}</span>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            @foreach ($roles as $r)
                                                <button wire:click="togglePermission({{ $r->id }}, '{{ $p->name }}')" type="button"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border text-[11px] font-bold transition-all {{ $r->hasPermissionTo($p->name) ? 'bg-primary-soft border-primary text-primary' : 'bg-surface-muted border-border text-fg-muted' }}">
                                                    <span>{{ $r->hasPermissionTo($p->name) ? '✓' : '○' }}</span>
                                                    {{ ucwords(str_replace('_', ' ', $r->name)) }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ========================================================== --}}
                {{-- TAB 7: RESET SISTEM (SUPER ADMIN ONLY) --}}
                {{-- ========================================================== --}}
                @if ($activeTab === 'reset' && auth()->user()->hasRole('super_admin'))
                    @php
                        $totalLogs   = \App\Models\AttendanceLog::withTrashed()->count();
                        $todayLogs   = \App\Models\AttendanceLog::withTrashed()->whereDate('timestamp', now()->toDateString())->count();
                        $totalEvents = \App\Models\SuspiciousEvent::count();
                    @endphp
                    <div class="card p-6 sm:p-8 border-danger/30">

                        {{-- Header --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-danger-soft border border-danger/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <h3 class="heading-3 text-danger">Hapus Riwayat & Foto Presensi</h3>
                            </div>
                            <p class="label-sm text-fg-muted ml-12">Hapus secara permanen data riwayat kehadiran karyawan beserta foto selfie yang diunggah saat melakukan absen. Tindakan ini tidak dapat dibatalkan.</p>
                        </div>

                        {{-- Stat Cards --}}
                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="bg-surface-muted border border-border rounded-xl p-4 text-center">
                                <div class="text-2xl font-semibold tabular-nums text-fg">{{ $totalLogs }}</div>
                                <div class="label-xs text-fg-subtle mt-1">Total Log Presensi</div>
                            </div>
                            <div class="bg-surface-muted border border-warning/20 rounded-xl p-4 text-center">
                                <div class="text-2xl font-semibold tabular-nums text-warning">{{ $todayLogs }}</div>
                                <div class="label-xs text-fg-subtle mt-1">Log Hari Ini</div>
                            </div>
                            <div class="bg-surface-muted border border-border rounded-xl p-4 text-center">
                                <div class="text-2xl font-semibold tabular-nums text-danger">{{ $totalEvents }}</div>
                                <div class="label-xs text-fg-subtle mt-1">Suspicious Events</div>
                            </div>
                        </div>

                        {{-- Warning Banner --}}
                        <div class="mb-6 p-4 bg-surface-muted border border-danger/20 rounded-xl flex items-start gap-3">
                            <svg class="w-5 h-5 text-danger flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="label-xs font-bold text-danger mb-1">Informasi & Batasan Tindakan</p>
                                <p class="label-xs text-fg-muted leading-relaxed">
                                    Tindakan penghapusan di bawah <strong class="text-danger">HANYA</strong> akan menghapus record log absensi (absen masuk/keluar) beserta file foto selfie yang diambil saat absen dari penyimpanan server. 
                                    <br><span class="text-fg-muted mt-1 block">🔒 <strong class="text-fg">Data yang Aman & TIDAK akan Terhapus:</strong> Akun Karyawan, Registrasi Master Wajah Biometrik (Master Face Template), Koordinat Kantor, Batas Radius Geofence, Jadwal Shift, serta semua konfigurasi sistem lainnya.</span>
                                </p>
                            </div>
                        </div>

                        {{-- Action Cards --}}
                        <div class="space-y-4">

                            {{-- Reset Today --}}
                            <div class="bg-surface-muted border border-warning/20 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="label-sm font-bold text-fg flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-warning inline-block"></span>
                                        Hapus Log Presensi Hari Ini
                                    </h4>
                                    <p class="label-xs text-fg-muted mt-1">Hapus secara permanen <strong class="text-warning">{{ $todayLogs }} log</strong> presensi hari ini ({{ now()->translatedFormat('d M Y') }}) beserta foto selfie terkait.</p>
                                </div>
                                <button
                                    wire:click="resetAttendanceToday"
                                    wire:confirm="⚠️ KONFIRMASI HAPUS LOG HARI INI\n\nAnda akan menghapus {{ $todayLogs }} data presensi hari ini beserta foto selfie terkait secara permanen.\n\nAkun karyawan dan data master biometrik Anda tetap aman.\n\nApakah Anda yakin?"
                                    wire:loading.attr="disabled"
                                    class="flex-shrink-0 btn-sm btn-warning-outline whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Log Hari Ini
                                </button>
                            </div>

                            {{-- Reset All --}}
                            <div class="bg-surface-muted border border-danger/30 rounded-xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="label-sm font-bold text-danger flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-danger inline-block"></span>
                                        Kosongkan Semua Riwayat Presensi
                                    </h4>
                                    <p class="label-xs text-fg-muted mt-1">Hapus seluruh <strong class="text-danger">{{ $totalLogs }} log presensi</strong> dari seluruh waktu, beserta {{ $totalEvents }} suspicious events dan semua foto selfie absen.</p>
                                </div>
                                <button
                                    wire:click="resetAttendanceAll"
                                    wire:confirm="🚨 KONFIRMASI HAPUS SELURUH RIWAYAT ABSENSI\n\nTindakan ini akan menghapus {{ $totalLogs }} log riwayat presensi beserta foto selfie absen secara permanen dari server.\n\n* Akun Karyawan, Registrasi Master Wajah Biometrik, Koordinat Kantor, & Pengaturan Radius tetap AMAN & tidak terpengaruh.\n\nApakah Anda yakin ingin melanjutkan?"
                                    wire:loading.attr="disabled"
                                    class="flex-shrink-0 btn-sm btn-danger-outline whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Semua Riwayat Absen
                                </button>
                            </div>

                        </div>
                    </div>
                    </div>
                @endif

            </div> <!-- Close lg:col-span-3 -->
        </div> <!-- Close grid -->

    </div>

    <!-- ========================================================== -->
    <!-- MODALS & DRAWERS -->
    <!-- ========================================================== -->



    <!-- Branch CRUD Modal -->
    <div x-data="{ open: @entangle('showBranchModal') }" x-show="open"
        class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 modal-overlay"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-lg max-h-[85vh] modal-glass rounded-xl p-6 sm:p-8 relative overflow-hidden flex flex-col">

            <div class="flex items-center justify-between mb-6 border-b border-border pb-4">
                <h3 class="heading-3 flex items-center">
                    @if ($selectedBranchId)
                        <svg class="w-5 h-5 mr-2 text-success inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Ubah Cabang Kantor
                    @else
                        <svg class="w-5 h-5 mr-2 text-success inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Daftarkan Cabang Baru
                    @endif
                </h3>
                <button @click="open = false" class="text-fg-muted hover:text-fg text-xl font-bold">×</button>
            </div>

            <form wire:submit.prevent="saveBranch"
                class="space-y-4 flex-1 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-white/10">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5">Nama Cabang</label>
                        <input wire:model="branch_name" type="text" placeholder="Contoh: Surabaya Branch" required
                            class="w-full text-xs">
                        @error('branch_name')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Kode Cabang</label>
                        <input wire:model="branch_code" type="text" placeholder="Contoh: SBY" required
                            class="w-full text-xs">
                        @error('branch_code')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block label-xs mb-1.5">Alamat Lengkap</label>
                    <textarea wire:model="branch_address" required rows="2" placeholder="Nama jalan, nomor, kota..."
                        class="w-full text-xs resize-none"></textarea>
                    @error('branch_address')
                        <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5">Latitude</label>
                        <input wire:model="branch_latitude" type="text" placeholder="Contoh: -7.257472" required
                            class="w-full text-xs">
                        @error('branch_latitude')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Longitude</label>
                        <input wire:model="branch_longitude" type="text" placeholder="Contoh: 112.752090" required
                            class="w-full text-xs">
                        @error('branch_longitude')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Radius Batas (meter)</label>
                        <input wire:model="branch_radius" type="number" required
                            class="w-full text-xs">
                        @error('branch_radius')
                            <span class="label-xs text-danger font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex items-center">
                        <button type="button" @click="$wire.branch_is_active = !$wire.branch_is_active"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="$wire.branch_is_active ? 'bg-primary' : 'bg-surface-muted'">
                            <span
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="$wire.branch_is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="ml-3 text-xs font-semibold text-fg-muted">Set Status Kantor Cabang ke
                            Aktif</span>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-border">
                    <button @click="open = false" type="button" class="btn-sm btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-sm btn-primary">
                        {{ $selectedBranchId ? 'Simpan Perubahan' : 'Daftarkan Cabang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>



</div>

</div>
