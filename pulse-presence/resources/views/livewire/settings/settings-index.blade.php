<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

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
                    class="bg-transparent lg:bg-[#121d33]/65 border-0 lg:border border-white/10 rounded-2xl p-0 lg:p-4 shadow-none lg:shadow-2xl relative overflow-hidden">
                    <div
                        class="absolute -right-16 -top-16 w-32 h-32 bg-blue-500/5 rounded-full blur-2xl pointer-events-none hidden lg:block">
                    </div>

                    <div
                        class="flex flex-row overflow-x-auto whitespace-nowrap lg:flex-col lg:space-y-1.5 lg:space-x-0 lg:overflow-x-visible lg:whitespace-normal space-x-2 p-0.5 scrollbar-none">

                        <!-- Tab 1: Parameter Keamanan -->
                        <button wire:click="$set('activeTab', 'security')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-2xl transition-all flex items-center space-x-2 text-left flex-shrink-0 {{ $activeTab === 'security' ? 'tab-active' : 'text-slate-300 hover:text-white hover:bg-white/5 bg-[#0d1527]/40 lg:bg-transparent border border-white/5 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'security' ? 'text-white' : 'text-slate-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span>Parameter Keamanan</span>
                        </button>

                        <!-- Tab 2: Jam Kerja & Lembur -->
                        <button wire:click="$set('activeTab', 'work_hours')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-2xl transition-all flex items-center space-x-2 text-left flex-shrink-0 {{ $activeTab === 'work_hours' ? 'tab-active' : 'text-slate-300 hover:text-white hover:bg-white/5 bg-[#0d1527]/40 lg:bg-transparent border border-white/5 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'work_hours' ? 'text-white' : 'text-slate-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Jam Kerja & Lembur</span>
                        </button>

                        <!-- Tab 3: Batas Izin Kerja -->
                        <button wire:click="$set('activeTab', 'permission_rules')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-2xl transition-all flex items-center space-x-2 text-left flex-shrink-0 {{ $activeTab === 'permission_rules' ? 'tab-active' : 'text-slate-300 hover:text-white hover:bg-white/5 bg-[#0d1527]/40 lg:bg-transparent border border-white/5 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'permission_rules' ? 'text-white' : 'text-slate-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>Batas Izin Kerja</span>
                        </button>

                        <!-- Tab 4: Kantor Cabang & Geofence -->
                        <button wire:click="$set('activeTab', 'branches')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-2xl transition-all flex items-center space-x-2 text-left flex-shrink-0 {{ $activeTab === 'branches' ? 'tab-active' : 'text-slate-300 hover:text-white hover:bg-white/5 bg-[#0d1527]/40 lg:bg-transparent border border-white/5 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'branches' ? 'text-white' : 'text-slate-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Kantor Cabang & Geofence</span>
                        </button>

                        <!-- Tab 5: Kredensial Biometrik -->
                        <button wire:click="$set('activeTab', 'biometrics')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-2xl transition-all flex items-center space-x-2 text-left flex-shrink-0 {{ $activeTab === 'biometrics' ? 'tab-active' : 'text-slate-300 hover:text-white hover:bg-white/5 bg-[#0d1527]/40 lg:bg-transparent border border-white/5 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'biometrics' ? 'text-white' : 'text-slate-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kredensial Biometrik</span>
                        </button>

                        <!-- Tab 6: Peran & Spatie -->
                        <button wire:click="$set('activeTab', 'roles')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-2xl transition-all flex items-center space-x-2 text-left flex-shrink-0 {{ $activeTab === 'roles' ? 'tab-active' : 'text-slate-300 hover:text-white hover:bg-white/5 bg-[#0d1527]/40 lg:bg-transparent border border-white/5 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'roles' ? 'text-white' : 'text-slate-400' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                            </svg>
                            <span>Peran & Otorisasi Spatie</span>
                        </button>

                        @if(auth()->user()->hasRole('super_admin'))
                        <!-- Tab 7: Hapus Riwayat Presensi (Super Admin Only) -->
                        <button wire:click="$set('activeTab', 'reset')" type="button"
                            class="px-3.5 py-2.5 lg:px-4 lg:py-3 label-xs font-bold rounded-xl lg:rounded-2xl transition-all flex items-center space-x-2 text-left flex-shrink-0 {{ $activeTab === 'reset' ? 'bg-rose-500/20 border border-rose-500/30 text-rose-300' : 'text-rose-400/70 hover:text-rose-300 hover:bg-rose-500/10 bg-[#0d1527]/40 lg:bg-transparent border border-rose-500/10 lg:border-0' }}">
                            <svg class="w-4 h-4 lg:w-5 lg:h-5 flex-shrink-0 {{ $activeTab === 'reset' ? 'text-rose-300' : 'text-rose-400/70' }}"
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
                    <div class="mb-4 p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl text-[11px] font-medium flex items-center gap-2 shadow-md">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl text-[11px] font-medium flex items-center gap-2 shadow-md">
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
                        class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden max-w-4xl mx-auto">
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
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Jarak maksimum yang diizinkan dari
                                        titik koordinat pusat kantor cabang.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Toleransi Akurasi GPS (meter)</label>
                                    <input wire:model="gps_margin" type="number" required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Margin akurasi telemetry GPS
                                        perangkat. Nilai lebih rendah menjamin presisi lokasi lebih tinggi.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Ambang Batas Keaktifan Wajah AI
                                        (Liveness)</label>
                                    <input wire:model="biometric_liveness_threshold" type="number" step="0.01"
                                        min="0.80" max="1.00" required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Faktor pencocokan minimal dari
                                        mesin pemindai wajah. Rekomendasi: 0.95.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Pemicu Otentikasi Perangkat (MFA)</label>
                                    <div class="mt-2.5 flex items-center">
                                        <button type="button" @click="$wire.require_mfa = !$wire.require_mfa"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                            :class="$wire.require_mfa ? 'bg-blue-600' : 'bg-white/10'">
                                            <span
                                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                                :class="$wire.require_mfa ? 'translate-x-5' : 'translate-x-0'"></span>
                                        </button>
                                        <span class="ml-3 label-sm text-slate-300">Wajibkan verifikasi MFA perangkat
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
                        class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden max-w-4xl mx-auto">
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
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Waktu kerja reguler
                                        dimulai.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Selesai Jam Kerja</label>
                                    <input wire:model="work_hour_end" type="time" required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Waktu kerja reguler
                                        berakhir.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Toleransi Keterlambatan (menit)</label>
                                    <input wire:model="grace_period" type="number" required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Durasi tambahan bebas sanksi
                                        telat.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Zona Waktu Perusahaan</label>
                                    <select wire:model="timezone" required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                        <option value="Asia/Jakarta">WIB (Asia/Jakarta)</option>
                                        <option value="Asia/Makassar">WITA (Asia/Makassar)</option>
                                        <option value="Asia/Jayapura">WIT (Asia/Jayapura)</option>
                                    </select>
                                    <span class="label-xs text-slate-500 mt-1 block">Zona waktu operasional
                                        perusahaan.</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block label-xs mb-2">Min. Lembur (Jam)</label>
                                    <input wire:model="overtime_min_hours" type="number" step="0.5" required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Minimal durasi kerja tambahan
                                        untuk lembur.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Lembur Penuh (Jam)</label>
                                    <input wire:model="overtime_full_day_hours" type="number" step="0.5"
                                        required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Minimal jam lembur untuk terhitung
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
                        class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden max-w-4xl mx-auto">
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
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Batas jam kerja yang diperbolehkan
                                        untuk izin datang terlambat.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Batas Izin Pulang Awal (Jam)</label>
                                    <input wire:model="permission_max_early_hours" type="number" step="0.5"
                                        required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Batas jam kerja yang diperbolehkan
                                        untuk izin pulang awal.</span>
                                </div>

                                <div>
                                    <label class="block label-xs mb-2">Batas Izin 1/2 Hari (Jam)</label>
                                    <input wire:model="permission_max_half_day_hours" type="number" step="0.5"
                                        required
                                        class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                    <span class="label-xs text-slate-500 mt-1 block">Batas jam kerja yang diperbolehkan
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
                <!-- TAB 4: KANTOR CABANG & GEOFENCE -->
                <!-- ========================================================== -->
                @if ($activeTab === 'branches')
                    <div class="space-y-8">
                        <!-- Kantor Cabang Aktif (Horizontal Cards) -->
                        <div
                            class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
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
                                        class="p-5 bg-[#0d1527] border border-white/5 rounded-2xl space-y-3.5 hover:border-white/10 transition-all">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="label-sm font-bold text-white uppercase">{{ $b->name }}</span>
                                            @if ($b->is_active)
                                                <span class="badge-success text-[9px] py-0.5">Aktif</span>
                                            @else
                                                <span class="badge-neutral text-[9px] py-0.5">Nonaktif</span>
                                            @endif
                                        </div>
                                        <div
                                            class="label-xs font-mono text-slate-400 space-y-1 pt-1.5 border-t border-white/5">
                                            <div class="flex justify-between"><span>Latitude:</span> <span
                                                    class="text-white">{{ $b->latitude }}</span></div>
                                            <div class="flex justify-between"><span>Longitude:</span> <span
                                                    class="text-white">{{ $b->longitude }}</span></div>
                                            <div class="flex justify-between"><span>Radius Geofence:</span> <span
                                                    class="text-blue-400 font-bold">{{ $b->radius }} meter</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Kelola Kantor Cabang (CRUD Table) -->
                        <div
                            class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
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

                            <div class="border border-white/5 rounded-2xl overflow-hidden bg-black/20">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-max text-left text-xs border-collapse">
                                        <thead>
                                            <tr
                                                class="border-b border-white/5 bg-[#0d1527]/70 label-xs font-bold uppercase">
                                                <th class="px-5 py-3.5">Nama & Kode Cabang</th>
                                                <th class="px-5 py-3.5">Alamat Kantor</th>
                                                <th class="px-5 py-3.5">Koordinat (Lat / Lng)</th>
                                                <th class="px-5 py-3.5 text-center">Batas Radius</th>
                                                <th class="px-5 py-3.5 text-center">Status</th>
                                                <th class="px-5 py-3.5 text-right">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-white/5 font-medium text-slate-300">
                                            @forelse($branches as $b)
                                                <tr class="hover:bg-white/5 transition-colors">
                                                    <td class="px-5 py-4">
                                                        <span
                                                            class="label-sm font-bold text-white block">{{ $b->name }}</span>
                                                        <span
                                                            class="label-xs font-mono text-slate-500 uppercase block tracking-wider mt-0.5">KODE:
                                                            {{ $b->code }}</span>
                                                    </td>

                                                    <td class="px-5 py-4 label-sm text-slate-300 max-w-xs truncate">
                                                        {{ $b->address }}
                                                    </td>

                                                    <td class="px-5 py-4 label-xs font-mono text-slate-400">
                                                        <div>Lat: {{ $b->latitude }}</div>
                                                        <div>Lng: {{ $b->longitude }}</div>
                                                    </td>

                                                    <td class="px-5 py-4 text-center label-sm font-bold text-blue-400">
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
                                                        class="px-5 py-12 text-center text-slate-500 font-bold uppercase label-xs">
                                                        Tidak ada data kantor cabang yang terdaftar di database.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB 5: KREDENSIAL BIOMETRIK KARYAWAN -->
                <!-- ========================================================== -->
                @if ($activeTab === 'biometrics')
                    <div
                        class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                        <div
                            class="absolute -right-16 -top-16 w-44 h-44 bg-blue-500/5 rounded-full blur-3xl pointer-events-none">
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                            <div>
                                <h3 class="heading-3">Dasbor Kredensial Biometrik Karyawan</h3>
                                <p class="label-sm">Pantau status biometrik karyawan, lakukan audit keamanan, dan hapus
                                    template terdaftar</p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button wire:click="$set('showRegisterModal', true)" type="button"
                                    class="btn-sm btn-primary py-2">
                                    <svg class="w-4 h-4 mr-1.5 inline-block" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4" />
                                    </svg> Karyawan
                                </button>
                                <span class="badge-success">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-1.5 animate-pulse"></span>
                                    Edge Vision Aktif
                                </span>
                            </div>
                        </div>

                        <!-- Enrollment Statistics Widget -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                            <div
                                class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4 sm:p-5 relative overflow-hidden">
                                <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-blue-500/5 rounded-full blur-xl">
                                </div>
                                <div class="label-xs text-slate-400">Total Tenaga Kerja</div>
                                <div class="heading-value-white mt-1">{{ $stats['total'] }}</div>
                                <div class="label-xs text-slate-500 mt-0.5">Akun personel terdaftar</div>
                            </div>

                            <div
                                class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4 sm:p-5 relative overflow-hidden">
                                <div
                                    class="absolute -right-6 -bottom-6 w-16 h-16 bg-emerald-500/5 rounded-full blur-xl">
                                </div>
                                <div class="label-xs text-slate-400">Identitas Terverifikasi</div>
                                <div class="heading-value-white text-emerald-400 mt-1">{{ $stats['enrolled'] }}</div>
                                <div class="label-xs text-slate-500 mt-0.5">Kunci biometrik aman aktif</div>
                            </div>

                            <div
                                class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4 sm:p-5 relative overflow-hidden">
                                <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-amber-500/5 rounded-full blur-xl">
                                </div>
                                <div class="label-xs text-slate-400">Menunggu Registrasi Wajah</div>
                                <div class="heading-value-white text-amber-500 mt-1">{{ $stats['pending'] }}</div>
                                <div class="label-xs text-slate-500 mt-0.5">Dibatasi dari absensi mandiri</div>
                            </div>

                            <div
                                class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4 sm:p-5 relative overflow-hidden">
                                <div
                                    class="absolute -right-6 -bottom-6 w-16 h-16 bg-indigo-500/5 rounded-full blur-xl">
                                </div>
                                <div class="label-xs text-indigo-300">Tingkat Kepatuhan Kunci</div>
                                <div class="heading-value-white text-indigo-400 mt-1">{{ $stats['rate'] }}%</div>
                                <div class="w-full bg-white/5 h-1.5 rounded-full mt-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-full rounded-full"
                                        style="width: {{ $stats['rate'] }}%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Search, Filter & Audit controls -->
                        <div
                            class="bg-[#0d1527]/80 border border-white/5 rounded-2xl p-4 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5">Cari Nama / ID Karyawan</label>
                                <div class="relative">
                                    <input wire:model.live.debounce.300ms="search" type="text"
                                        placeholder="Masukkan nama, email, atau ID..."
                                        class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-600">
                                    @if ($search)
                                        <button @click="$wire.set('search', '')"
                                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white text-xs">×</button>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Filter Status Biometrik</label>
                                <select wire:model.live="statusFilter"
                                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="all" class="bg-[#121d33]">Semua Karyawan</option>
                                    <option value="registered" class="bg-[#121d33]">Wajah Terverifikasi (Aktif)
                                    </option>
                                    <option value="pending" class="bg-[#121d33]">Belum Registrasi Wajah</option>
                                </select>
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Penempatan Kantor Cabang</label>
                                <select wire:model.live="branchFilter"
                                    class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="all" class="bg-[#121d33]">Semua Cabang</option>
                                    @foreach ($branches as $b)
                                        <option value="{{ $b->id }}" class="bg-[#121d33]">{{ $b->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Main Ledger Table -->
                        <div class="border border-white/5 rounded-2xl overflow-hidden bg-black/20">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-max text-left text-xs border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-white/5 bg-[#0d1527]/70 label-xs font-bold uppercase">
                                            <th class="px-5 py-3.5">Karyawan</th>
                                            <th class="px-5 py-3.5">Cabang & Mode Kerja</th>
                                            <th class="px-5 py-3.5 text-center">Status Registrasi Wajah</th>
                                            <th class="px-5 py-3.5 text-center">Sudut Telemetri</th>
                                            <th class="px-5 py-3.5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5 font-medium text-slate-300">
                                        @forelse($users as $u)
                                            <tr class="hover:bg-white/5 transition-colors">
                                                <td class="px-5 py-4">
                                                    <div class="flex items-center space-x-3.5">
                                                        @if ($u->hasRegisteredFace())
                                                            <div
                                                                class="relative w-9 h-9 rounded-xl border border-blue-500/20 overflow-hidden bg-slate-800 flex-shrink-0">
                                                                <img src="{{ $u->getMasterFaceUrl() }}"
                                                                    class="w-full h-full object-cover transform scaleX(-1)">
                                                            </div>
                                                        @else
                                                            <div
                                                                class="w-9 h-9 rounded-xl bg-slate-800/80 border border-white/5 flex items-center justify-center font-bold text-slate-400 flex-shrink-0">
                                                                {{ substr($u->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <span
                                                                class="label-sm font-bold text-white block">{{ $u->name }}</span>
                                                            <span
                                                                class="label-xs font-mono text-slate-500 block mt-0.5 font-semibold">#{{ $u->employee_id }}
                                                                · {{ strtolower($u->email) }}</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-5 py-4">
                                                    <span
                                                        class="label-sm font-bold text-white block">{{ $u->branch->name ?? 'Belum Ditentukan' }}</span>
                                                    <span class="badge-rect-info mt-1 inline-block">
                                                        {{ strtoupper($u->work_mode) }}
                                                    </span>
                                                </td>

                                                <td class="px-5 py-4 text-center">
                                                    @if ($u->is_registered)
                                                        <span class="badge-success">
                                                            <span
                                                                class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span>
                                                            Kunci Aktif
                                                        </span>
                                                    @else
                                                        <span class="badge-danger">
                                                            <span
                                                                class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span>
                                                            Belum Terdaftar
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="px-5 py-4 text-center font-mono text-xs">
                                                    @if ($u->is_registered)
                                                        <div class="flex items-center justify-center space-x-1">
                                                            <span
                                                                class="label-xs font-bold text-blue-400 mr-1.5">{{ $u->registered_angles }}/3</span>
                                                            <span
                                                                class="w-2 h-2 rounded-full {{ $u->registered_angles >= 1 ? 'bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.6)]' : 'bg-white/10' }}"
                                                                title="Sudut Tengah"></span>
                                                            <span
                                                                class="w-2 h-2 rounded-full {{ $u->registered_angles >= 2 ? 'bg-indigo-400 shadow-[0_0_8px_rgba(129,140,248,0.6)]' : 'bg-white/10' }}"
                                                                title="Profil Kiri"></span>
                                                            <span
                                                                class="w-2 h-2 rounded-full {{ $u->registered_angles >= 3 ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)]' : 'bg-white/10' }}"
                                                                title="Profil Kanan"></span>
                                                        </div>
                                                    @else
                                                        <span class="label-xs text-slate-600">0 Sudut Terkunci</span>
                                                    @endif
                                                </td>

                                                <td class="px-5 py-4 text-right">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <button wire:click="openUserEditModal({{ $u->id }})"
                                                            class="btn-primary btn-xs shadow-sm">
                                                            <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>Kelola
                                                        </button>

                                                        @if ($u->is_registered)
                                                            <button wire:click="revokeBiometrics({{ $u->id }})"
                                                                wire:confirm="Apakah Anda yakin ingin menghapus dan membatalkan kunci biometrik untuk {{ $u->name }}? Mereka akan langsung diblokir dari sistem absensi."
                                                                class="btn-danger-outline btn-xs">
                                                                <svg class="w-3 h-3 mr-1" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                                Hapus Wajah
                                                            </button>
                                                        @else
                                                            <div class="flex items-center justify-end"
                                                                x-data="{ uploading: false }">
                                                                <label
                                                                    class="btn-primary-outline btn-xs relative cursor-pointer">
                                                                    <span x-show="!uploading"><svg
                                                                            class="w-3.5 h-3.5 mr-1 inline-block"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2.5"
                                                                                d="M12 4v16m8-8H4" />
                                                                        </svg>Wajah</span>
                                                                    <span x-show="uploading"
                                                                        class="animate-pulse">...</span>
                                                                    <input type="file" accept="image/*"
                                                                        class="absolute inset-0 opacity-0 cursor-pointer w-full h-full"
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
                                                <td colspan="5"
                                                    class="px-5 py-12 text-center text-slate-500 font-bold uppercase tracking-wider">
                                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-600" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Tidak ada data karyawan yang cocok dengan kriteria pencarian Anda.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ========================================================== -->
                <!-- TAB 3: PERAN & OTORISASI SPATIE -->
                <!-- ========================================================== -->
                @if ($activeTab === 'roles')
                    <div
                        class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                        <div class="mb-8">
                            <h3 class="heading-3">Matriks Otorisasi Fitur (Spatie Rules)</h3>
                            <p class="label-sm">Batasi dan tentukan hak akses kapabilitas sistem secara detail untuk
                                masing-masing peran karyawan</p>

                            <div
                                class="mt-4 p-4 bg-[#0d1527]/90 border border-amber-500/20 text-amber-400 rounded-2xl label-xs font-bold flex items-start gap-2 max-w-3xl">
                                <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-amber-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <span class="label-xs block mb-1">Peringatan Keamanan Administratif</span>
                                    Pemberian hak akses baru atau penolakan kapabilitas akan berdampak langsung seketika
                                    pada sesi aktif karyawan.
                                    Demi menjaga keamanan struktur otorisasi sistem, hanya akun Super Administrator yang
                                    diizinkan memodifikasi Spatie Matrix ini.
                                </div>
                            </div>
                        </div>

                        <div class="border border-white/5 rounded-2xl overflow-hidden bg-black/20">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-max text-left text-xs border-collapse">
                                    <thead>
                                        <tr
                                            class="border-b border-white/5 bg-[#0d1527]/70 label-xs font-bold uppercase">
                                            <th class="px-5 py-4">Kunci Kapabilitas / Izin Sistem</th>
                                            @foreach ($roles as $r)
                                                <th class="px-5 py-4 text-center font-bold">
                                                    <span
                                                        class="label-sm font-bold text-white block">{{ strtoupper(str_replace('_', ' ', $r->name)) }}</span>
                                                    <span
                                                        class="label-xs text-slate-500 font-bold uppercase mt-0.5">{{ $r->permissions->count() }}
                                                        Izin</span>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5 font-medium text-slate-300">
                                        @foreach ($allPermissions as $p)
                                            <tr class="hover:bg-white/5 transition-colors">
                                                <td class="px-5 py-3">
                                                    <span
                                                        class="label-sm font-bold text-white block">{{ strtoupper(str_replace('view ', 'Lihat ', str_replace('create ', 'Buat ', str_replace('edit ', 'Ubah ', str_replace('delete ', 'Hapus ', str_replace('approve ', 'Setujui ', $p->name)))))) }}</span>
                                                    <span
                                                        class="label-xs text-slate-500 uppercase tracking-widest mt-0.5">KUNCI
                                                        TEKNIS: {{ $p->name }}</span>
                                                </td>

                                                @foreach ($roles as $r)
                                                    <td class="px-5 py-3 text-center">
                                                        <button
                                                            wire:click="togglePermission({{ $r->id }}, '{{ $p->name }}')"
                                                            type="button"
                                                            class="relative inline-flex items-center focus:outline-none cursor-pointer">
                                                            <span
                                                                class="w-5 h-5 rounded-md border flex items-center justify-center transition-all {{ $r->hasPermissionTo($p->name) ? 'bg-blue-500/20 border-blue-400 text-blue-400 shadow-[0_0_8px_rgba(59,130,246,0.3)]' : 'bg-white/5 border-white/10 text-transparent' }}">
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
                    <div class="bg-[#121d33]/65 backdrop-blur-xl border border-rose-500/20 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                        <div class="absolute -right-16 -top-16 w-48 h-48 bg-rose-500/5 rounded-full blur-3xl pointer-events-none"></div>

                        {{-- Header --}}
                        <div class="mb-8">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-9 h-9 rounded-xl bg-rose-500/15 border border-rose-500/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                                <h3 class="heading-3 text-rose-300">Hapus Riwayat & Foto Presensi</h3>
                            </div>
                            <p class="label-sm text-slate-400 ml-12">Hapus secara permanen data riwayat kehadiran karyawan beserta foto selfie yang diunggah saat melakukan absen. Tindakan ini tidak dapat dibatalkan.</p>
                        </div>

                        {{-- Stat Cards --}}
                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="bg-[#0d1527]/80 border border-white/5 rounded-2xl p-4 text-center">
                                <div class="text-2xl font-black text-white">{{ $totalLogs }}</div>
                                <div class="label-xs text-slate-500 mt-1">Total Log Presensi</div>
                            </div>
                            <div class="bg-[#0d1527]/80 border border-amber-500/10 rounded-2xl p-4 text-center">
                                <div class="text-2xl font-black text-amber-400">{{ $todayLogs }}</div>
                                <div class="label-xs text-slate-500 mt-1">Log Hari Ini</div>
                            </div>
                            <div class="bg-[#0d1527]/80 border border-white/5 rounded-2xl p-4 text-center">
                                <div class="text-2xl font-black text-rose-400">{{ $totalEvents }}</div>
                                <div class="label-xs text-slate-500 mt-1">Suspicious Events</div>
                            </div>
                        </div>

                        {{-- Warning Banner --}}
                        <div class="mb-6 p-4 bg-[#0c1322] border border-rose-500/20 rounded-2xl flex items-start gap-3">
                            <svg class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="label-xs font-bold text-rose-300 mb-1">Informasi & Batasan Tindakan</p>
                                <p class="label-xs text-slate-400 leading-relaxed">
                                    Tindakan penghapusan di bawah <strong class="text-rose-300">HANYA</strong> akan menghapus record log absensi (absen masuk/keluar) beserta file foto selfie yang diambil saat absen dari penyimpanan server. 
                                    <br><span class="text-slate-300 mt-1 block">🔒 <strong class="text-white">Data yang Aman & TIDAK akan Terhapus:</strong> Akun Karyawan, Registrasi Master Wajah Biometrik (Master Face Template), Koordinat Kantor, Batas Radius Geofence, Jadwal Shift, serta semua konfigurasi sistem lainnya.</span>
                                </p>
                            </div>
                        </div>

                        {{-- Action Cards --}}
                        <div class="space-y-4">

                            {{-- Reset Today --}}
                            <div class="bg-[#0d1527]/60 border border-amber-500/20 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="label-sm font-bold text-white flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-amber-400 inline-block"></span>
                                        Hapus Log Presensi Hari Ini
                                    </h4>
                                    <p class="label-xs text-slate-400 mt-1">Hapus secara permanen <strong class="text-amber-400">{{ $todayLogs }} log</strong> presensi hari ini ({{ now()->translatedFormat('d M Y') }}) beserta foto selfie terkait.</p>
                                </div>
                                <button
                                    wire:click="resetAttendanceToday"
                                    wire:confirm="⚠️ KONFIRMASI HAPUS LOG HARI INI\n\nAnda akan menghapus {{ $todayLogs }} data presensi hari ini beserta foto selfie terkait secara permanen.\n\nAkun karyawan dan data master biometrik Anda tetap aman.\n\nApakah Anda yakin?"
                                    wire:loading.attr="disabled"
                                    class="flex-shrink-0 px-5 py-2.5 bg-amber-500/20 hover:bg-amber-500/30 border border-amber-500/40 text-amber-300 font-bold text-xs rounded-xl transition-all flex items-center gap-2 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Log Hari Ini
                                </button>
                            </div>

                            {{-- Reset All --}}
                            <div class="bg-[#0d1527]/60 border border-rose-500/30 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <h4 class="label-sm font-bold text-rose-300 flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-rose-400 inline-block animate-pulse"></span>
                                        Kosongkan Semua Riwayat Presensi
                                    </h4>
                                    <p class="label-xs text-slate-400 mt-1">Hapus seluruh <strong class="text-rose-400">{{ $totalLogs }} log presensi</strong> dari seluruh waktu, beserta {{ $totalEvents }} suspicious events dan semua foto selfie absen.</p>
                                </div>
                                <button
                                    wire:click="resetAttendanceAll"
                                    wire:confirm="🚨 KONFIRMASI HAPUS SELURUH RIWAYAT ABSENSI\n\nTindakan ini akan menghapus {{ $totalLogs }} log riwayat presensi beserta foto selfie absen secara permanen dari server.\n\n* Akun Karyawan, Registrasi Master Wajah Biometrik, Koordinat Kantor, & Pengaturan Radius tetap AMAN & tidak terpengaruh.\n\nApakah Anda yakin ingin melanjutkan?"
                                    wire:loading.attr="disabled"
                                    class="flex-shrink-0 px-5 py-2.5 bg-rose-500/20 hover:bg-rose-500/30 border border-rose-500/40 text-rose-300 font-bold text-xs rounded-xl transition-all flex items-center gap-2 whitespace-nowrap">
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

    <div x-data="{ open: @entangle('showRegisterModal') }" x-show="open"
        class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 bg-black/40 backdrop-blur-md"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-lg max-h-[85vh] bg-[#121d33] border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden flex flex-col">
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/5 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <h3 class="heading-3 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 inline-block text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Registrasi Karyawan Baru
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-white text-xl font-bold">×</button>
            </div>

            <form wire:submit.prevent="registerUser"
                class="space-y-4 flex-1 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-white/10">
                <div>
                    <label class="block label-xs mb-1.5">Nama Lengkap Karyawan</label>
                    <input wire:model="new_name" type="text" placeholder="Contoh: Budi Santoso" required
                        class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-600">
                    @error('new_name')
                        <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block label-xs mb-1.5">Email Kantor</label>
                    <input wire:model="new_email" type="email" placeholder="Contoh: budi.santoso@perusahaan.com"
                        required
                        class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-600">
                    @error('new_email')
                        <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5">Nomor Induk Karyawan (EMP ID)</label>
                        <input wire:model="new_employee_id" type="text" placeholder="Contoh: EMP1042" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all placeholder:text-slate-600">
                        @error('new_employee_id')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Kata Sandi Akun</label>
                        <input wire:model="new_password" type="password" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('new_password')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5">Cabang Kantor</label>
                        <select wire:model="new_branch_id" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="">Pilih Cabang</option>
                            @foreach ($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                        @error('new_branch_id')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Mode Kerja</label>
                        <select wire:model="new_work_mode" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="wfo">WFO (Di Kantor)</option>
                            <option value="wfh">WFH (Di Rumah)</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        @error('new_work_mode')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Peran Sistem</label>
                        <select wire:model="new_role" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                            <option value="employee">Employee (Karyawan)</option>
                            <option value="manager">Manager</option>
                            <option value="hr_admin">HR Admin</option>
                        </select>
                        @error('new_role')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-white/5">
                    <button @click="open = false" type="button" class="btn-sm btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-sm btn-primary">
                        Buat Akun Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Branch CRUD Modal -->
    <div x-data="{ open: @entangle('showBranchModal') }" x-show="open"
        class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 bg-black/40 backdrop-blur-md"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-lg max-h-[85vh] bg-[#121d33] border border-white/10 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden flex flex-col">
            <div
                class="absolute -right-16 -top-16 w-36 h-36 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <h3 class="heading-3 flex items-center">
                    @if ($selectedBranchId)
                        <svg class="w-5 h-5 mr-2 text-emerald-400 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Ubah Cabang Kantor
                    @else
                        <svg class="w-5 h-5 mr-2 text-emerald-400 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Daftarkan Cabang Baru
                    @endif
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-white text-xl font-bold">×</button>
            </div>

            <form wire:submit.prevent="saveBranch"
                class="space-y-4 flex-1 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-white/10">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5">Nama Cabang</label>
                        <input wire:model="branch_name" type="text" placeholder="Contoh: Surabaya Branch" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('branch_name')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Kode Cabang</label>
                        <input wire:model="branch_code" type="text" placeholder="Contoh: SBY" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('branch_code')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block label-xs mb-1.5">Alamat Lengkap</label>
                    <textarea wire:model="branch_address" required rows="2" placeholder="Nama jalan, nomor, kota..."
                        class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all resize-none"></textarea>
                    @error('branch_address')
                        <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block label-xs mb-1.5">Latitude</label>
                        <input wire:model="branch_latitude" type="text" placeholder="Contoh: -7.257472" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('branch_latitude')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Longitude</label>
                        <input wire:model="branch_longitude" type="text" placeholder="Contoh: 112.752090" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('branch_longitude')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block label-xs mb-1.5">Radius Batas (meter)</label>
                        <input wire:model="branch_radius" type="number" required
                            class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                        @error('branch_radius')
                            <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex items-center">
                        <button type="button" @click="$wire.branch_is_active = !$wire.branch_is_active"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="$wire.branch_is_active ? 'bg-blue-600' : 'bg-white/10'">
                            <span
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="$wire.branch_is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="ml-3 text-xs font-semibold text-slate-300">Set Status Kantor Cabang ke
                            Aktif</span>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-white/5">
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

    <!-- Employee Detail & Edit Modal -->
    <div x-data="{ open: @entangle('showUserEditModal') }" x-show="open"
        class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4 bg-black/40 backdrop-blur-md"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-3xl max-h-[85vh] bg-[#121d33]/95 border border-white/15 rounded-2xl p-6 sm:p-8 shadow-2xl relative overflow-hidden animate-fade-in flex flex-col">
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/5 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="heading-3"><svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>Kelola Akun Karyawan</h3>
                    <p class="label-xs text-slate-400 mt-0.5">Ubah rincian profil, penempatan cabang, biometrik wajah,
                        perangkat terpercaya, dan peran</p>
                </div>
                <button @click="open = false" class="text-slate-400 hover:text-white text-xl font-bold">×</button>
            </div>

            @php
                $selectedUser = $selectedUserId ? \App\Models\User::find($selectedUserId) : null;
            @endphp

            <form wire:submit.prevent="saveUser"
                class="space-y-6 flex-1 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-white/10">
                <!-- Two Column Layout: Face + Details -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

                    <!-- Left Column: Master Face Photos (3 Angles) -->
                    <div class="md:col-span-4 flex flex-col items-center">
                        <span class="block label-xs mb-2.5 text-center">Baseline Biometrik</span>

                        @if ($selectedUser && $selectedUser->hasRegisteredFace())
                            <div class="grid grid-cols-3 gap-2 w-full max-w-[220px]">
                                @foreach (['front' => 'Depan', 'left' => 'Kiri', 'right' => 'Kanan'] as $angle => $label)
                                    <div class="flex flex-col items-center">
                                        <div class="relative w-full aspect-square bg-[#0d1527] border border-white/10 rounded-xl overflow-hidden flex items-center justify-center shadow-md">
                                            @if ($selectedUser->getFaceAngleUrl($angle))
                                                <img src="{{ $selectedUser->getFaceAngleUrl($angle) }}"
                                                    class="w-full h-full object-cover {{ $angle === 'front' ? 'transform scaleX(-1)' : '' }}">
                                                <div class="absolute inset-x-0 h-[1px] bg-blue-500/60 shadow-[0_0_6px_#3b82f6] pointer-events-none z-10"
                                                    style="animation: scanLine 3s linear infinite;"></div>
                                            @else
                                                <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="label-xs text-slate-500 mt-1.5 text-center">{{ $label }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 text-center">
                                <span class="badge-rect-success">
                                    <svg class="w-3.5 h-3.5 mr-1 text-emerald-400 inline-block" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>{{ $selectedUser->registered_angles }}/3 Sudut Terdaftar
                                </span>
                            </div>
                        @else
                            <div
                                class="relative w-full aspect-square max-w-[180px] bg-[#0d1527] border border-white/10 rounded-2xl overflow-hidden flex items-center justify-center shadow-md">
                                <div class="text-center text-slate-500 p-4">
                                    <svg class="w-10 h-10 mx-auto text-slate-600 mb-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="label-xs text-slate-500 block">Wajah Belum Terdaftar</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Personal & Employee details -->
                    <div class="md:col-span-8 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5">Nama Lengkap</label>
                                <input wire:model="edit_name" type="text" required
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                @error('edit_name')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Nomor Induk / ID Karyawan</label>
                                <input wire:model="edit_employee_id" type="text" required
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                @error('edit_employee_id')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5">Alamat Email</label>
                                <input wire:model="edit_email" type="email" required
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                @error('edit_email')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Ubah Sandi Baru (Opsional)</label>
                                <input wire:model="edit_password" type="password"
                                    placeholder="Kosongkan jika tidak diubah"
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                @error('edit_password')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5">Tanggal Lahir</label>
                                <input wire:model="edit_date_of_birth" type="date"
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                @error('edit_date_of_birth')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Tanggal Masuk Kerja (Joined)</label>
                                <input wire:model="edit_joined_at" type="date"
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                @error('edit_joined_at')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <label class="block label-xs mb-1.5">Cabang Kantor</label>
                                <select wire:model="edit_branch_id" required
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('edit_branch_id')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Mode Kerja</label>
                                <select wire:model="edit_work_mode" required
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="wfo">WFO (Di Kantor)</option>
                                    <option value="wfh">WFH (Di Rumah)</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                                @error('edit_work_mode')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Peran Sistem</label>
                                <select wire:model="edit_role" required
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all cursor-pointer">
                                    <option value="employee">Employee</option>
                                    <option value="manager">Manager</option>
                                    <option value="hr_admin">HR Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                                @error('edit_role')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block label-xs mb-1.5">Kuota Cuti Tahunan</label>
                                <input wire:model="edit_annual_leave_quota" type="number" required min="0" max="100"
                                    class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all">
                                @error('edit_annual_leave_quota')
                                    <span class="label-xs text-rose-400 font-bold block mt-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trusted Devices / Fingerprint Section -->
                <div class="border-t border-white/10 pt-4">
                    <span class="block label-xs mb-2.5"><svg class="w-5 h-5 mr-2 text-slate-400 inline-block"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>Telemetri Perangkat & Browser Karyawan</span>

                    @if (empty($userDevices))
                        <div
                            class="bg-[#0d1527]/50 border border-white/5 rounded-2xl p-4 text-center label-xs font-bold text-slate-500">
                            Tidak ada perangkat yang terdaftar atau digunakan karyawan ini.
                        </div>
                    @else
                        <div
                            class="max-h-[140px] overflow-y-auto space-y-2 pr-1.5 divide-y divide-white/5 bg-[#0d1527]/40 border border-white/5 rounded-2xl p-3 text-xs">
                            @foreach ($userDevices as $index => $device)
                                <div class="flex justify-between items-center pt-2 {{ $index === 0 ? 'pt-0' : '' }}">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center space-x-2">
                                            <span
                                                class="label-sm font-bold text-white">{{ $device['browser'] ?? 'Browser' }}
                                                on {{ $device['os'] ?? 'OS' }}</span>
                                            @if ($device['trusted'])
                                                <span class="badge-rect-success">Trusted</span>
                                            @else
                                                <span class="badge-rect-danger">Belum Disetujui</span>
                                            @endif
                                        </div>
                                        <div class="label-xs font-mono text-slate-500 tracking-wider">
                                            Hash: {{ substr($device['device_hash'], 0, 12) }}... · Platform:
                                            {{ $device['platform'] ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right label-xs text-slate-400">
                                            <span>Terakhir absensi:</span>
                                            <span class="block label-sm font-bold text-blue-400 mt-0.5">
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
                <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                    <div class="flex items-center">
                        <button type="button" @click="$wire.edit_is_active = !$wire.edit_is_active"
                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                            :class="$wire.edit_is_active ? 'bg-blue-600' : 'bg-white/10'">
                            <span
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="$wire.edit_is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="ml-3 label-sm text-slate-300">Akun Karyawan Aktif</span>
                    </div>

                    <button type="button" wire:click="deleteUser({{ $selectedUserId ?? 0 }})"
                        wire:confirm="PERINGATAN KERAS! Apakah Anda benar-benar yakin ingin menghapus permanen akun karyawan ini? Semua riwayat absensi dan data terkait akan dihapus secara permanen dari basis data."
                        class="btn-danger-outline btn-sm">
                        <svg class="w-3.5 h-3.5 mr-1 inline-block" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>Hapus Akun Karyawan
                    </button>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-white/5">
                    <button @click="open = false" type="button" class="btn-sm btn-secondary">
                        Batal
                    </button>
                    <button type="submit" class="btn-sm btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

</div>
