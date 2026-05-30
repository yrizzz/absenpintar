<div class="py-8 min-h-screen text-slate-900 dark:text-slate-100 bg-transparent">
    
    <!-- Custom Theme-Isolated styles for Permissions to completely bypass app.css wildcards -->
    <style>
        /* Base / Default Theme (Dark Mode) */
        .permissions-card {
            background: linear-gradient(135deg, rgba(18, 29, 51, 0.82) 0%, rgba(11, 18, 34, 0.94) 100%) !important;
            backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.4) !important;
            color: #f1f5f9 !important;
        }

        /* Light Mode Override */
        html.light .permissions-card {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.08) !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05) !important;
            color: #0f172a !important;
        }

        /* Base / Default Theme (Dark Mode) */
        .permissions-kpi-card {
            background: linear-gradient(135deg, rgba(18, 29, 51, 0.82) 0%, rgba(11, 18, 34, 0.94) 100%) !important;
            backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.4) !important;
            color: #f1f5f9 !important;
        }
        .permissions-kpi-card:hover {
            border-color: rgba(59, 130, 246, 0.3) !important;
            box-shadow: 0 25px 50px -12px rgba(59, 130, 246, 0.12) !important;
        }

        /* Light Mode Override */
        html.light .permissions-kpi-card {
            background: #ffffff !important;
            background-color: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.08) !important;
            box-shadow: 0 4px 15px -3px rgba(15, 23, 42, 0.03) !important;
            color: #0f172a !important;
        }
        html.light .permissions-kpi-card:hover {
            border-color: rgba(37, 99, 235, 0.25) !important;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.08) !important;
        }

        /* Base / Default Theme (Dark Mode) */
        .permissions-subcard {
            background-color: rgba(13, 21, 39, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            color: #cbd5e1 !important;
        }

        /* Light Mode Override */
        html.light .permissions-subcard {
            background-color: rgba(15, 23, 42, 0.03) !important;
            border: 1px solid rgba(15, 23, 42, 0.05) !important;
            color: #334155 !important;
        }

        /* Base / Default Theme (Dark Mode) */
        .permissions-history-card {
            background-color: rgba(13, 21, 39, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        .permissions-history-card:hover {
            background-color: rgba(13, 21, 39, 0.6) !important;
            border-color: rgba(59, 130, 246, 0.2) !important;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.4) !important;
        }

        /* Light Mode Override */
        html.light .permissions-history-card {
            background-color: rgba(15, 23, 42, 0.02) !important;
            border: 1px solid rgba(15, 23, 42, 0.05) !important;
        }
        html.light .permissions-history-card:hover {
            background-color: #ffffff !important;
            border-color: rgba(37, 99, 235, 0.25) !important;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.08) !important;
        }

        /* Base / Default Theme (Dark Mode) */
        .permissions-innercard {
            background-color: rgba(13, 21, 39, 0.85) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        /* Light Mode Override */
        html.light .permissions-innercard {
            background-color: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.08) !important;
        }

        /* Base / Default Theme (Dark Mode) - Filter inputs styling and padding */
        .permissions-input {
            background-color: #0d1527 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            padding-top: 0.625rem !important;
            padding-bottom: 0.625rem !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
            border-radius: 0.75rem !important;
            font-size: 0.75rem !important;
            transition: all 0.2s ease-in-out !important;
        }
        .permissions-input:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2) !important;
        }
        /* Specific rules for select dropdowns and search boxes */
        select.permissions-input {
            padding-right: 2rem !important;
            cursor: pointer !important;
        }
        input.permissions-input[type="text"] {
            padding-left: 2.75rem !important; /* space for icon */
        }

        /* Light Mode Override */
        html.light .permissions-input {
            background-color: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.12) !important;
            color: #0f172a !important;
        }
        html.light .permissions-input:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12) !important;
        }

        /* Base / Default Theme (Dark Mode) - Search Icon Wrapper */
        .permissions-search-icon-wrapper {
            position: absolute !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            left: 0.95rem !important; /* Premium spacing from left border */
            display: flex !important;
            align-items: center !important;
            pointer-events: none !important;
            color: #94a3b8 !important;
            z-index: 10 !important;
        }
        /* Light Mode Override - Search Icon Wrapper */
        html.light .permissions-search-icon-wrapper {
            color: #64748b !important;
        }

        /* Base / Default Theme (Dark Mode) - Tabs Wrapper */
        .permissions-tab-wrapper {
            background-color: rgba(13, 21, 39, 0.4) !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }
        
        /* Light Mode Override - Tabs Wrapper */
        html.light .permissions-tab-wrapper {
            background-color: rgba(15, 23, 42, 0.03) !important;
            border: 1px solid rgba(15, 23, 42, 0.08) !important;
        }

        /* Base / Default Theme (Dark Mode) - Tabs Button */
        .permissions-tab-btn {
            color: #94a3b8 !important;
            background: transparent !important;
            transition: all 0.2s ease-in-out !important;
        }
        .permissions-tab-btn span {
            color: inherit !important;
        }
        .permissions-tab-btn svg {
            color: inherit !important;
            stroke: currentColor !important;
        }
        .permissions-tab-btn:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        .permissions-tab-btn.active,
        .permissions-tab-btn.tab-active {
            background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25) !important;
        }
        .permissions-tab-btn.active *,
        .permissions-tab-btn.tab-active * {
            color: #ffffff !important;
            fill: #ffffff !important;
        }
        
        /* Light Mode Override - Tabs Button */
        html.light .permissions-tab-btn {
            color: #475569 !important;
            background: transparent !important;
        }
        html.light .permissions-tab-btn:hover {
            color: #0f172a !important;
            background-color: rgba(15, 23, 42, 0.04) !important;
        }
        html.light .permissions-tab-btn.active,
        html.light .permissions-tab-btn.tab-active {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
            border: none !important;
        }
        html.light .permissions-tab-btn.active *,
        html.light .permissions-tab-btn.tab-active * {
            color: #ffffff !important;
            fill: #ffffff !important;
        }

        /* Base / Default Theme (Dark Mode) - Modal Backdrop Overlay */
        .permissions-modal-backdrop {
            background: rgba(0, 0, 0, 0.45) !important;
            background-color: rgba(0, 0, 0, 0.45) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
        }

        /* Light Mode Override - Modal Backdrop Overlay */
        html.light .permissions-modal-backdrop {
            background: rgba(15, 23, 42, 0.3) !important; /* Soft, transparent bootstrap-like slate-dark backdrop */
            background-color: rgba(15, 23, 42, 0.3) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
        }

        /* Base / Default Theme (Dark Mode) */
        .permissions-modal {
            background-color: #121d33 !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #f1f5f9 !important;
        }

        /* Light Mode Override */
        html.light .permissions-modal {
            background-color: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.08) !important;
            color: #0f172a !important;
        }

        /* ========================================================
           GLOBAL SLATE TEXT COLOR OVERRIDES IN BASE/DARK MODE
           ======================================================== */
        .text-slate-900, .text-slate-800, .text-slate-700, .text-slate-600 {
            color: #f8fafc !important; /* bright text */
        }
        .text-slate-500, .text-slate-400 {
            color: #94a3b8 !important; /* muted/medium grey text */
        }

        /* Restore Tailwind's Light Mode color mapping when light class is active */
        html.light .text-slate-900 {
            color: #0f172a !important;
        }
        html.light .text-slate-800 {
            color: #1e293b !important;
        }
        html.light .text-slate-700 {
            color: #334155 !important;
        }
        html.light .text-slate-600 {
            color: #475569 !important;
        }
        html.light .text-slate-500 {
            color: #64748b !important;
        }
        html.light .text-slate-400 {
            color: #94a3b8 !important;
        }

        /* Fix global CSS specificity issue forcing fill: #ffffff on SVGs in active states */
        .tab-active svg, 
        .tab-active svg *,
        .btn-primary svg,
        .btn-primary svg *,
        .btn-success svg,
        .btn-success svg *,
        .btn-danger svg,
        .btn-danger svg *,
        .bg-blue-600 svg,
        .bg-blue-600 svg *,
        [class*="bg-blue-"] svg,
        [class*="bg-blue-"] svg * {
            fill: none !important;
        }
    </style>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight font-display">Sistem Izin Kerja</h1>
                <p class="mt-1.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Pengajuan izin datang terlambat, pulang awal, setengah hari, dan tidak masuk dengan alur persetujuan ganda.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                @if($step === 'index')
                    <button wire:click="$set('step', 'create')" type="button" class="btn-sm btn-primary flex items-center gap-1.5 shadow-[0_0_15px_rgba(59,130,246,0.2)] hover:shadow-[0_0_20px_rgba(59,130,246,0.35)] transition-all">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Ajukan Izin Baru</span>
                    </button>
                @else
                    <button wire:click="$set('step', 'index')" type="button" class="btn-sm btn-secondary flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span>Kembali ke Riwayat</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Global Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 sm:p-5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl text-xs font-bold flex items-center shadow-lg animate-fade-in">
                <svg class="w-5 h-5 mr-2.5 flex-shrink-0 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 sm:p-5 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 rounded-2xl text-xs font-bold flex items-center shadow-lg animate-fade-in">
                <svg class="w-5 h-5 mr-2.5 flex-shrink-0 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- ========================================== -->
        <!-- FORM PENGAJUAN IZIN -->
        <!-- ========================================== -->
        @if($step === 'create')
            <div class="max-w-3xl mx-auto permissions-card rounded-2xl p-6 sm:p-8 relative overflow-hidden animate-fade-in">
                <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center gap-2">
                    <span class="p-1.5 bg-blue-500/10 rounded-lg text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    Formulir Pengajuan Izin Kerja
                </h3>
                
                <form wire:submit.prevent="submitRequest" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Kategori Izin</label>
                            <select wire:model.live="type" required class="w-full permissions-input rounded-2xl px-4 py-3.5 text-xs focus:outline-none transition-all cursor-pointer">
                                <option value="ijin_datang_terlambat">Izin Datang Terlambat</option>
                                <option value="ijin_pulang_awal">Izin Pulang Awal</option>
                                <option value="ijin_tidak_masuk">Izin Tidak Masuk</option>
                                <option value="ijin_setengah_hari">Izin Setengah Hari</option>
                            </select>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-1.5 block">Pilih kategori dispensasi izin kerja yang diajukan.</span>
                            
                            @if($type === 'ijin_datang_terlambat')
                                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mt-2 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                    Batas toleransi datang terlambat maksimal {{ cache()->get('settings.permission_max_late_hours', 2.0) }} jam.
                                </span>
                            @elseif($type === 'ijin_pulang_awal')
                                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mt-2 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                    Batas toleransi pulang awal maksimal {{ cache()->get('settings.permission_max_early_hours', 2.0) }} jam.
                                </span>
                            @elseif($type === 'ijin_setengah_hari')
                                <span class="text-[10px] text-amber-600 dark:text-amber-400 font-bold mt-2 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-ping"></span>
                                    Batas toleransi setengah hari maksimal {{ cache()->get('settings.permission_max_half_day_hours', 4.0) }} jam.
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Tanggal Izin</label>
                            <input wire:model="date" type="date" required class="w-full permissions-input rounded-2xl px-4 py-3.5 text-xs focus:outline-none transition-all cursor-pointer">
                            @error('date')
                                <span class="text-xs text-rose-500 dark:text-rose-4550 font-bold mt-1.5 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    @if($type !== 'ijin_tidak_masuk')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Jam Mulai Izin</label>
                                <input wire:model="start_time" type="time" required class="w-full permissions-input rounded-2xl px-4 py-3.5 text-xs focus:outline-none transition-all">
                                @error('start_time')
                                    <span class="text-xs text-rose-500 dark:text-rose-4550 font-bold mt-1.5 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Jam Selesai Izin</label>
                                <input wire:model="end_time" type="time" required class="w-full permissions-input rounded-2xl px-4 py-3.5 text-xs focus:outline-none transition-all">
                                @error('end_time')
                                    <span class="text-xs text-rose-500 dark:text-rose-4550 font-bold mt-1.5 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Alasan Izin</label>
                        <textarea wire:model="reason" rows="4" required placeholder="Tuliskan detail alasan pengajuan izin Anda di sini secara jelas..." class="w-full permissions-input rounded-2xl px-4 py-3 text-xs focus:outline-none transition-all resize-none"></textarea>
                        @error('reason')
                            <span class="text-xs text-rose-500 dark:text-rose-4550 font-bold mt-1.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Dokumen Pendukung / Lampiran (Opsional)</label>
                        <div class="relative flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-white/10 border-dashed rounded-2xl cursor-pointer bg-slate-50 dark:bg-[#0d1527]/50 hover:bg-slate-100 dark:hover:bg-[#0d1527]/85 transition-all hover:border-blue-500/50">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-1 text-xs text-slate-500 dark:text-slate-350"><span class="font-bold text-blue-600 dark:text-blue-400">Klik untuk upload</span> atau drag berkas</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-5550">PDF, JPG, PNG (Maks. 2MB)</p>
                                </div>
                                <input wire:model="attachment" type="file" class="hidden">
                            </label>
                        </div>
                        @if ($attachment)
                            <div class="mt-3 p-3 bg-blue-500/5 border border-blue-500/20 rounded-xl flex items-center justify-between">
                                <span class="text-xs text-blue-600 dark:text-blue-450 font-bold truncate max-w-md">📎 Berkas terpilih: {{ $attachment->getClientOriginalName() }}</span>
                                <button type="button" wire:click="$set('attachment', null)" class="text-xs text-rose-600 dark:text-rose-4550 font-bold hover:underline">Hapus</button>
                            </div>
                        @endif
                        @error('attachment')
                            <span class="text-xs text-rose-500 dark:text-rose-4550 font-bold mt-1.5 block">{{ $message }}</span>
                        @enderror
                        <div wire:loading wire:target="attachment" class="text-xs text-blue-600 dark:text-blue-400 font-bold mt-2 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                            Mengunggah file...
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 space-x-3 border-t border-slate-100 dark:border-white/5">
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
            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="permissions-kpi-card rounded-2xl p-5 shadow-lg relative overflow-hidden flex items-center space-x-4 transition-all duration-300">
                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-blue-500/5 rounded-full blur-xl pointer-events-none"></div>
                    <div class="p-3 bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-black text-slate-500 dark:text-slate-400 tracking-wider">Total Pengajuan Anda</div>
                        <div class="text-2xl font-bold text-slate-800 dark:text-white mt-0.5">{{ auth()->user()->permissionRequests()->count() }} <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">Sesi</span></div>
                    </div>
                </div>

                <div class="permissions-kpi-card rounded-2xl p-5 shadow-lg relative overflow-hidden flex items-center space-x-4 transition-all duration-300">
                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-emerald-500/5 rounded-full blur-xl pointer-events-none"></div>
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-black text-slate-500 dark:text-slate-400 tracking-wider">Disetujui Resmi</div>
                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">{{ auth()->user()->permissionRequests()->where('status', 'approved')->count() }} <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">Sesi</span></div>
                    </div>
                </div>

                <div class="permissions-kpi-card rounded-2xl p-5 shadow-lg relative overflow-hidden flex items-center space-x-4 transition-all duration-300">
                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-amber-500/5 rounded-full blur-xl pointer-events-none"></div>
                    <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-400 rounded-xl flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-black text-slate-500 dark:text-slate-400 tracking-wider">Menunggu Persetujuan</div>
                        <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-0.5">{{ auth()->user()->permissionRequests()->where('status', 'pending')->count() }} <span class="text-xs text-slate-400 dark:text-slate-500 font-normal">Sesi</span></div>
                    </div>
                </div>
            </div>

            <!-- Alur Persetujuan Section -->
            <div class="permissions-card rounded-2xl p-6 sm:p-7 shadow-lg relative overflow-hidden mb-8">
                <div class="flex items-center space-x-2.5 mb-5">
                    <div class="h-5 w-1 bg-blue-500 rounded-full"></div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Alur Persetujuan Ganda (Double-Level Verification)</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="p-5 permissions-subcard rounded-2xl relative">
                        <div class="text-2xl font-black text-blue-600 dark:text-blue-400 font-display leading-none mb-3">01</div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-white">Karyawan Mengajukan</h5>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed font-medium">Mengisi tipe izin (telat, pulang awal, setengah hari, tidak masuk) disertai alasan & berkas.</p>
                    </div>

                    <div class="p-5 permissions-subcard rounded-2xl relative">
                        <div class="text-2xl font-black text-amber-600 dark:text-amber-400 font-display leading-none mb-3">02</div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-white">Kepala Divisi (Kadiv)</h5>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed font-medium">Melakukan review kesesuaian operasional & beban kerja di divisi terkait.</p>
                    </div>

                    <div class="p-5 permissions-subcard rounded-2xl relative">
                        <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400 font-display leading-none mb-3">03</div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-white">HR Manager (HRD)</h5>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed font-medium">Persetujuan akhir & sinkronisasi data dispensasi kehadiran sistem.</p>
                    </div>

                    <div class="p-5 permissions-subcard rounded-2xl relative">
                        <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-display leading-none mb-3">04</div>
                        <h5 class="text-xs font-bold text-slate-800 dark:text-white">Cetak Surat Resmi</h5>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1.5 leading-relaxed font-medium">Sistem menerbitkan surat izin resmi bertanda tangan digital ber-barkod pengaman.</p>
                    </div>
                </div>
            </div>

            <!-- Tab Selector for Admins/Managers -->
            @if($isAdmin)
                <div class="permissions-tab-wrapper flex space-x-1.5 p-1.5 rounded-2xl max-w-md mb-6">
                    <button wire:click="$set('activeTab', 'my')" type="button" 
                        class="flex-1 py-2.5 text-xs font-extrabold rounded-xl transition-all flex items-center justify-center space-x-2 permissions-tab-btn {{ $activeTab === 'my' ? 'active tab-active' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Riwayat Izin Saya</span>
                    </button>
                    <button wire:click="$set('activeTab', 'review')" type="button" 
                        class="flex-1 py-2.5 text-xs font-extrabold rounded-xl transition-all flex items-center justify-center space-x-2 relative permissions-tab-btn {{ $activeTab === 'review' ? 'active tab-active' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Tinjau Izin Karyawan</span>
                        @if($rawPendingCount > 0)
                            <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-rose-600 text-[10px] font-black text-white ring-2 ring-white dark:ring-slate-900 animate-pulse">
                                {{ $rawPendingCount }}
                            </span>
                        @endif
                    </button>
                </div>
            @endif

            <!-- Main Panel Content -->
            <div class="grid grid-cols-1 gap-8">
                
                <!-- Tab: Tinjau Pengajuan Karyawan -->
                @if($isAdmin && $activeTab === 'review')
                    <div class="permissions-card rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden animate-fade-in">
                        <div class="absolute -right-16 -top-16 w-36 h-36 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>
                        
                        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-6 mb-6 pb-6 border-b border-slate-100 dark:border-white/5">
                            <div>
                                <div class="flex items-center space-x-2.5">
                                    <div class="h-5 w-1 bg-indigo-500 rounded-full"></div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">Tinjau Pengajuan Izin Karyawan</h3>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 pl-3.5">Daftar permohonan aktif yang memerlukan verifikasi Kepala Divisi dan HR Manager.</p>
                            </div>
                            
                            <!-- Search & Filter Controls -->
                            <div class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
                                <div class="relative flex-1 min-w-[200px] xl:max-w-xs">
                                    <span class="permissions-search-icon-wrapper">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </span>
                                    <input wire:model.live.debounce.350ms="reviewSearch" type="text" placeholder="Cari nama / ID karyawan..." class="w-full permissions-input rounded-xl pl-10 pr-4 py-2 text-xs focus:outline-none transition-all">
                                </div>

                                <select wire:model.live="reviewTypeFilter" class="permissions-input rounded-xl px-3 py-2 text-xs focus:outline-none cursor-pointer">
                                    <option value="all">Semua Tipe</option>
                                    <option value="ijin_datang_terlambat">Terlambat</option>
                                    <option value="ijin_pulang_awal">Pulang Awal</option>
                                    <option value="ijin_setengah_hari">Setengah Hari</option>
                                    <option value="ijin_tidak_masuk">Tidak Masuk</option>
                                </select>

                                <select wire:model.live="reviewStatusFilter" class="permissions-input rounded-xl px-3 py-2 text-xs focus:outline-none cursor-pointer">
                                    <option value="pending">Pending Review</option>
                                    <option value="approved">Disetujui</option>
                                    <option value="rejected">Ditolak</option>
                                    <option value="all">Semua Riwayat</option>
                                </select>

                                @if($reviewSearch || $reviewTypeFilter !== 'all' || $reviewStatusFilter !== 'pending')
                                    <button wire:click="clearReviewFilters" type="button" class="btn-xs px-3 py-2 bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 hover:bg-rose-500/20 hover:text-rose-700 dark:hover:text-rose-300 font-bold rounded-xl transition-all">
                                        Reset
                                    </button>
                                @endif
                            </div>
                        </div>

                        @if($reviewRequests->isEmpty())
                            <div class="py-16 flex flex-col items-center justify-center permissions-subcard rounded-2xl">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 mb-4 shadow-md">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white">Antrean Bersih!</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-xs text-center leading-relaxed">Tidak ditemukan pengajuan izin kerja yang cocok dengan kriteria filter Anda saat ini.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-black/25">
                                <table class="w-full min-w-max text-left border-collapse">
                                    <thead>
                                        <tr class="border-b border-slate-200 dark:border-white/5 text-[10px] bg-slate-100 dark:bg-white/5 font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                            <th class="px-5 py-4 w-[240px]">Karyawan</th>
                                            <th class="px-5 py-4 w-[140px]">Tipe Izin</th>
                                            <th class="px-5 py-4 w-[160px]">Tanggal & Waktu</th>
                                            <th class="px-5 py-4 w-[280px]">Alasan & Lampiran</th>
                                            <th class="px-5 py-4 text-center w-[150px]">Persetujuan Ganda</th>
                                            <th class="px-5 py-4 text-right w-[200px]">Aksi Review</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-slate-700 dark:text-slate-200">
                                        @foreach($reviewRequests as $req)
                                            <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors align-middle">
                                                <td class="px-5 py-4.5">
                                                    <div class="flex items-center space-x-3.5">
                                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm uppercase shadow-sm">
                                                            {{ strtoupper(substr($req->user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-xs font-bold text-slate-800 dark:text-white">{{ $req->user->name }}</div>
                                                            <div class="text-[10px] font-mono text-slate-500 dark:text-slate-400 mt-0.5">#{{ $req->user->employee_id }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4.5 text-xs font-bold">
                                                    @if($req->type === 'ijin_datang_terlambat')
                                                        <span class="badge-rect-warning">Terlambat</span>
                                                    @elseif($req->type === 'ijin_pulang_awal')
                                                        <span class="badge-rect-danger">Pulang Awal</span>
                                                    @elseif($req->type === 'ijin_setengah_hari')
                                                        <span class="badge-rect-info">Setengah Hari</span>
                                                    @else
                                                        <span class="badge-rect-neutral">Tidak Masuk</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4.5 text-xs font-medium text-slate-600 dark:text-slate-3300">
                                                    <div class="font-bold text-slate-800 dark:text-white">{{ $req->date->translatedFormat('d M Y') }}</div>
                                                    @if($req->type !== 'ijin_tidak_masuk')
                                                        <div class="text-[10px] text-slate-500 dark:text-slate-450 mt-1 flex items-center">
                                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            {{ substr($req->start_time, 0, 5) }} - {{ substr($req->end_time, 0, 5) }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4.5 text-xs text-slate-600 dark:text-slate-300">
                                                    <div class="max-w-[260px] truncate font-medium text-slate-700 dark:text-slate-200" title="{{ $req->reason }}">{{ $req->reason }}</div>
                                                    @if($req->attachment_path)
                                                        <a href="{{ asset('storage/' . $req->attachment_path) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline mt-1.5">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                            </svg>
                                                            Lihat Lampiran
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-4.5">
                                                    <div class="flex flex-col space-y-1 items-center justify-center max-w-[120px] mx-auto">
                                                        <div class="flex items-center space-x-2 w-full justify-between px-2 py-0.5 bg-slate-100 dark:bg-black/25 border border-slate-200 dark:border-white/5 rounded-md">
                                                            <span class="text-[8px] font-bold text-slate-500 dark:text-slate-400 uppercase">Kadiv:</span>
                                                            @if($req->status_dept_head === 'approved')
                                                                <span class="text-[8px] font-black text-emerald-600 dark:text-emerald-400 uppercase flex items-center">✓ ACC</span>
                                                            @elseif($req->status_dept_head === 'rejected')
                                                                <span class="text-[8px] font-black text-rose-600 dark:text-rose-450 uppercase flex items-center">✗ REJ</span>
                                                            @else
                                                                <span class="text-[8px] font-black text-amber-600 dark:text-amber-500 uppercase flex items-center">⏳ PND</span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center space-x-2 w-full justify-between px-2 py-0.5 bg-slate-100 dark:bg-black/25 border border-slate-200 dark:border-white/5 rounded-md">
                                                            <span class="text-[8px] font-bold text-slate-500 dark:text-slate-400 uppercase">HRD:</span>
                                                            @if($req->status_hr === 'approved')
                                                                <span class="text-[8px] font-black text-emerald-600 dark:text-emerald-400 uppercase flex items-center">✓ ACC</span>
                                                            @elseif($req->status_hr === 'rejected')
                                                                <span class="text-[8px] font-black text-rose-600 dark:text-rose-4550 uppercase flex items-center">✗ REJ</span>
                                                            @else
                                                                <span class="text-[8px] font-black text-amber-600 dark:text-amber-500 uppercase flex items-center">⏳ PND</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4.5 text-right">
                                                    <div class="flex items-center justify-end space-x-2">
                                                        <button wire:click="viewDetail({{ $req->id }})" type="button" class="btn-xs bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold px-2.5 py-1.5 rounded-lg transition-all">
                                                            Detail
                                                        </button>

                                                        @if($req->user_id === auth()->id())
                                                            <span class="inline-flex items-center gap-1 text-[10px] text-slate-500 font-bold bg-slate-100 dark:bg-slate-900/40 px-2 py-1 rounded-lg border border-slate-200 dark:border-slate-800 cursor-not-allowed" title="Anda tidak dapat memverifikasi pengajuan Anda sendiri">
                                                                🔒 Sendiri
                                                            </span>
                                                        @else
                                                            @if($req->status === 'pending')
                                                                <!-- Dept Head actions -->
                                                                @if($isManager && $req->status_dept_head === 'pending')
                                                                    <div class="flex items-center space-x-1">
                                                                        <button wire:click="approveDeptHead({{ $req->id }})" type="button" class="btn-success btn-xs font-black px-2.5 py-1.5 rounded-lg shadow-sm hover:scale-[1.03] transition-all">
                                                                            ACC Kadiv
                                                                        </button>
                                                                        <button wire:click="openApprovalModal({{ $req->id }}, 'dept_head')" type="button" class="btn-xs bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/20 px-1 py-1 rounded-lg" title="Setujui dengan Catatan">
                                                                            💬
                                                                        </button>
                                                                    </div>
                                                                @endif

                                                                <!-- HR actions -->
                                                                @if($isHr && $req->status_hr === 'pending')
                                                                    <div class="flex items-center space-x-1">
                                                                        <button wire:click="approveHr({{ $req->id }})" type="button" class="btn-primary btn-xs font-black px-2.5 py-1.5 rounded-lg shadow-sm hover:scale-[1.03] transition-all">
                                                                            ACC HR
                                                                        </button>
                                                                        <button wire:click="openApprovalModal({{ $req->id }}, 'hr')" type="button" class="btn-xs bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 hover:bg-blue-500/20 px-1 py-1 rounded-lg" title="Setujui dengan Catatan">
                                                                            💬
                                                                        </button>
                                                                    </div>
                                                                @endif

                                                                <button wire:click="openRejectionModal({{ $req->id }})" type="button" class="btn-danger-outline btn-xs px-2.5 py-1.5 rounded-lg font-black hover:scale-[1.03] transition-all">
                                                                    Tolak
                                                                </button>
                                                            @else
                                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">Selesai</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Pagination Review -->
                            <div class="mt-6">
                                {{ $reviewRequests->links() }}
                            </div>
                        @endif
                    </div>
                
                <!-- Tab: Riwayat Pengajuan Izin Saya -->
                @else
                    <div class="permissions-card rounded-2xl p-6 sm:p-8 shadow-xl relative overflow-hidden animate-fade-in">
                        <div class="absolute -right-16 -top-16 w-36 h-36 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
                        
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100 dark:border-white/5">
                            <div>
                                <div class="flex items-center space-x-2.5">
                                    <div class="h-5 w-1 bg-blue-500 rounded-full"></div>
                                    <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">Riwayat Pengajuan Izin Saya</h3>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 pl-3.5">Lacak riwayat dispensasi kerja dan status persetujuan berjenjang Anda.</p>
                            </div>

                            <!-- Status Filter -->
                            <div class="permissions-tab-wrapper flex items-center space-x-1 p-1.5 rounded-2xl">
                                <button wire:click="$set('statusFilter', 'all')" type="button" class="px-4 py-2 text-xs font-bold rounded-xl transition-all permissions-tab-btn {{ $statusFilter === 'all' ? 'active tab-active' : '' }}">
                                    Semua
                                </button>
                                <button wire:click="$set('statusFilter', 'approved')" type="button" class="px-4 py-2 text-xs font-bold rounded-xl transition-all permissions-tab-btn {{ $statusFilter === 'approved' ? 'active tab-active' : '' }}">
                                    Disetujui
                                </button>
                                <button wire:click="$set('statusFilter', 'pending')" type="button" class="px-4 py-2 text-xs font-bold rounded-xl transition-all permissions-tab-btn {{ $statusFilter === 'pending' ? 'active tab-active' : '' }}">
                                    Pending
                                </button>
                            </div>
                        </div>

                        @if($myPermissions->isEmpty())
                            <div class="py-16 flex flex-col items-center justify-center permissions-subcard rounded-2xl animate-fade-in">
                                <div class="w-14 h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 mb-4 shadow-lg shadow-blue-500/5">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white">Belum Ada Pengajuan</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-xs text-center leading-relaxed">Anda tidak memiliki pengajuan izin dengan status terpilih untuk saat ini.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($myPermissions as $perm)
                                    <div class="permissions-history-card rounded-2xl p-5 relative overflow-hidden flex flex-col justify-between hover:-translate-y-1 transition-all duration-300 group shadow-md hover:shadow-xl">
                                        <div class="absolute -right-6 -bottom-6 w-20 h-20 bg-blue-500/[0.015] rounded-full blur-xl group-hover:bg-blue-500/[0.03]"></div>
                                        
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[10px] font-black uppercase text-slate-500 dark:text-slate-400 tracking-wider">
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
                                                
                                                <!-- Overall status badge -->
                                                @if($perm->status === 'approved')
                                                    <span class="badge-rect-success">Disetujui</span>
                                                @elseif($perm->status === 'rejected')
                                                    <span class="badge-rect-danger">Ditolak</span>
                                                @else
                                                    <span class="badge-rect-warning">Diproses</span>
                                                @endif
                                            </div>
                                            
                                            <div class="text-xs text-slate-600 dark:text-slate-350 space-y-2 permissions-innercard p-3.5 rounded-xl">
                                                <div class="flex justify-between">
                                                    <span class="text-slate-500 dark:text-slate-400">Tanggal Izin:</span>
                                                    <span class="font-bold text-slate-800 dark:text-white">{{ $perm->date->translatedFormat('d M Y') }}</span>
                                                </div>
                                                @if($perm->type !== 'ijin_tidak_masuk')
                                                    <div class="flex justify-between">
                                                        <span class="text-slate-500 dark:text-slate-400">Waktu Dispensasi:</span>
                                                        <span class="font-bold text-blue-600 dark:text-blue-400">{{ substr($perm->start_time, 0, 5) }} s/d {{ substr($perm->end_time, 0, 5) }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex flex-col pt-2 border-t border-slate-100 dark:border-white/5">
                                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Alasan Pengajuan:</span>
                                                    <span class="mt-1 text-slate-700 dark:text-slate-300 leading-relaxed font-medium line-clamp-2" title="{{ $perm->reason }}">{{ $perm->reason }}</span>
                                                </div>
                                                @if($perm->approval_notes)
                                                    <div class="flex flex-col pt-2 border-t border-slate-100 dark:border-white/5">
                                                        <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Catatan Peninjau:</span>
                                                        <span class="mt-1 text-rose-600 dark:text-rose-350 leading-relaxed font-semibold italic">{{ $perm->approval_notes }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Visual Progress Tracking Line -->
                                            <div class="bg-slate-100/50 dark:bg-black/20 border border-slate-200/60 dark:border-white/5 rounded-xl p-3 space-y-2">
                                                <div class="flex items-center justify-between text-[9px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider">
                                                    <span>Tahap Verifikasi:</span>
                                                </div>
                                                <div class="flex items-center justify-between px-2 pt-1 relative">
                                                    <!-- Background track line -->
                                                    <div class="absolute top-[13px] inset-x-6 h-[2px] bg-slate-200 dark:bg-slate-800 z-0"></div>
                                                    <div class="absolute top-[13px] left-6 h-[2px] bg-gradient-to-r from-blue-600 to-emerald-500 z-0 transition-all duration-300"
                                                         style="width: {{ $perm->status === 'approved' ? '100' : ($perm->status_dept_head === 'approved' ? '50' : '0') }}%"></div>

                                                    <!-- Step 1: Draft / Diajukan -->
                                                    <div class="flex flex-col items-center z-10" title="Izin telah diajukan ke sistem">
                                                        <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[8px] bg-blue-600 text-white shadow shadow-blue-500/20">
                                                            ✓
                                                        </div>
                                                        <span class="text-[8px] font-bold text-slate-500 dark:text-slate-400 mt-1 uppercase tracking-wider">Diajukan</span>
                                                    </div>

                                                    <!-- Step 2: Kadiv Acc -->
                                                    <div class="flex flex-col items-center z-10">
                                                        @if($perm->status_dept_head === 'approved')
                                                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[8px] bg-emerald-600 text-white shadow shadow-emerald-500/20" title="Disetujui Kepala Divisi">
                                                                ✓
                                                            </div>
                                                        @elseif($perm->status_dept_head === 'rejected')
                                                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[8px] bg-rose-600 text-white shadow shadow-rose-500/20" title="Ditolak Kepala Divisi">
                                                                ✗
                                                            </div>
                                                        @else
                                                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[8px] bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700 text-slate-500 dark:text-slate-400 animate-pulse" title="Menunggu verifikasi Kepala Divisi">
                                                                ⏳
                                                            </div>
                                                        @endif
                                                        <span class="text-[8px] font-bold mt-1 uppercase tracking-wider {{ $perm->status_dept_head === 'approved' ? 'text-emerald-600 dark:text-emerald-450' : ($perm->status_dept_head === 'rejected' ? 'text-rose-6050 dark:text-rose-4550' : 'text-slate-500') }}">Kadiv</span>
                                                    </div>

                                                    <!-- Step 3: HR Acc -->
                                                    <div class="flex flex-col items-center z-10">
                                                        @if($perm->status_hr === 'approved')
                                                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[8px] bg-emerald-600 text-white shadow shadow-emerald-500/20" title="Disetujui HR Manager">
                                                                ✓
                                                            </div>
                                                        @elseif($perm->status_hr === 'rejected')
                                                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[8px] bg-rose-600 text-white shadow shadow-rose-500/20" title="Ditolak HR Manager">
                                                                ✗
                                                            </div>
                                                        @else
                                                            <div class="w-5 h-5 rounded-full flex items-center justify-center font-bold text-[8px] bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700 text-slate-500 dark:text-slate-400 {{ $perm->status_dept_head === 'approved' ? 'animate-pulse' : '' }}" title="Menunggu verifikasi HRD">
                                                                ⏳
                                                            </div>
                                                        @endif
                                                        <span class="text-[8px] font-bold mt-1 uppercase tracking-wider {{ $perm->status_hr === 'approved' ? 'text-emerald-600 dark:text-emerald-4550' : ($perm->status_hr === 'rejected' ? 'text-rose-6050 dark:text-rose-4550' : 'text-slate-500') }}">HRD</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-4 border-t border-slate-100 dark:border-white/5 mt-4 flex items-center gap-2">
                                            <button wire:click="viewDetail({{ $perm->id }})" type="button" class="flex-1 py-2 text-center rounded-lg bg-slate-100 dark:bg-slate-850 hover:bg-slate-200 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-white/5 font-extrabold text-[10px] transition-all">
                                                Detail & Lacak
                                            </button>
                                            
                                            @if($perm->status === 'approved')
                                                <a href="{{ route('letters.permission', $perm->id) }}" target="_blank"
                                                    class="flex-1 flex items-center justify-center gap-1 py-2 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 hover:bg-blue-500/20 dark:hover:bg-blue-500/20 dark:hover:text-blue-300 font-extrabold text-[10px] transition-all">
                                                    <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                    Cetak Surat
                                                </a>
                                            @else
                                                <button disabled class="flex-1 py-2 rounded-lg bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/5 text-slate-400 dark:text-slate-600 font-bold text-[10px] cursor-not-allowed text-center">
                                                    Menunggu
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- Pagination MyPermissions -->
                            <div class="mt-6">
                                {{ $myPermissions->links() }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        @endif

    </div>

    <!-- ========================================================== -->
    <!-- MODALS & DIALOGS -->
    <!-- ========================================================== -->

    <!-- Modal 1: Detail & Lacak Modal -->
    <div x-data="{ open: @entangle('showDetailModal') }" x-show="open"
        class="fixed inset-0 z-[120] overflow-y-auto flex items-center justify-center p-4 permissions-modal-backdrop"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-3xl permissions-modal rounded-3xl p-6 sm:p-8 shadow-2xl relative overflow-hidden flex flex-col max-h-[90vh]">
            <div class="absolute -right-20 -top-20 w-44 h-44 bg-blue-500/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex items-center justify-between mb-6 border-b border-slate-100 dark:border-white/5 pb-4">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="p-1 bg-blue-500/10 rounded-lg text-blue-600 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    Informasi Detail Pengajuan Izin
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-2xl font-bold focus:outline-none">×</button>
            </div>

            @if($selectedRequestForDetail)
                <div class="space-y-6 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-slate-200 dark:scrollbar-thumb-white/10 flex-1">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        
                        <!-- Left Info Column -->
                        <div class="md:col-span-7 space-y-4">
                            <div class="p-4 permissions-subcard rounded-2xl space-y-3.5">
                                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-5500 uppercase tracking-widest border-b border-slate-100 dark:border-white/5 pb-2">Profil Karyawan</h4>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-black text-sm uppercase">
                                        {{ strtoupper(substr($selectedRequestForDetail->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-slate-800 dark:text-white">{{ $selectedRequestForDetail->user->name }}</div>
                                        <div class="text-[10px] text-slate-5500 dark:text-slate-400 mt-0.5 font-mono">ID: {{ $selectedRequestForDetail->user->employee_id }} · Cabang: {{ $selectedRequestForDetail->user->branch->name ?? 'Default' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 permissions-subcard rounded-2xl space-y-3">
                                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-5500 uppercase tracking-widest border-b border-slate-100 dark:border-white/5 pb-2">Rincian Dispensasi</h4>
                                <div class="grid grid-cols-2 gap-4 text-xs font-medium text-slate-600 dark:text-slate-300">
                                    <div>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 block">Kategori Izin:</span>
                                        <span class="text-slate-800 dark:text-white font-bold mt-0.5 block">
                                            @if($selectedRequestForDetail->type === 'ijin_datang_terlambat') Izin Datang Terlambat
                                            @elseif($selectedRequestForDetail->type === 'ijin_pulang_awal') Izin Pulang Awal
                                            @elseif($selectedRequestForDetail->type === 'ijin_setengah_hari') Izin Setengah Hari
                                            @else Izin Tidak Masuk
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 block">Tanggal Izin:</span>
                                        <span class="text-slate-800 dark:text-white font-bold mt-0.5 block">{{ $selectedRequestForDetail->date->translatedFormat('d F Y') }}</span>
                                    </div>
                                    @if($selectedRequestForDetail->type !== 'ijin_tidak_masuk')
                                        <div>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-5500 block">Jam Mulai:</span>
                                            <span class="text-blue-600 dark:text-blue-400 font-bold mt-0.5 block">{{ substr($selectedRequestForDetail->start_time, 0, 5) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] text-slate-400 dark:text-slate-5500 block">Jam Selesai:</span>
                                            <span class="text-blue-600 dark:text-blue-400 font-bold mt-0.5 block">{{ substr($selectedRequestForDetail->end_time, 0, 5) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-3 border-t border-slate-100 dark:border-white/5">
                                    <span class="text-[10px] text-slate-450 dark:text-slate-500 block font-bold uppercase tracking-wider mb-1">Alasan Pengajuan:</span>
                                    <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed bg-white dark:bg-black/20 p-3 rounded-xl border border-slate-200 dark:border-white/5">{{ $selectedRequestForDetail->reason }}</p>
                                </div>

                                @if($selectedRequestForDetail->attachment_path)
                                    <div class="pt-3 border-t border-slate-100 dark:border-white/5 space-y-2">
                                        <span class="text-[10px] text-slate-450 dark:text-slate-500 block font-bold uppercase tracking-wider mb-1">Dokumen Lampiran:</span>
                                        @php
                                            $ext = pathinfo($selectedRequestForDetail->attachment_path, PATHINFO_EXTENSION);
                                            $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png']);
                                        @endphp
                                        @if($isImg)
                                            <div class="relative w-full max-h-40 rounded-xl overflow-hidden border border-slate-200 dark:border-white/10 bg-slate-100 dark:bg-black flex items-center justify-center">
                                                <img src="{{ asset('storage/' . $selectedRequestForDetail->attachment_path) }}" class="max-h-40 object-contain w-full">
                                            </div>
                                        @endif
                                        <a href="{{ asset('storage/' . $selectedRequestForDetail->attachment_path) }}" target="_blank" class="inline-flex items-center text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                                            <svg class="w-4 h-4 mr-1 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            Buka Dokumen di Tab Baru
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right Approval Timeline Column -->
                        <div class="md:col-span-5 space-y-4">
                            <div class="p-4 permissions-subcard rounded-2xl space-y-4">
                                <h4 class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest border-b border-slate-100 dark:border-white/5 pb-2">Jejak Persetujuan</h4>
                                
                                <div class="relative pl-6 space-y-6">
                                    <!-- Timeline line bar -->
                                    <div class="absolute left-2 top-2 bottom-2 w-0.5 bg-slate-200 dark:bg-slate-800 z-0"></div>

                                    <!-- Step 1: Diajukan -->
                                    <div class="relative z-10 flex items-start space-x-3">
                                        <div class="absolute -left-6 mt-1 w-4.5 h-4.5 rounded-full bg-blue-600 border-4 border-[#0d1527] shadow shadow-blue-500/20"></div>
                                        <div class="text-xs">
                                            <span class="font-bold text-slate-800 dark:text-white block">Pengajuan Terkirim</span>
                                            <span class="text-[9px] text-slate-450 dark:text-slate-500 font-mono">{{ $selectedRequestForDetail->created_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                        </div>
                                    </div>

                                    <!-- Step 2: Kadiv Acc -->
                                    <div class="relative z-10 flex items-start space-x-3">
                                        @if($selectedRequestForDetail->status_dept_head === 'approved')
                                            <div class="absolute -left-6 mt-1 w-4.5 h-4.5 rounded-full bg-emerald-500 border-4 border-[#0d1527] shadow shadow-emerald-500/20"></div>
                                            <div class="text-xs">
                                                <span class="font-bold text-emerald-600 dark:text-emerald-400 block">Disetujui Kepala Divisi</span>
                                                <span class="text-[9px] text-slate-500 dark:text-slate-450 font-mono block mt-0.5">Oleh: {{ $selectedRequestForDetail->deptHead->name ?? 'N/A' }}</span>
                                                @if($selectedRequestForDetail->dept_head_approved_at)
                                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-mono block mt-0.5">{{ $selectedRequestForDetail->dept_head_approved_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                                @endif
                                            </div>
                                        @elseif($selectedRequestForDetail->status_dept_head === 'rejected')
                                            <div class="absolute -left-6 mt-1 w-4.5 h-4.5 rounded-full bg-rose-500 border-4 border-[#0d1527] shadow shadow-rose-500/20"></div>
                                            <div class="text-xs">
                                                <span class="font-bold text-rose-600 dark:text-rose-450 block">Ditolak Kepala Divisi</span>
                                                <span class="text-[9px] text-slate-500 dark:text-slate-450 font-mono block mt-0.5">Oleh: {{ $selectedRequestForDetail->deptHead->name ?? 'N/A' }}</span>
                                                @if($selectedRequestForDetail->dept_head_approved_at)
                                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-mono block mt-0.5">{{ $selectedRequestForDetail->dept_head_approved_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="absolute -left-6 mt-1 w-4.5 h-4.5 rounded-full bg-slate-200 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-700 animate-pulse"></div>
                                            <div class="text-xs">
                                                <span class="font-bold text-slate-400 dark:text-slate-500 block">Menunggu Kepala Divisi</span>
                                                <span class="text-[9px] text-slate-500 dark:text-slate-600 block mt-0.5">Review operasional / Kadiv</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Step 3: HR Acc -->
                                    <div class="relative z-10 flex items-start space-x-3">
                                        @if($selectedRequestForDetail->status_hr === 'approved')
                                            <div class="absolute -left-6 mt-1 w-4.5 h-4.5 rounded-full bg-emerald-500 border-4 border-[#0d1527] shadow shadow-emerald-500/20"></div>
                                            <div class="text-xs">
                                                <span class="font-bold text-emerald-600 dark:text-emerald-400 block">Disetujui HR Manager</span>
                                                <span class="text-[9px] text-slate-500 dark:text-slate-450 font-mono block mt-0.5">Oleh: {{ $selectedRequestForDetail->hr->name ?? 'N/A' }}</span>
                                                @if($selectedRequestForDetail->hr_approved_at)
                                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-mono block mt-0.5">{{ $selectedRequestForDetail->hr_approved_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                                @endif
                                            </div>
                                        @elseif($selectedRequestForDetail->status_hr === 'rejected')
                                            <div class="absolute -left-6 mt-1 w-4.5 h-4.5 rounded-full bg-rose-500 border-4 border-[#0d1527] shadow shadow-rose-500/20"></div>
                                            <div class="text-xs">
                                                <span class="font-bold text-rose-600 dark:text-rose-4550 block">Ditolak HR Manager</span>
                                                <span class="text-[9px] text-slate-500 dark:text-slate-450 font-mono block mt-0.5">Oleh: {{ $selectedRequestForDetail->hr->name ?? 'N/A' }}</span>
                                                @if($selectedRequestForDetail->hr_approved_at)
                                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-mono block mt-0.5">{{ $selectedRequestForDetail->hr_approved_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="absolute -left-6 mt-1 w-4.5 h-4.5 rounded-full bg-slate-200 dark:bg-slate-800 border-2 border-slate-300 dark:border-slate-700"></div>
                                            <div class="text-xs">
                                                <span class="font-bold text-slate-400 dark:text-slate-500 block">Menunggu HR Manager</span>
                                                <span class="text-[9px] text-slate-500 dark:text-slate-600 block mt-0.5">Persetujuan akhir & database sync</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($selectedRequestForDetail->approval_notes)
                                    <div class="pt-3.5 border-t border-slate-100 dark:border-white/5">
                                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-5500 uppercase tracking-widest mb-1.5 block">Log Catatan Catatan Verifikator:</span>
                                        <p class="text-[11px] text-amber-800 dark:text-amber-300 font-bold bg-amber-500/5 p-3 border border-amber-500/10 rounded-xl leading-relaxed italic">
                                            {{ $selectedRequestForDetail->approval_notes }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="flex justify-between items-center pt-5 border-t border-slate-100 dark:border-white/5 mt-6">
                    <div>
                        @if($selectedRequestForDetail->status === 'approved')
                            <a href="{{ route('letters.permission', $selectedRequestForDetail->id) }}" target="_blank"
                                class="btn-sm btn-primary py-2.5 px-4 font-black flex items-center gap-1.5 shadow-[0_0_15px_rgba(59,130,246,0.25)]">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Surat Dispensasi Resmi
                            </a>
                        @endif
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        @if($selectedRequestForDetail->status === 'pending' && $selectedRequestForDetail->user_id !== auth()->id())
                            @if($isManager && $selectedRequestForDetail->status_dept_head === 'pending')
                                <button wire:click="approveDeptHead({{ $selectedRequestForDetail->id }})" type="button" class="btn-sm btn-success font-black">
                                    Setujui Kadiv
                                </button>
                            @endif
                            @if($isHr && $selectedRequestForDetail->status_hr === 'pending')
                                <button wire:click="approveHr({{ $selectedRequestForDetail->id }})" type="button" class="btn-sm btn-primary font-black">
                                    Setujui HRD
                                </button>
                            @endif
                            <button wire:click="openRejectionModal({{ $selectedRequestForDetail->id }})" type="button" class="btn-sm btn-danger-outline font-black">
                                Tolak Izin
                            </button>
                        @endif
                        <button @click="open = false" type="button" class="btn-sm btn-secondary font-black">
                            Tutup
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal 2: Rejection Notes Modal -->
    <div x-data="{ open: @entangle('showRejectionModal') }" x-show="open"
        class="fixed inset-0 z-[130] overflow-y-auto flex items-center justify-center p-4 permissions-modal-backdrop"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-md permissions-modal rounded-2xl p-6 shadow-2xl relative overflow-hidden flex flex-col">
            <div class="absolute -right-16 -top-16 w-32 h-32 bg-rose-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-white/5 pb-3">
                <h3 class="text-base sm:text-lg font-bold text-rose-600 dark:text-rose-450 flex items-center gap-1.5">
                    ❌ Masukkan Alasan Penolakan
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-xl font-bold">×</button>
            </div>

            <form wire:submit.prevent="submitRejection" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Catatan Penolakan / Alasan</label>
                    <textarea wire:model="actionNotes" rows="3" required placeholder="Contoh: Lampiran tidak terbaca / operasional divisi sedang tinggi..." class="w-full permissions-input rounded-xl p-3 text-xs focus:outline-none transition-all resize-none"></textarea>
                    @error('actionNotes')
                        <span class="text-xs text-rose-500 dark:text-rose-450 font-bold block mt-1.5">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button @click="open = false" type="button" class="btn-xs px-4 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/5 text-slate-600 dark:text-slate-350 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold rounded-lg transition-all">
                        Batal
                    </button>
                    <button type="submit" class="btn-xs px-4 py-2 bg-rose-600 hover:bg-rose-550 text-white font-bold rounded-lg transition-all shadow-[0_0_15px_rgba(225,29,72,0.2)]">
                        Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Approval Notes Modal -->
    <div x-data="{ open: @entangle('showApprovalModal') }" x-show="open"
        class="fixed inset-0 z-[130] overflow-y-auto flex items-center justify-center p-4 permissions-modal-backdrop"
        style="display: none;" x-transition>
        <div @click.away="open = false"
            class="w-full max-w-md permissions-modal rounded-2xl p-6 shadow-2xl relative overflow-hidden flex flex-col">
            <div class="absolute -right-16 -top-16 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl pointer-events-none"></div>

            <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-white/5 pb-3">
                <h3 class="text-base sm:text-lg font-bold text-emerald-600 dark:text-emerald-4550 flex items-center gap-1.5">
                    ✍️ Setujui dengan Catatan (Opsional)
                </h3>
                <button @click="open = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-xl font-bold">×</button>
            </div>

            <form wire:submit.prevent="submitApproval" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-2">Tulis Catatan Persetujuan</label>
                    <textarea wire:model="actionNotes" rows="3" placeholder="Tulis catatan (opsional). Contoh: Disetujui karena ada tugas luar divisi..." class="w-full permissions-input rounded-xl p-3 text-xs focus:outline-none transition-all resize-none"></textarea>
                    @error('actionNotes')
                        <span class="text-xs text-rose-500 dark:text-rose-450 font-bold block mt-1.5">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button @click="open = false" type="button" class="btn-xs px-4 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-white/5 text-slate-600 dark:text-slate-350 hover:bg-slate-200 dark:hover:bg-slate-700 font-bold rounded-lg transition-all">
                        Batal
                    </button>
                    <button type="submit" class="btn-xs px-4 py-2 bg-emerald-650 hover:bg-emerald-600 text-white font-bold rounded-lg transition-all shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                        Setujui Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
