<div class="py-8 min-h-screen text-slate-100 bg-transparent">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight font-display">Kontrol & Parameter Sistem</h1>
                <p class="mt-1 text-sm text-slate-400 font-medium">Konfigurasi profil keamanan global, margin geofence, dan skor risiko biometrik</p>
            </div>
            
            <!-- Tab Controls -->
            <div class="flex bg-[#0d1527]/90 border border-white/5 p-1 rounded-2xl">
                <button wire:click="$set('activeTab', 'security')" type="button" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center space-x-1.5 {{ $activeTab === 'security' ? 'bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow' : 'text-slate-400 hover:text-white' }}">
                    <span>🛡️ Keamanan & Biometrik</span>
                </button>
                <button wire:click="$set('activeTab', 'branches')" type="button" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center space-x-1.5 {{ $activeTab === 'branches' ? 'bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow' : 'text-slate-400 hover:text-white' }}">
                    <span>🏢 Kelola Kantor Cabang</span>
                </button>
                <button wire:click="$set('activeTab', 'roles')" type="button" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl transition-all flex items-center space-x-1.5 {{ $activeTab === 'roles' ? 'bg-gradient-to-r from-blue-600 to-sky-500 text-white shadow' : 'text-slate-400 hover:text-white' }}">
                    <span>🔑 Peran & Spatie</span>
                </button>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl text-xs font-bold flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2.5 flex-shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4.5 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl text-xs font-bold flex items-center shadow-lg">
                <svg class="w-5 h-5 mr-2.5 flex-shrink-0 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================================== -->
        <!-- TAB 1: KEAMANAN & BIOMETRIK -->
        <!-- ========================================================== -->
        @if($activeTab === 'security')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: High Admin Parameters Panel -->
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-2">
                    <h3 class="text-lg font-bold text-white tracking-tight font-display mb-6">Ambang Batas Pengaman Keamanan</h3>
                    
                    <form wire:submit.prevent="saveSettings" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Radius Geofence Maksimum (meter)</label>
                                <input wire:model="radius" type="number" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Jarak maksimum yang diizinkan dari titik koordinat pusat kantor cabang.</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Toleransi Akurasi GPS (meter)</label>
                                <input wire:model="gps_margin" type="number" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Margin akurasi telemetry GPS perangkat. Nilai lebih rendah menjamin presisi lokasi lebih tinggi.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Ambang Batas Keaktifan Wajah AI (Liveness)</label>
                                <input wire:model="biometric_liveness_threshold" type="number" step="0.01" min="0.80" max="1.00" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Faktor pencocokan minimal dari mesin pemindai wajah. Rekomendasi: 0.95.</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Pemicu Otentikasi Perangkat (MFA)</label>
                                <div class="mt-2.5 flex items-center">
                                    <button type="button" @click="$wire.require_mfa = !$wire.require_mfa" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="$wire.require_mfa ? 'bg-sky-500' : 'bg-white/10'">
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.require_mfa ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                    <span class="ml-3 text-xs font-semibold text-slate-300">Wajibkan verifikasi MFA perangkat saat absen</span>
                                </div>
                            </div>
                        </div>

                        <!-- Section Divider -->
                        <div class="border-t border-white/5 pt-6 mt-6">
                            <h4 class="text-sm font-bold text-white tracking-tight font-display mb-4">⏰ Kebijakan Jam Kerja & Lembur Otomatis</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Mulai Jam Kerja</label>
                                <input wire:model="work_hour_start" type="time" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Waktu kerja reguler dimulai.</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Selesai Jam Kerja</label>
                                <input wire:model="work_hour_end" type="time" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Waktu kerja reguler berakhir.</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Toleransi Keterlambatan (menit)</label>
                                <input wire:model="grace_period" type="number" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Durasi tambahan bebas sanksi telat.</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Min. Lembur (Jam)</label>
                                <input wire:model="overtime_min_hours" type="number" step="0.5" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Minimal durasi kerja tambahan untuk lembur.</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Lembur Penuh (Jam)</label>
                                <input wire:model="overtime_full_day_hours" type="number" step="0.5" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Minimal jam lembur untuk terhitung penuh.</span>
                            </div>
                        </div>

                        <!-- Permission Rules Section Divider -->
                        <div class="border-t border-white/5 pt-6 mt-6">
                            <h4 class="text-sm font-bold text-white tracking-tight font-display mb-4">📋 Batas Toleransi Durasi Izin Kerja</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Batas Izin Telat (Jam)</label>
                                <input wire:model="permission_max_late_hours" type="number" step="0.5" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Batas jam kerja yang diperbolehkan untuk izin datang terlambat.</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Batas Izin Pulang Awal (Jam)</label>
                                <input wire:model="permission_max_early_hours" type="number" step="0.5" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Batas jam kerja yang diperbolehkan untuk izin pulang awal.</span>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Batas Izin 1/2 Hari (Jam)</label>
                                <input wire:model="permission_max_half_day_hours" type="number" step="0.5" required class="w-full bg-[#0d1527]/90 border border-white/10 rounded-2xl px-4 py-3 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                <span class="text-[9px] text-slate-500 mt-1 block">Batas jam kerja yang diperbolehkan untuk izin setengah hari.</span>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-6 py-3.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-2xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-button-primary hover:translate-y-[-1px] transition-all cursor-pointer">
                                Terapkan Konfigurasi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Office Branch coordinates list -->
                <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden lg:col-span-1">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-white tracking-tight font-display">Kantor Cabang Aktif</h3>
                        <span class="text-xs font-semibold text-slate-400">Total: {{ $branches->count() }}</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($branches as $b)
                            <div class="p-4 bg-[#0d1527] border border-white/5 rounded-2xl space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">{{ $b->name }}</span>
                                    <span class="text-[9px] font-bold {{ $b->is_active ? 'text-sky-400 bg-sky-500/10 border-sky-500/20' : 'text-slate-500 bg-white/5 border-white/10' }} px-2 py-0.5 rounded border">
                                        {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                <div class="text-[10px] font-mono text-slate-400 space-y-0.5">
                                    <div>Lat: {{ $b->latitude }}</div>
                                    <div>Lng: {{ $b->longitude }}</div>
                                    <div>Radius: {{ $b->radius }}m</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- HR Biometric Identity Management Dashboard Section -->
            <div class="mt-8 bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-44 h-44 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-xl font-extrabold text-white tracking-tight font-display">Dasbor Kredensial Biometrik Karyawan</h3>
                        <p class="text-xs text-slate-400 font-medium">Pantau status biometrik karyawan, lakukan audit keamanan, dan hapus template terdaftar</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button wire:click="$set('showRegisterModal', true)" type="button" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-700 hover:to-sky-600 text-white text-[11px] font-black uppercase tracking-wider rounded-xl shadow-md hover:translate-y-[-1px] transition-all cursor-pointer">
                            ➕ Daftarkan Karyawan
                        </button>
                        <span class="inline-flex items-center px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[10px] font-bold uppercase tracking-wider text-emerald-400">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full mr-1.5 animate-pulse"></span>
                            Edge Vision Aktif
                        </span>
                    </div>
                </div>

                <!-- Enrollment Statistics Widget -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4.5 relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-blue-500/5 rounded-full blur-xl"></div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Tenaga Kerja</div>
                        <div class="text-2xl font-black text-white font-display mt-1">{{ $stats['total'] }}</div>
                        <div class="text-[9px] text-slate-500 mt-0.5">Akun personel terdaftar</div>
                    </div>
                    
                    <div class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4.5 relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-emerald-500/5 rounded-full blur-xl"></div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Identitas Terverifikasi</div>
                        <div class="text-2xl font-black text-emerald-400 font-display mt-1">{{ $stats['enrolled'] }}</div>
                        <div class="text-[9px] text-slate-500 mt-0.5">Kunci biometrik aman aktif</div>
                    </div>

                    <div class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4.5 relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-amber-500/5 rounded-full blur-xl"></div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menunggu Registrasi Wajah</div>
                        <div class="text-2xl font-black text-amber-500 font-display mt-1">{{ $stats['pending'] }}</div>
                        <div class="text-[9px] text-slate-500 mt-0.5">Dibatasi dari absensi mandiri</div>
                    </div>

                    <div class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4.5 relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-indigo-500/5 rounded-full blur-xl"></div>
                        <div class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Tingkat Kepatuhan Kunci</div>
                        <div class="text-2xl font-black text-indigo-400 font-display mt-1">{{ $stats['rate'] }}%</div>
                        <div class="w-full bg-white/5 h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-full rounded-full" style="width: {{ $stats['rate'] }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Search, Filter & Audit controls -->
                <div class="bg-[#0d1527]/80 border border-white/5 rounded-2xl p-4 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Cari Nama / ID Karyawan</label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Masukkan nama, email, atau ID..." class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-sky-500 transition-all placeholder:text-slate-600">
                            @if($search)
                                <button @click="$wire.set('search', '')" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white text-xs">×</button>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Filter Status Biometrik</label>
                        <select wire:model.live="statusFilter" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="all" class="bg-[#121d33]">Semua Karyawan</option>
                            <option value="registered" class="bg-[#121d33]">Wajah Terverifikasi (Aktif)</option>
                            <option value="pending" class="bg-[#121d33]">Belum Registrasi Wajah</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Penempatan Kantor Cabang</label>
                        <select wire:model.live="branchFilter" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="all" class="bg-[#121d33]">Semua Cabang</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" class="bg-[#121d33]">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Main Ledger Table -->
                <div class="border border-white/5 rounded-2xl overflow-hidden bg-black/20">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 bg-[#0d1527]/70 text-slate-400 font-bold uppercase tracking-wider text-[9px]">
                                    <th class="px-5 py-3.5">Karyawan</th>
                                    <th class="px-5 py-3.5">Cabang & Mode Kerja</th>
                                    <th class="px-5 py-3.5 text-center">Status Registrasi Wajah</th>
                                    <th class="px-5 py-3.5 text-center font-mono">Sudut Telemetri</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 font-medium text-slate-300">
                                @forelse($users as $u)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center space-x-3.5">
                                                @if($u->hasRegisteredFace())
                                                    <div class="relative w-9 h-9 rounded-xl border border-sky-500/20 overflow-hidden bg-slate-800 flex-shrink-0">
                                                        <img src="{{ $u->getMasterFaceUrl() }}" class="w-full h-full object-cover transform scaleX(-1)">
                                                    </div>
                                                @else
                                                    <div class="w-9 h-9 rounded-xl bg-slate-800/80 border border-white/5 flex items-center justify-center font-bold text-slate-400 flex-shrink-0">
                                                        {{ substr($u->name, 0, 1) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="font-bold text-white block text-sm">{{ $u->name }}</span>
                                                    <span class="text-[10px] font-mono text-slate-500 block uppercase tracking-wider mt-0.5 font-semibold">#{{ $u->employee_id }} · {{ $u->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-5 py-4">
                                            <span class="text-white font-bold block">{{ $u->branch->name ?? 'Belum Ditentukan' }}</span>
                                            <span class="text-[9px] font-black uppercase text-sky-400 tracking-wider bg-sky-500/10 px-2 py-0.5 rounded border border-sky-500/20 mt-1 inline-block">
                                                {{ strtoupper($u->work_mode) }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            @if($u->is_registered)
                                                <span class="inline-flex items-center px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5"></span>
                                                    Kunci Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[10px] font-bold uppercase tracking-wider rounded-full">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-400 mr-1.5"></span>
                                                    Belum Terdaftar
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4 text-center font-mono text-xs">
                                            @if($u->is_registered)
                                                <div class="flex items-center justify-center space-x-1">
                                                    <span class="text-xs font-black text-sky-400 mr-1.5">{{ $u->registered_angles }}/3</span>
                                                    <span class="w-2 h-2 rounded-full {{ $u->registered_angles >= 1 ? 'bg-sky-400 shadow-[0_0_8px_rgba(56,189,248,0.6)]' : 'bg-white/10' }}" title="Sudut Tengah"></span>
                                                    <span class="w-2 h-2 rounded-full {{ $u->registered_angles >= 2 ? 'bg-indigo-400 shadow-[0_0_8px_rgba(129,140,248,0.6)]' : 'bg-white/10' }}" title="Profil Kiri"></span>
                                                    <span class="w-2 h-2 rounded-full {{ $u->registered_angles >= 3 ? 'bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)]' : 'bg-white/10' }}" title="Profil Kanan"></span>
                                                </div>
                                            @else
                                                <span class="text-slate-600 font-bold uppercase text-[9px] tracking-wider">0 Sudut Terkunci</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4 text-right">
                                             <div class="flex items-center justify-end space-x-2">
                                                 <button wire:click="openUserEditModal({{ $u->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/20 hover:border-blue-500/30 text-sky-400 hover:text-sky-300 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                                                     ⚙️ Kelola
                                                 </button>

                                                 @if($u->is_registered)
                                                     <button wire:click="revokeBiometrics({{ $u->id }})" wire:confirm="Apakah Anda yakin ingin menghapus dan membatalkan kunci biometrik untuk {{ $u->name }}? Mereka akan langsung diblokir dari sistem absensi." class="inline-flex items-center px-3 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 hover:border-rose-500/30 text-rose-400 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                                                         <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                         </svg>
                                                         Hapus Wajah
                                                     </button>
                                                 @else
                                                     <div class="flex items-center justify-end" x-data="{ uploading: false }">
                                                         <label class="inline-flex items-center px-3 py-1.5 bg-sky-500/10 hover:bg-sky-500/20 border border-sky-500/20 hover:border-sky-500/30 text-sky-400 hover:text-sky-300 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer relative">
                                                             <span x-show="!uploading">➕ Wajah</span>
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-slate-500 font-bold uppercase tracking-wider">
                                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
        <!-- TAB 2: KELOLA KANTOR CABANG -->
        <!-- ========================================================== -->
        @if($activeTab === 'branches')
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-extrabold text-white tracking-tight font-display">Kelola Kantor Cabang</h3>
                        <p class="text-xs text-slate-400 font-medium">Tambah, ubah, dan hapus koordinat perimeter geofence kantor cabang perusahaan</p>
                    </div>
                    <button wire:click="openBranchModal()" type="button" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-700 hover:to-sky-600 text-white text-[11px] font-black uppercase tracking-wider rounded-xl shadow-md hover:translate-y-[-1px] transition-all cursor-pointer">
                        ➕ Tambah Cabang Baru
                    </button>
                </div>

                <div class="border border-white/5 rounded-2xl overflow-hidden bg-black/20">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 bg-[#0d1527]/70 text-slate-400 font-bold uppercase tracking-wider text-[9px]">
                                    <th class="px-5 py-3.5">Nama & Kode Cabang</th>
                                    <th class="px-5 py-3.5">Alamat Kantor</th>
                                    <th class="px-5 py-3.5 font-mono">Koordinat (Lat / Lng)</th>
                                    <th class="px-5 py-3.5 text-center">Batas Radius</th>
                                    <th class="px-5 py-3.5 text-center">Status</th>
                                    <th class="px-5 py-3.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 font-medium text-slate-300">
                                @forelse($branches as $b)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-5 py-4">
                                            <span class="font-bold text-white text-sm block">{{ $b->name }}</span>
                                            <span class="text-[10px] font-mono text-slate-500 uppercase font-semibold block tracking-wider mt-0.5">KODE: {{ $b->code }}</span>
                                        </td>
                                        
                                        <td class="px-5 py-4 text-slate-300 max-w-xs truncate">
                                            {{ $b->address }}
                                        </td>

                                        <td class="px-5 py-4 font-mono text-slate-400">
                                            <div>Lat: {{ $b->latitude }}</div>
                                            <div>Lng: {{ $b->longitude }}</div>
                                        </td>

                                        <td class="px-5 py-4 text-center font-bold text-sky-400">
                                            {{ $b->radius }} meter
                                        </td>

                                        <td class="px-5 py-4 text-center">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider {{ $b->is_active ? 'bg-sky-500/10 text-sky-400 border border-sky-500/20' : 'bg-white/5 text-slate-500 border border-white/10' }}">
                                                {{ $b->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>

                                        <td class="px-5 py-4 text-right space-x-1.5">
                                            <button wire:click="openBranchModal({{ $b->id }})" type="button" class="inline-flex items-center px-2.5 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                                                Ubah
                                            </button>
                                            <button wire:click="deleteBranch({{ $b->id }})" wire:confirm="Apakah Anda yakin ingin menghapus koordinat kantor cabang ini? Seluruh karyawan yang ditugaskan di cabang ini harus disesuaikan kembali." type="button" class="inline-flex items-center px-2.5 py-1.5 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-12 text-center text-slate-500 font-bold uppercase tracking-wider">
                                            Tidak ada data kantor cabang yang terdaftar di database.
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
        @if($activeTab === 'roles')
            <div class="bg-[#121d33]/65 backdrop-blur-xl border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
                <div class="mb-8">
                    <h3 class="text-xl font-extrabold text-white tracking-tight font-display">Matriks Otorisasi Fitur (Spatie Rules)</h3>
                    <p class="text-xs text-slate-400 font-medium">Batasi dan tentukan hak akses kapabilitas sistem secara detail untuk masing-masing peran karyawan</p>
                    
                    <div class="mt-4 p-4 bg-[#0d1527]/90 border border-amber-500/20 text-amber-400 rounded-2xl text-[11px] font-bold flex items-start gap-2 max-w-3xl">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <span class="uppercase tracking-widest text-[9px] block mb-1">Peringatan Keamanan Administratif</span>
                            Pemberian hak akses baru atau penolakan kapabilitas akan berdampak langsung seketika pada sesi aktif karyawan.
                            Demi menjaga keamanan struktur otorisasi sistem, hanya akun Super Administrator yang diizinkan memodifikasi Spatie Matrix ini.
                        </div>
                    </div>
                </div>

                <div class="border border-white/5 rounded-2xl overflow-hidden bg-black/20">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="border-b border-white/5 bg-[#0d1527]/70 text-slate-400 font-bold uppercase tracking-wider text-[9px]">
                                    <th class="px-5 py-4">Kunci Kapabilitas / Izin Sistem</th>
                                    @foreach($roles as $r)
                                        <th class="px-5 py-4 text-center font-bold">
                                            <span class="text-white text-sm block">{{ strtoupper(str_replace('_', ' ', $r->name)) }}</span>
                                            <span class="text-[9px] text-slate-500 font-semibold uppercase tracking-wider">{{ $r->permissions->count() }} Izin</span>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 font-medium text-slate-300">
                                @foreach($allPermissions as $p)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-5 py-3">
                                            <span class="font-bold text-white block text-[13px]">{{ strtoupper(str_replace('view ', 'Lihat ', str_replace('create ', 'Buat ', str_replace('edit ', 'Ubah ', str_replace('delete ', 'Hapus ', str_replace('approve ', 'Setujui ', $p->name)))))) }}</span>
                                            <span class="text-[9px] text-slate-500 uppercase tracking-widest mt-0.5">KUNCI TEKNIS: {{ $p->name }}</span>
                                        </td>

                                        @foreach($roles as $r)
                                            <td class="px-5 py-3 text-center">
                                                <button wire:click="togglePermission({{ $r->id }}, '{{ $p->name }}')" type="button" class="relative inline-flex items-center focus:outline-none cursor-pointer">
                                                    <span class="w-5 h-5 rounded-md border flex items-center justify-center transition-all {{ $r->hasPermissionTo($p->name) ? 'bg-sky-500/20 border-sky-400 text-sky-400 shadow-[0_0_8px_rgba(56,189,248,0.3)]' : 'bg-white/5 border-white/10 text-transparent' }}">
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

    </div>

    <!-- ========================================================== -->
    <!-- MODALS & DRAWERS -->
    <!-- ========================================================== -->

    <!-- Frosted Midnight Glass Registration Modal -->
    <div x-data="{ open: @entangle('showRegisterModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-md" style="display: none;" x-transition>
        <div @click.away="open = false" class="w-full max-w-lg bg-[#121d33] border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <h3 class="text-xl font-extrabold text-white tracking-tight font-display">➕ Daftarkan Karyawan Baru</h3>
                <button @click="open = false" class="text-slate-400 hover:text-white text-xl font-bold">×</button>
            </div>

            <form wire:submit.prevent="registerUser" class="space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap Karyawan</label>
                    <input wire:model="new_name" type="text" placeholder="Contoh: Budi Santoso" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all placeholder:text-slate-600">
                    @error('new_name') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email Kantor</label>
                    <input wire:model="new_email" type="email" placeholder="Contoh: budi.santoso@perusahaan.com" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all placeholder:text-slate-600">
                    @error('new_email') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nomor Induk Karyawan (EMP ID)</label>
                        <input wire:model="new_employee_id" type="text" placeholder="Contoh: EMP1042" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all placeholder:text-slate-600">
                        @error('new_employee_id') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kata Sandi Akun</label>
                        <input wire:model="new_password" type="password" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                        @error('new_password') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Cabang Kantor</label>
                        <select wire:model="new_branch_id" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="">Pilih Cabang</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                        @error('new_branch_id') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Mode Kerja</label>
                        <select wire:model="new_work_mode" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="wfo">WFO (Di Kantor)</option>
                            <option value="wfh">WFH (Di Rumah)</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                        @error('new_work_mode') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Peran Sistem</label>
                        <select wire:model="new_role" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                            <option value="employee">Employee (Karyawan)</option>
                            <option value="manager">Manager</option>
                            <option value="hr_admin">HR Admin</option>
                        </select>
                        @error('new_role') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-white/5">
                    <button @click="open = false" type="button" class="px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-xl hover:text-white hover:border-white/20 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-md transition-all cursor-pointer">
                        Buat Akun Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Branch CRUD Modal -->
    <div x-data="{ open: @entangle('showBranchModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-md" style="display: none;" x-transition>
        <div @click.away="open = false" class="w-full max-w-lg bg-[#121d33] border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <h3 class="text-xl font-extrabold text-white tracking-tight font-display">
                    {{ $selectedBranchId ? '🏢 Ubah Cabang Kantor' : '🏢 Daftarkan Cabang Baru' }}
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-white text-xl font-bold">×</button>
            </div>

            <form wire:submit.prevent="saveBranch" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Cabang</label>
                        <input wire:model="branch_name" type="text" placeholder="Contoh: Surabaya Branch" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                        @error('branch_name') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode Cabang</label>
                        <input wire:model="branch_code" type="text" placeholder="Contoh: SBY" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                        @error('branch_code') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                    <textarea wire:model="branch_address" required rows="2" placeholder="Nama jalan, nomor, kota..." class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all resize-none"></textarea>
                    @error('branch_address') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Latitude</label>
                        <input wire:model="branch_latitude" type="text" placeholder="Contoh: -7.257472" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                        @error('branch_latitude') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Longitude</label>
                        <input wire:model="branch_longitude" type="text" placeholder="Contoh: 112.752090" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                        @error('branch_longitude') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Radius Batas (meter)</label>
                        <input wire:model="branch_radius" type="number" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                        @error('branch_radius') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex items-center">
                        <button type="button" @click="$wire.branch_is_active = !$wire.branch_is_active" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="$wire.branch_is_active ? 'bg-sky-500' : 'bg-white/10'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.branch_is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="ml-3 text-xs font-semibold text-slate-300">Set Status Kantor Cabang ke Aktif</span>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-white/5">
                    <button @click="open = false" type="button" class="px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-xl hover:text-white hover:border-white/20 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-md transition-all cursor-pointer">
                        {{ $selectedBranchId ? 'Simpan Perubahan' : 'Daftarkan Cabang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Employee Detail & Edit Modal -->
    <div x-data="{ open: @entangle('showUserEditModal') }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-md" style="display: none;" x-transition>
        <div @click.away="open = false" class="w-full max-w-3xl bg-[#121d33]/95 border border-white/15 rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden animate-fade-in">
            <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-4">
                <div>
                    <h3 class="text-lg font-extrabold text-white tracking-tight font-display">⚙️ Kelola Akun Karyawan</h3>
                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">Ubah rincian profil, penempatan cabang, biometrik wajah, perangkat terpercaya, dan peran</p>
                </div>
                <button @click="open = false" class="text-slate-400 hover:text-white text-xl font-bold">×</button>
            </div>

            @php
                $selectedUser = $selectedUserId ? \App\Models\User::find($selectedUserId) : null;
            @endphp

            <form wire:submit.prevent="saveUser" class="space-y-6">
                <!-- Two Column Layout: Face + Details -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    
                    <!-- Left Column: Master Face Photo -->
                    <div class="md:col-span-4 flex flex-col items-center">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Baseline Biometrik</span>
                        
                        <div class="relative w-full aspect-square max-w-[180px] bg-[#0d1527] border border-white/10 rounded-2xl overflow-hidden flex items-center justify-center shadow-md">
                            @if($selectedUser && $selectedUser->hasRegisteredFace())
                                <img src="{{ $selectedUser->getMasterFaceUrl() }}" class="w-full h-full object-cover transform scaleX(-1)">
                                <!-- Cyber Sweep HUD -->
                                <div class="absolute inset-x-0 h-[1.5px] bg-sky-400 shadow-[0_0_10px_#38bdf8] pointer-events-none z-10" style="animation: scanLine 3s linear infinite;"></div>
                            @else
                                <div class="text-center text-slate-500 p-4">
                                    <svg class="w-10 h-10 mx-auto text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider block">Wajah Belum Terdaftar</span>
                                </div>
                            @endif
                        </div>

                        @if($selectedUser && $selectedUser->hasRegisteredFace())
                            <div class="mt-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase tracking-wide">
                                    ✓ Wajah Terdaftar
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Personal & Employee details -->
                    <div class="md:col-span-8 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                                <input wire:model="edit_name" type="text" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                @error('edit_name') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nomor Induk / ID Karyawan</label>
                                <input wire:model="edit_employee_id" type="text" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                @error('edit_employee_id') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
                                <input wire:model="edit_email" type="email" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                @error('edit_email') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Ubah Sandi Baru (Opsional)</label>
                                <input wire:model="edit_password" type="password" placeholder="Kosongkan jika tidak diubah" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                @error('edit_password') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tanggal Lahir</label>
                                <input wire:model="edit_date_of_birth" type="date" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                @error('edit_date_of_birth') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tanggal Masuk Kerja (Joined)</label>
                                <input wire:model="edit_joined_at" type="date" class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all">
                                @error('edit_joined_at') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Cabang Kantor</label>
                                <select wire:model="edit_branch_id" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                                    <option value="">Pilih Cabang</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                                @error('edit_branch_id') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Mode Kerja</label>
                                <select wire:model="edit_work_mode" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                                    <option value="wfo">WFO (Di Kantor)</option>
                                    <option value="wfh">WFH (Di Rumah)</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                                @error('edit_work_mode') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Peran Sistem</label>
                                <select wire:model="edit_role" required class="w-full bg-[#0d1527] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-sky-500 transition-all cursor-pointer">
                                    <option value="employee">Employee</option>
                                    <option value="manager">Manager</option>
                                    <option value="hr_admin">HR Admin</option>
                                    <option value="super_admin">Super Admin</option>
                                </select>
                                @error('edit_role') <span class="text-[9px] text-rose-400 font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trusted Devices / Fingerprint Section -->
                <div class="border-t border-white/10 pt-4">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5">🖥️ Telemetri Perangkat & Browser Karyawan</span>
                    
                    @if(empty($userDevices))
                        <div class="bg-[#0d1527]/50 border border-white/5 rounded-2xl p-4 text-center text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            Tidak ada perangkat yang terdaftar atau digunakan karyawan ini.
                        </div>
                    @else
                        <div class="max-h-[140px] overflow-y-auto space-y-2 pr-1.5 divide-y divide-white/5 bg-[#0d1527]/40 border border-white/5 rounded-2xl p-3 text-xs">
                            @foreach($userDevices as $index => $device)
                                <div class="flex justify-between items-center pt-2 {{ $index === 0 ? 'pt-0' : '' }}">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-white">{{ $device['browser'] ?? 'Browser' }} on {{ $device['os'] ?? 'OS' }}</span>
                                            @if($device['trusted'])
                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 uppercase">Trusted</span>
                                            @endif
                                        </div>
                                        <div class="text-[9px] text-slate-500 font-mono tracking-wider">
                                            Hash: {{ substr($device['device_hash'], 0, 16) }} · Platform: {{ $device['platform'] ?? 'N/A' }} · GPU: {{ substr($device['gpu_info'] ?? 'N/A', 0, 45) }}
                                        </div>
                                    </div>
                                    <div class="text-right text-[10px] text-slate-400 font-semibold">
                                        <span>Terakhir absensi:</span>
                                        <span class="block text-sky-400 font-bold mt-0.5">
                                            {{ $device['last_used_at'] ? \Carbon\Carbon::parse($device['last_used_at'])->translatedFormat('d M Y, H:i') : 'Belum Pernah' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Footer Section: Toggles & Permanently Delete -->
                <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                    <div class="flex items-center">
                        <button type="button" @click="$wire.edit_is_active = !$wire.edit_is_active" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" :class="$wire.edit_is_active ? 'bg-sky-500' : 'bg-white/10'">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out" :class="$wire.edit_is_active ? 'translate-x-5' : 'translate-x-0'"></span>
                        </button>
                        <span class="ml-3 text-xs font-semibold text-slate-300">Akun Karyawan Aktif</span>
                    </div>

                    <button type="button" 
                            wire:click="deleteUser({{ $selectedUserId ?? 0 }})"
                            wire:confirm="PERINGATAN KERAS! Apakah Anda benar-benar yakin ingin menghapus permanen akun karyawan ini? Semua riwayat absensi dan data terkait akan dihapus secara permanen dari basis data."
                            class="px-3.5 py-2 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 hover:border-rose-500/30 text-rose-400 hover:text-rose-300 text-[10px] font-bold uppercase tracking-wider rounded-xl transition-all cursor-pointer">
                        🗑️ Hapus Akun Karyawan
                    </button>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-white/5">
                    <button @click="open = false" type="button" class="px-4 py-2.5 bg-white/5 border border-white/10 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-xl hover:text-white hover:border-white/20 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 via-sky-500 to-emerald-500 text-white text-xs font-bold uppercase tracking-wider rounded-xl hover:from-blue-700 hover:via-sky-600 hover:to-emerald-600 shadow-md transition-all cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

</div>
