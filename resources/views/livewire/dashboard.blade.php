<!-- Inject Leaflet Assets directly to avoid bundle overhead -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="py-8 min-h-screen" x-data="{
    selectedLog: null,
    showModal: false,
    init() {
        this.$watch('showModal', value => {
            document.body.style.overflow = value ? 'hidden' : '';
        });
    },
    toast: { show: false, message: '', type: 'success' },
    showToast(message, type = 'success') {
        this.toast.message = message;
        this.toast.type = type;
        this.toast.show = true;
        setTimeout(() => { this.toast.show = false; }, 5000);
    },
    detailMap: null,
    detailUserMarker: null,
    initDetailMap() {
        this.$nextTick(() => {
            if (!this.selectedLog) return;
            const lat = parseFloat(this.selectedLog.latitude);
            const lng = parseFloat(this.selectedLog.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            setTimeout(() => {
                const mapEl = document.getElementById('detail-map');
                if (!mapEl) { console.warn('[Map] detail-map element not found'); return; }

                if (this.detailMap) {
                    try { this.detailMap.remove(); } catch (e) { console.error(e); }
                    this.detailMap = null;
                    this.detailUserMarker = null;
                }

                this.detailMap = L.map('detail-map', { zoomControl: true, attributionControl: false }).setView([lat, lng], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(this.detailMap);

                const userIcon = L.divIcon({
                    className: 'custom-user-dot',
                    html: `<div class='relative flex items-center justify-center'>
                             <div class='absolute w-8 h-8 rounded-full bg-primary/30 animate-ping'></div>
                             <div class='relative w-3.5 h-3.5 bg-primary rounded-full border-2 border-white'></div>
                           </div>`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12]
                });

                this.detailUserMarker = L.marker([lat, lng], { icon: userIcon }).addTo(this.detailMap);
                this.detailUserMarker.bindPopup('<strong class=\'text-xs text-slate-800\'>Lokasi Absensi</strong>').openPopup();

                [100, 300, 600, 1200, 2000].forEach(delay => {
                    setTimeout(() => { if (this.detailMap) this.detailMap.invalidateSize(); }, delay);
                });
            }, 250);
        });
    }
}"
    @attendance-updated.window="
         const eventDetail = $event.detail[0] || {};
         const eventType = eventDetail.type === 'checkin' ? 'Masuk' : 'Keluar';
         const isAdmin = {{ auth()->user()->hasAnyRole(['super_admin', 'hr_admin'])? 'true': 'false' }};
         if (isAdmin) {
             showToast('Log Absensi Baru: Seseorang baru saja melakukan Absen ' + eventType + '!', 'info');
         } else {
             showToast('Absensi berhasil terverifikasi real-time!', 'success');
         }
     ">

    @php
        // Catmull-Rom -> cubic bezier smoothing for a list of [x, y] points.
        $smoothLine = function (array $pts) {
            $n = count($pts);
            if ($n < 2) return '';
            $d = 'M' . $pts[0][0] . ',' . $pts[0][1];
            if ($n === 2) return $d . ' L' . $pts[1][0] . ',' . $pts[1][1];
            $t = 0.18;
            for ($i = 0; $i < $n - 1; $i++) {
                $p0 = $pts[$i - 1] ?? $pts[$i];
                $p1 = $pts[$i];
                $p2 = $pts[$i + 1];
                $p3 = $pts[$i + 2] ?? $p2;
                $c1x = round($p1[0] + ($p2[0] - $p0[0]) * $t, 1);
                $c1y = round($p1[1] + ($p2[1] - $p0[1]) * $t, 1);
                $c2x = round($p2[0] - ($p3[0] - $p1[0]) * $t, 1);
                $c2y = round($p2[1] - ($p3[1] - $p1[1]) * $t, 1);
                $d .= ' C' . $c1x . ',' . $c1y . ' ' . $c2x . ',' . $c2y . ' ' . $p2[0] . ',' . $p2[1];
            }
            return $d;
        };
        // Map a data list to [x, y] points inside a viewBox.
        $sparkXY = function (array $data, $w = 200, $h = 48, $pad = 6) {
            $n = count($data);
            $max = max($data ?: [0]); $min = min($data ?: [0]);
            $range = max(1, $max - $min);
            $pts = [];
            foreach ($data as $i => $v) {
                $x = $pad + ($n <= 1 ? 0 : $i * ($w - 2 * $pad) / ($n - 1));
                $y = $h - $pad - ($v - $min) / $range * ($h - 2 * $pad);
                $pts[] = [round($x, 1), round($y, 1)];
            }
            return $pts;
        };
        $rangeStart = now()->copy()->subDays(6);
        $rangeEnd = now();
    @endphp

    <div class="px-4 sm:px-6 lg:px-8">

        {{-- Hero --}}
        @php
            $heroTz = cache()->get('settings.timezone', 'Asia/Jakarta');
            $heroTzLabel = ['Asia/Jakarta' => 'WIB', 'Asia/Makassar' => 'WITA', 'Asia/Jayapura' => 'WIT'][$heroTz] ?? 'WIB';
        @endphp
        <div class="relative overflow-hidden rounded-3xl border border-blue-500/10 shadow-lg mb-8 p-6 sm:p-8 text-white"
            style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #0f172a 100%);">

            {{-- Decorative concentric rings --}}
            <svg class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 text-white opacity-[0.06]" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="1.25" aria-hidden="true">
                <circle cx="100" cy="100" r="42" /><circle cx="100" cy="100" r="66" /><circle cx="100" cy="100" r="90" />
            </svg>
            <div class="pointer-events-none absolute -left-10 -bottom-16 h-56 w-56 rounded-full" style="background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);"></div>

            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                {{-- Greeting --}}
                <div class="min-w-0">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 px-3 py-1 text-xs font-semibold text-emerald-300">
                        <span class="relative flex h-1.5 w-1.5">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-current opacity-60 animate-ping"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        </span>
                        Workspace aktif
                    </span>
                    <p class="text-sm text-blue-200/80 mt-3.5">Selamat datang kembali,</p>
                    <h1 class="mt-0.5 text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight text-white">
                        <span>{{ auth()->user()->name }}</span> <span>👋</span>
                    </h1>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/10 px-2.5 py-1 text-xs font-medium text-white/90 backdrop-blur-sm">
                            <svg class="h-3.5 w-3.5 text-blue-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ auth()->user()->branch->name ?? 'HQ Workspace' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/10 bg-white/10 px-2.5 py-1 text-xs font-medium text-white/90 backdrop-blur-sm">
                            <svg class="h-3.5 w-3.5 text-blue-200" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>

                {{-- Live clock + actions --}}
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center lg:flex-col lg:items-end">
                    <div x-data="{
                            time: '--:--:--',
                            init() {
                                const fmt = new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: '{{ $heroTz }}' });
                                const tick = () => { this.time = fmt.format(new Date()); };
                                tick();
                                this._t = setInterval(tick, 1000);
                            },
                            destroy() { clearInterval(this._t); }
                        }"
                        class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/10 px-4 py-3 backdrop-blur-sm shadow-md">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-white/10 text-white">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <div>
                            <div class="text-xl font-bold tabular-nums tracking-tight text-white leading-none" x-text="time">--:--:--</div>
                            <div class="mt-1 text-[11px] font-semibold uppercase tracking-wide text-blue-200">Waktu {{ $heroTzLabel }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('attendance.checkin') }}" class="inline-flex items-center justify-center gap-2 rounded-xl h-10 px-4 text-xs font-semibold text-blue-700 bg-white hover:bg-blue-50 hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-blue-900/35">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Absen Sekarang
                        </a>
                        <a href="{{ route('attendance.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl h-10 px-4 text-xs font-semibold text-white border border-white/20 hover:bg-white/10 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Riwayat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        @if (session()->has('success'))
            <div class="card border-success/30 bg-success-soft p-5 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 rounded-2xl">
                <div class="flex items-center gap-4">
                    <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-success text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </span>
                    <div>
                        <h4 class="text-sm font-semibold text-fg">Absensi berhasil terverifikasi!</h4>
                        <p class="text-xs text-fg-muted mt-1">Face recognition &amp; geofencing pintar berhasil mendata sesi Anda secara akurat.</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="badge-rect-success">{{ session('success') }}</span>
                    @if (session()->has('warning'))<span class="badge-rect-warning">{{ session('warning') }}</span>@endif
                </div>
            </div>
        @endif

        {{-- KPI cards with sparklines --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-8">
            @foreach ([
                ['Bulan Ini', $stats['total_attendance'], 'Log kehadiran tercatat', 'bg-info-soft text-info', '--info', $spark['total'] ?? [], 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', true],
                ['Tepat Waktu', $stats['on_time'], 'Absensi tepat waktu', 'bg-success-soft text-success', '--success', $spark['on_time'] ?? [], 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', true],
                ['Terlambat', $stats['late'], 'Keterlambatan kedatangan', 'bg-warning-soft text-warning', '--warning', $spark['late'] ?? [], 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', false],
                ['Peringatan', $stats['suspicious_events'], 'Anomali terdeteksi', 'bg-danger-soft text-danger', '--danger', $spark['suspicious'] ?? [], 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', false],
            ] as [$label, $value, $caption, $iconClass, $cssVar, $sparkData, $icon, $upGood])
                @php
                    $last7 = array_slice($sparkData, 7);
                    $prev7 = array_slice($sparkData, 0, 7);
                    $sumL = array_sum($last7); $sumP = array_sum($prev7);
                    $deltaPct = $sumP > 0 ? (int) round(($sumL - $sumP) / $sumP * 100) : ($sumL > 0 ? 100 : 0);
                    $isGood = ($deltaPct >= 0) === $upGood;
                    $trendCls = $deltaPct == 0 ? 'bg-surface-muted text-fg-muted' : ($isGood ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/15' : 'bg-rose-500/10 text-rose-600 border border-rose-500/15');
                @endphp
                <div class="card card-hover p-5 flex flex-col relative overflow-hidden bg-surface border-border hover:border-primary/20"
                    style="transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); border-radius: 1.25rem;">
                    <div class="flex items-start justify-between mb-3.5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl"
                            style="background-color: color-mix(in srgb, var({{ $cssVar }}) 10%, transparent); color: var({{ $cssVar }});">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" /></svg>
                        </span>
                        <a href="{{ route('attendance.index') }}" class="flex h-7 w-7 items-center justify-center rounded-full text-fg-subtle hover:text-primary hover:bg-surface-muted transition-colors" title="Lihat detail">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-fg-muted">{{ $label }}</span>
                    <div class="mt-1 flex items-center gap-2">
                        <p class="heading-value text-2xl font-extrabold text-fg">{{ $value }}</p>
                        @if ($deltaPct != 0)
                            <span class="inline-flex items-center gap-0.5 rounded-full px-2 py-0.5 text-[10px] font-bold border {{ $trendCls }}" title="Dibanding 7 hari sebelumnya">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $deltaPct >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}" /></svg>
                                {{ abs($deltaPct) }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-fg-subtle mt-1.5 truncate normal-case tracking-normal">{{ $caption }}</p>
                    @if (count($sparkData) > 1)
                        @php $sp = $sparkXY($sparkData); $sLine = $smoothLine($sp); $sArea = $sLine . ' L' . end($sp)[0] . ',42 L' . $sp[0][0] . ',42 Z'; @endphp
                        <svg viewBox="0 0 200 48" preserveAspectRatio="none" class="w-full h-10 mt-3 overflow-visible">
                            <defs>
                                <linearGradient id="kpi-grad-{{ $loop->index }}" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0" stop-color="var({{ $cssVar }})" stop-opacity="0.22" />
                                    <stop offset="1" stop-color="var({{ $cssVar }})" stop-opacity="0" />
                                </linearGradient>
                            </defs>
                            <path d="{{ $sArea }}" fill="url(#kpi-grad-{{ $loop->index }})" />
                            <path d="{{ $sLine }}" fill="none" stroke="var({{ $cssVar }})" stroke-width="2.75" stroke-linecap="round" stroke-linejoin="round" vector-effect="non-scaling-stroke" />
                        </svg>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Analytics grid --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">

            {{-- Left: weekly chart + mini metrics --}}
            <div class="xl:col-span-2 space-y-6">

                {{-- Weekly summary line chart --}}
                <div class="card p-5 sm:p-6 rounded-2xl">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                        <h3 class="heading-3 flex items-center gap-2">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>
                            Ringkasan Kehadiran
                        </h3>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-border bg-surface-muted px-3 h-8 text-xs font-medium text-fg-muted">7 Hari Terakhir</span>
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-border bg-surface-muted px-3 h-8 text-xs font-medium text-fg-muted">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $rangeStart->translatedFormat('j M') }} – {{ $rangeEnd->translatedFormat('j M Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        @foreach ([['Tepat Waktu', 'bg-success'], ['Terlambat', 'bg-warning'], ['Tidak Hadir', 'bg-danger']] as [$lg, $dot])
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-fg-muted">
                                <span class="h-2.5 w-2.5 rounded-full {{ $dot }} shadow-sm"></span> {{ $lg }}
                            </span>
                        @endforeach
                    </div>

                    @php
                        $n = count($weekSeries);
                        $W = 720; $H = 248; $padL = 30; $padR = 16; $padT = 16; $padB = 36;
                        $maxVal = 0;
                        foreach ($weekSeries as $d) { $maxVal = max($maxVal, $d['on_time'], $d['late'], $d['absent']); }
                        $yMax = max(4, (int) (ceil($maxVal / 4) * 4));
                        $innerW = $W - $padL - $padR; $innerH = $H - $padT - $padB;
                        $px = fn ($i) => round($padL + ($n <= 1 ? 0 : $i * $innerW / ($n - 1)), 1);
                        $py = fn ($v) => round($padT + $innerH - ($yMax > 0 ? $v / $yMax * $innerH : 0), 1);
                        $ptsOf = function ($key) use ($weekSeries, $px, $py) {
                            $p = [];
                            foreach ($weekSeries as $i => $d) { $p[] = [$px($i), $py($d[$key])]; }
                            return $p;
                        };
                        $onPts = $ptsOf('on_time'); $latePts = $ptsOf('late'); $absPts = $ptsOf('absent');
                        $onLine = $smoothLine($onPts);
                        $onArea = $onLine . ' L' . $px($n - 1) . ',' . $py(0) . ' L' . $px(0) . ',' . $py(0) . ' Z';
                        $ticks = [0, $yMax / 4, $yMax / 2, 3 * $yMax / 4, $yMax];
                    @endphp

                    <div class="overflow-hidden">
                        <svg viewBox="0 0 {{ $W }} {{ $H }}" class="w-full h-auto" font-family="inherit">
                            <defs>
                                <linearGradient id="chart-grad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0" stop-color="var(--success)" stop-opacity="0.18" />
                                    <stop offset="0.95" stop-color="var(--success)" stop-opacity="0" />
                                </linearGradient>
                                <filter id="glow-success" x="-10%" y="-10%" width="120%" height="120%">
                                    <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#10b981" flood-opacity="0.2" />
                                </filter>
                                <filter id="glow-warning" x="-10%" y="-10%" width="120%" height="120%">
                                    <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#f59e0b" flood-opacity="0.2" />
                                </filter>
                                <filter id="glow-danger" x="-10%" y="-10%" width="120%" height="120%">
                                    <feDropShadow dx="0" dy="4" stdDeviation="3" flood-color="#ef4444" flood-opacity="0.2" />
                                </filter>
                            </defs>

                            {{-- Gridlines + Y labels --}}
                            @foreach ($ticks as $t)
                                <line x1="{{ $padL }}" y1="{{ $py($t) }}" x2="{{ $W - $padR }}" y2="{{ $py($t) }}" stroke="var(--border)" stroke-width="1" stroke-dasharray="{{ $t == 0 ? '0' : '3 4' }}" />
                                <text x="{{ $padL - 8 }}" y="{{ $py($t) + 4 }}" text-anchor="end" font-size="11" fill="var(--fg-subtle)">{{ (int) $t }}</text>
                            @endforeach

                            {{-- Area + smooth series lines --}}
                            <path d="{{ $onArea }}" fill="url(#chart-grad)" />
                            <path d="{{ $smoothLine($absPts) }}" fill="none" stroke="var(--danger)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" filter="url(#glow-danger)" />
                            <path d="{{ $smoothLine($latePts) }}" fill="none" stroke="var(--warning)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" filter="url(#glow-warning)" />
                            <path d="{{ $onLine }}" fill="none" stroke="var(--success)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" filter="url(#glow-success)" />

                            {{-- Dots + X labels --}}
                            @foreach ($weekSeries as $i => $d)
                                <circle cx="{{ $px($i) }}" cy="{{ $py($d['absent']) }}" r="4.5" fill="var(--danger)" stroke="var(--surface)" stroke-width="2.5"><title>{{ $d['label'] }} {{ $d['date'] }} — Tidak Hadir: {{ $d['absent'] }}</title></circle>
                                <circle cx="{{ $px($i) }}" cy="{{ $py($d['late']) }}" r="4.5" fill="var(--warning)" stroke="var(--surface)" stroke-width="2.5"><title>{{ $d['label'] }} {{ $d['date'] }} — Terlambat: {{ $d['late'] }}</title></circle>
                                <circle cx="{{ $px($i) }}" cy="{{ $py($d['on_time']) }}" r="4.5" fill="var(--success)" stroke="var(--surface)" stroke-width="2.5"><title>{{ $d['label'] }} {{ $d['date'] }} — Tepat Waktu: {{ $d['on_time'] }}</title></circle>
                                <text x="{{ $px($i) }}" y="{{ $H - 14 }}" text-anchor="middle" font-size="11.5" font-weight="600" fill="var(--fg-muted)">{{ $d['label'] }}</text>
                                <text x="{{ $px($i) }}" y="{{ $H - 2 }}" text-anchor="middle" font-size="10" fill="var(--fg-subtle)">{{ $d['date'] }}</text>
                            @endforeach
                        </svg>
                    </div>
                </div>

                {{-- Mini metrics --}}
                @php
                    $delta = $metrics['attendance_delta'] ?? 0;
                    $comp = $metrics['compliance'] ?? 0;
                    $compLabel = $comp >= 90 ? 'Sangat Baik' : ($comp >= 75 ? 'Baik' : ($comp >= 50 ? 'Cukup' : 'Perlu Perhatian'));
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Avg attendance --}}
                    <div class="card card-hover p-5 rounded-2xl">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-success-soft text-success mb-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-fg-muted">Rata-rata Kehadiran</span>
                        <p class="heading-value text-2xl font-extrabold text-fg mt-0.5">{{ $metrics['attendance_rate'] ?? 0 }}%</p>
                        <p class="text-xs mt-1.5 inline-flex items-center gap-1 {{ $delta >= 0 ? 'text-success' : 'text-danger' }}">
                            @if ($delta >= 0)
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" /></svg>
                            @else
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            @endif
                            {{ abs($delta) }}% <span class="text-fg-subtle">vs minggu lalu</span>
                        </p>
                    </div>
                    {{-- Best day --}}
                    <div class="card card-hover p-5 rounded-2xl">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-info-soft text-info mb-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-fg-muted">Hari Terbaik</span>
                        <p class="text-xl font-extrabold text-fg mt-0.5 truncate">{{ $metrics['best_day'] ?? '-' }}</p>
                        <p class="text-[11px] text-fg-subtle mt-1.5">{{ $metrics['best_day_count'] ?? 0 }} tepat waktu</p>
                    </div>
                    {{-- Total hours --}}
                    <div class="card card-hover p-5 rounded-2xl">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-soft text-primary mb-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-fg-muted">Total Jam Kerja</span>
                        <p class="text-xl font-extrabold tabular-nums text-fg mt-0.5">{{ $metrics['total_hours'] ?? '0j 0m' }}</p>
                        <p class="text-[11px] text-fg-subtle mt-1.5">Minggu ini</p>
                    </div>
                    {{-- Compliance --}}
                    <div class="card card-hover p-5 rounded-2xl">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-warning-soft text-warning mb-3">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-fg-muted">Kepatuhan</span>
                        <p class="heading-value text-2xl font-extrabold text-fg mt-0.5">{{ $comp }}%</p>
                        <p class="text-[11px] mt-1.5 font-bold {{ $comp >= 75 ? 'text-success' : ($comp >= 50 ? 'text-warning' : 'text-danger') }}">{{ $compLabel }}</p>
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="space-y-6">

                {{-- Upcoming --}}
                <div class="card p-5 sm:p-6 rounded-2xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="heading-3 flex items-center gap-2">
                            <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            Akan Datang
                        </h3>
                        <a href="{{ route('leaves.index') }}" class="text-xs font-semibold text-primary hover:underline">Lihat semua</a>
                    </div>
                    @forelse ($upcoming as $ev)
                        <div class="flex items-center gap-3.5 p-3 rounded-xl hover:bg-surface-muted transition-all duration-150 {{ ! $loop->last ? 'border-b border-border/50' : '' }}">
                            <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl"
                                style="background-color: color-mix(in srgb, var(--{{ $ev['tone'] === 'success' ? 'success' : 'warning' }}) 10%, transparent); color: var(--{{ $ev['tone'] === 'success' ? 'success' : 'warning' }});">
                                @if ($ev['tone'] === 'success')
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                @else
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-fg truncate">{{ $ev['title'] }}</p>
                                <p class="text-[11px] text-fg-muted mt-0.5">{{ $ev['subtitle'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-xl bg-surface-muted text-fg-subtle mb-3">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="label-sm text-xs font-semibold text-fg-muted">Tidak ada agenda cuti atau izin mendatang.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Distribution donut --}}
                <div class="card p-5 sm:p-6 rounded-2xl">
                    <h3 class="heading-3 flex items-center gap-2 mb-5">
                        <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" /></svg>
                        Distribusi Kehadiran
                    </h3>
                    @php
                        $dTotal = $distribution['total'] ?? 0;
                        $circ = 2 * M_PI * 40;
                        $acc = 0;
                        $segs = [
                            ['v' => $distribution['on_time'] ?? 0, 'c' => 'text-success', 'l' => 'Tepat Waktu', 'p' => $distribution['on_time_pct'] ?? 0],
                            ['v' => $distribution['late'] ?? 0, 'c' => 'text-warning', 'l' => 'Terlambat', 'p' => $distribution['late_pct'] ?? 0],
                            ['v' => $distribution['absent'] ?? 0, 'c' => 'text-danger', 'l' => 'Tidak Hadir', 'p' => $distribution['absent_pct'] ?? 0],
                        ];
                    @endphp
                    <div class="flex items-center gap-6">
                        <div class="relative flex-shrink-0">
                            <svg viewBox="0 0 100 100" class="h-28 w-28 -rotate-90">
                                <circle cx="50" cy="50" r="40" fill="none" stroke-width="13" class="text-surface-muted" stroke="currentColor" />
                                @foreach ($segs as $s)
                                    @php $len = $dTotal > 0 ? $s['v'] / $dTotal * $circ : 0; @endphp
                                    @if ($len > 0)
                                        <circle cx="50" cy="50" r="40" fill="none" stroke-width="13" stroke-linecap="round" stroke="currentColor" class="{{ $s['c'] }}"
                                            stroke-dasharray="{{ round($len, 2) }} {{ round($circ - $len, 2) }}" stroke-dashoffset="{{ round(-$acc, 2) }}"><title>{{ $s['l'] }}: {{ $s['v'] }} ({{ $s['p'] }}%)</title></circle>
                                        @php $acc += $len; @endphp
                                    @endif
                                @endforeach
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-bold tabular-nums text-fg leading-none">{{ $dTotal }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wide text-fg-subtle mt-0.5">Total</span>
                            </div>
                        </div>
                        <div class="flex-1 space-y-3">
                            @foreach ([
                                ['Tepat Waktu', 'bg-success', $distribution['on_time'] ?? 0, $distribution['on_time_pct'] ?? 0],
                                ['Terlambat', 'bg-warning', $distribution['late'] ?? 0, $distribution['late_pct'] ?? 0],
                                ['Tidak Hadir', 'bg-danger', $distribution['absent'] ?? 0, $distribution['absent_pct'] ?? 0],
                            ] as [$lg, $dot, $cnt, $pct])
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex items-center gap-2 text-sm text-fg-muted font-semibold">
                                        <span class="h-2.5 w-2.5 rounded-full {{ $dot }}"></span> {{ $lg }}
                                    </span>
                                    <span class="text-sm font-bold text-fg tabular-nums">{{ $cnt }} <span class="text-fg-subtle font-normal text-xs">({{ $pct }}%)</span></span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Consistency promo --}}
                <div class="card bg-primary-soft border-primary/10 p-5 flex items-start gap-4 rounded-2xl">
                    <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-primary text-primary-fg shadow-md shadow-primary/20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                    </span>
                    <div>
                        <h4 class="text-sm font-bold text-fg">Tetap jaga konsistensi!</h4>
                        <p class="text-xs text-fg-muted mt-1 leading-relaxed">Kehadiran yang baik mencerminkan kinerja yang profesional dan menjaga skor risiko tetap rendah.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's attendance (real-time) --}}
        <div class="card rounded-2xl">
            <div class="px-6 py-5 border-b border-border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="heading-2 text-lg font-bold text-fg">Kehadiran Hari Ini</h3>
                    <p class="label-sm mt-1 text-xs text-fg-muted">Status kehadiran real-time. Klik kartu untuk melihat detail absensi.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/15 px-3 py-1 text-xs font-semibold text-emerald-600 self-start sm:self-auto">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Verifikasi aktif
                </span>
            </div>

            <div class="p-6">
                @if ($todayAttendance->isEmpty())
                    <div class="text-center py-14 max-w-sm mx-auto">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-primary-soft text-primary mb-5 shadow-sm">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h4 class="heading-3 text-base font-bold text-fg">Gerbang absensi terbuka</h4>
                        <p class="label-sm mt-2 text-xs text-fg-subtle">Anda belum mencatat kehadiran hari ini. Pastikan izin GPS aktif pada perangkat Anda sebelum memulai absensi.</p>
                        <a href="{{ route('attendance.checkin') }}" class="btn-primary btn-sm mt-6">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Absen masuk sekarang
                        </a>
                    </div>
                @else
                    <div class="space-y-3.5">
                        @foreach ($todayAttendance as $attendance)
                            <div @click="selectedLog = {{ json_encode([
                                'id' => $attendance->id,
                                'type' => $attendance->type === 'checkin' ? 'Absen Masuk' : 'Absen Keluar',
                                'timestamp' => $attendance->timestamp->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->translatedFormat('H:i:s, d F Y'),
                                'latitude' => $attendance->latitude,
                                'longitude' => $attendance->longitude,
                                'accuracy' => $attendance->accuracy,
                                'ip_address' => $attendance->ip_address,
                                'work_mode' => strtoupper($attendance->work_mode ?? 'office'),
                                'risk_score' => $attendance->risk_score ?? 0,
                                'risk_level' =>
                                    $attendance->risk_level === 'high' ? 'Tinggi' : ($attendance->risk_level === 'medium' ? 'Sedang' : 'Rendah'),
                                'risk_class' => $attendance->risk_level,
                                'status' =>
                                    $attendance->status === 'approved'
                                        ? 'Disetujui'
                                        : ($attendance->status === 'flagged'
                                            ? 'Dicurigai'
                                            : 'Diproses'),
                                'status_class' => $attendance->status,
                                'is_late' => $attendance->is_late,
                                'selfie_url' => $attendance->selfie_path ? asset('storage/' . $attendance->selfie_path) : null,
                                'notes' => $attendance->notes ?? 'Tidak ada catatan tambahan.',
                                'branch_name' => $attendance->branch->name ?? 'HQ Workspace',
                                'device_hash' => substr(md5($attendance->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                                'employee_name' => $attendance->user->name ?? 'Karyawan',
                            ]) }}; showModal = true; initDetailMap();"
                                class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4.5 rounded-2xl border border-border hover:border-primary/25 hover:bg-surface-muted/50 cursor-pointer shadow-sm hover:shadow transition-all duration-200 group">
                                <div class="flex items-start gap-3.5 w-full sm:w-auto">
                                    @if ($attendance->type === 'checkin')
                                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                        </span>
                                    @else
                                        <span class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:bg-blue-500/20 dark:text-blue-400">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        </span>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                            <span class="text-sm font-semibold text-fg truncate">
                                                {{ auth()->user()->hasAnyRole(['super_admin', 'hr_admin'])? $attendance->user->name ?? 'Karyawan': ($attendance->type === 'checkin'? 'Verifikasi Absen Masuk': 'Verifikasi Absen Keluar') }}
                                            </span>
                                            <div class="flex items-center gap-1 flex-wrap">
                                                @if (auth()->user()->hasAnyRole(['super_admin', 'hr_admin']))
                                                    <span class="inline-flex items-center rounded-md bg-blue-500/10 px-2 py-0.5 text-xs font-semibold text-blue-600 border border-blue-500/15">{{ $attendance->type === 'checkin' ? 'Masuk' : 'Keluar' }}</span>
                                                @endif
                                                <span class="inline-flex items-center rounded-md bg-surface-muted px-2 py-0.5 text-xs font-semibold text-fg-muted border border-border">{{ strtoupper($attendance->work_mode ?? 'office') }}</span>
                                            </div>
                                        </div>
                                        <div class="text-xs text-fg-muted mt-1.5 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                            <span>Waktu: <strong class="text-fg">{{ $attendance->timestamp->timezone(cache()->get('settings.timezone', 'Asia/Jakarta'))->format('H:i:s') }}</strong></span>
                                            <span class="text-fg-subtle hidden sm:inline">•</span>
                                            <span>IP: <strong class="text-fg">{{ $attendance->ip_address }}</strong></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between sm:justify-end gap-3 mt-4 sm:mt-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-border w-full sm:w-auto">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        @php
                                            $riskColor = $attendance->risk_level === 'high' ? ['bg-rose-500/10 text-rose-600 border-rose-500/15', 'Tinggi'] : ($attendance->risk_level === 'medium' ? ['bg-amber-500/10 text-amber-600 border-amber-500/15', 'Sedang'] : ['bg-emerald-500/10 text-emerald-600 border-emerald-500/15', 'Rendah']);
                                            
                                            $statusColor = $attendance->status === 'approved' ? ['bg-emerald-500/10 text-emerald-600 border-emerald-500/15', 'Disetujui'] : ($attendance->status === 'flagged' ? ['bg-rose-500/10 text-rose-600 border-rose-500/15', 'Dicurigai'] : ['bg-amber-500/10 text-amber-600 border-amber-500/15', 'Diproses']);
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs font-semibold {{ $riskColor[0] }}">
                                            Risiko: {{ $riskColor[1] }}
                                        </span>
                                        <span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs font-semibold {{ $statusColor[0] }}">
                                            Status: {{ $statusColor[1] }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-semibold text-primary flex items-center shrink-0">
                                        Detail
                                        <svg class="h-4 w-4 ml-0.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                    </span>
                                </div>
                            </div>
                        @endforeach

                        @if (!$todayAttendance->where('type', 'checkout')->count())
                            <div class="pt-4 flex justify-center">
                                <a href="{{ route('attendance.checkout') }}" class="btn-primary btn-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    Absen keluar sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

    <x-attendance.detail-modal :is-admin="$isAdmin" />

    {{-- Real-time toast --}}
    <div x-show="toast.show" x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 z-50 max-w-sm w-full card shadow-md p-4 flex items-center gap-3.5">
        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl text-white"
            :class="toast.type === 'info' ? 'bg-info' : 'bg-success'">
            <template x-if="toast.type === 'info'">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </template>
            <template x-if="toast.type !== 'info'">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </template>
        </span>
        <div class="flex-grow">
            <span class="text-xs font-semibold text-primary block">Notifikasi real-time</span>
            <p class="text-sm font-medium text-fg mt-0.5" x-text="toast.message"></p>
        </div>
        <button @click="toast.show = false" class="text-fg-subtle hover:text-fg flex-shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

</div>
