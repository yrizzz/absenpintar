@php
    $user = auth()->user();
    $navLink = function ($route, $pattern, $label, $icon) {
        $active = request()->routeIs($pattern);
        $cls = $active
            ? 'bg-primary-soft text-primary'
            : 'text-fg-muted hover:bg-surface-muted hover:text-fg';
        return '<a href="' . route($route) . '" @click="sidebarOpen = false"'
            . ' class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors ' . $cls . '">'
            . '<svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">' . $icon . '</svg>'
            . '<span>' . $label . '</span></a>';
    };

    $icoDash = '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />';
    $icoAtt  = '<path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />';
    $icoLeave = '<path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
    $icoPerm = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />';
    $icoReport = '<path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />';
    $icoCog = '<path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
@endphp

<div class="flex h-full flex-col bg-surface">
    {{-- Brand --}}
    <div class="flex h-16 flex-shrink-0 items-center justify-between gap-2 px-5 border-b border-border">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5" @click="sidebarOpen = false">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary text-primary-fg">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </span>
            <span class="text-lg font-semibold tracking-tight text-fg">Presensi<span class="text-primary">Ku</span></span>
        </a>
        {{-- Close (mobile drawer only) --}}
        <button type="button" @click="sidebarOpen = false" class="lg:hidden flex h-8 w-8 items-center justify-center rounded-lg text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto px-3 py-5 space-y-6">
        <div class="space-y-1">
            <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-fg-subtle">Utama</p>
            {!! $navLink('dashboard', 'dashboard', 'Dasbor', $icoDash) !!}
            {!! $navLink('attendance.index', 'attendance.*', 'Ruang Absensi', $icoAtt) !!}
        </div>

        <div class="space-y-1">
            <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-fg-subtle">Pengajuan</p>
            {!! $navLink('leaves.index', 'leaves.*', 'Manajemen Cuti', $icoLeave) !!}
            @if($user->hasAnyRole(['super_admin', 'hr_admin', 'manager', 'employee']))
                {!! $navLink('permissions.index', 'permissions.*', 'Izin Kerja', $icoPerm) !!}
            @endif
        </div>

        @if($user->hasAnyRole(['super_admin', 'hr_admin']))
            <div class="space-y-1">
                <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-fg-subtle">Administrasi</p>
                {!! $navLink('reports.index', 'reports.*', 'Laporan & Telemetri', $icoReport) !!}
                {!! $navLink('settings.index', 'settings.*', 'Panel Kontrol', $icoCog) !!}
            </div>
        @endif
    </nav>

    {{-- User --}}
    <div class="flex-shrink-0 border-t border-border p-3 space-y-1.5">
        <a href="{{ route('profile') }}" @click="sidebarOpen = false"
            class="flex items-center gap-3 rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('profile') ? 'bg-primary-soft' : 'hover:bg-surface-muted' }}">
            <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-primary text-primary-fg text-sm font-semibold uppercase">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-sm font-medium text-fg">{{ $user->name }}</span>
                <span class="block truncate text-xs text-fg-muted">{{ $user->employee_id ?? $user->email }}</span>
            </span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-danger hover:bg-danger-soft transition-colors">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Keluar Sesi
            </button>
        </form>
    </div>
</div>
