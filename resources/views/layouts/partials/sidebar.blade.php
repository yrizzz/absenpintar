@php
    $user = auth()->user();
@endphp

<div class="flex h-full flex-col bg-surface">
    {{-- Brand --}}
    <div class="flex h-16 flex-shrink-0 items-center overflow-hidden gap-2 px-3 border-b border-border">
        <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
           class="flex items-center gap-2.5 min-w-0"
           :class="desktopNav === 'collapsed' ? 'justify-center w-full' : ''">
            <span class="flex flex-shrink-0 h-9 w-9 items-center justify-center rounded-xl bg-primary text-primary-fg">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
            </span>
            <span x-show="desktopNav !== 'collapsed'" class="text-lg font-semibold tracking-tight text-fg whitespace-nowrap">Presensi<span class="text-primary">Ku</span></span>
        </a>
        {{-- Close (mobile drawer only) --}}
        <button type="button" @click="sidebarOpen = false" class="lg:hidden ml-auto flex h-8 w-8 items-center justify-center rounded-lg text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden px-2 py-4 space-y-5">
        <div class="space-y-0.5">
            <p x-show="desktopNav !== 'collapsed'" class="px-3 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-fg-subtle">Utama</p>

            {{-- Dasbor --}}
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
               title="Dasbor"
               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-primary text-primary-fg' : 'text-fg-muted hover:bg-surface-muted hover:text-fg' }}"
               :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span x-show="desktopNav !== 'collapsed'">Dasbor</span>
            </a>

            {{-- Ruang Absensi --}}
            <a href="{{ route('attendance.index') }}" @click="sidebarOpen = false"
               title="Ruang Absensi"
               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                      {{ request()->routeIs('attendance.*') ? 'bg-primary text-primary-fg' : 'text-fg-muted hover:bg-surface-muted hover:text-fg' }}"
               :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span x-show="desktopNav !== 'collapsed'">Ruang Absensi</span>
            </a>
        </div>

        <div class="space-y-0.5">
            <p x-show="desktopNav !== 'collapsed'" class="px-3 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-fg-subtle">Pengajuan</p>
            @if($user->hasAnyRole(['super_admin', 'hr_admin', 'manager', 'employee']))
            <a href="{{ route('permissions.index') }}" @click="sidebarOpen = false"
               title="Izin Tidak Masuk"
               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                      {{ request()->routeIs('permissions.*') ? 'bg-primary text-primary-fg' : 'text-fg-muted hover:bg-surface-muted hover:text-fg' }}"
               :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span x-show="desktopNav !== 'collapsed'">Izin Tidak Masuk</span>
            </a>
            @endif
        </div>

        @if($user->hasAnyRole(['super_admin', 'hr_admin']))
        <div class="space-y-0.5">
            <p x-show="desktopNav !== 'collapsed'" class="px-3 pb-1.5 text-[10px] font-semibold uppercase tracking-wider text-fg-subtle">Administrasi</p>
            <a href="{{ route('settings.employees') }}" @click="sidebarOpen = false"
               title="Kelola Karyawan"
               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                      {{ request()->routeIs('settings.employees') ? 'bg-primary text-primary-fg' : 'text-fg-muted hover:bg-surface-muted hover:text-fg' }}"
               :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-3.833-6.24H18.5a1.5 1.5 0 00-1.5 1.5v3.128zm-9 0c.221.034.444.052.668.052a9.053 9.053 0 005.084-1.562 1.5 1.5 0 00-1.5-1.5H7.5a4.125 4.125 0 00-3.833 6.24A9.278 9.278 0 007.5 19.5a9.34 9.34 0 00.668-.052M12 14.25a3 3 0 100-6 3 3 0 000 6zm-7.5-3a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zm15 0a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" /></svg>
                <span x-show="desktopNav !== 'collapsed'">Kelola Karyawan</span>
            </a>
            <a href="{{ route('reports.index') }}" @click="sidebarOpen = false"
               title="Laporan & Telemetri"
               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                      {{ request()->routeIs('reports.*') ? 'bg-primary text-primary-fg' : 'text-fg-muted hover:bg-surface-muted hover:text-fg' }}"
               :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <span x-show="desktopNav !== 'collapsed'">Laporan &amp; Telemetri</span>
            </a>
            <a href="{{ route('settings.index') }}" @click="sidebarOpen = false"
               title="Panel Kontrol"
               class="group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                      {{ request()->routeIs('settings.index') ? 'bg-primary text-primary-fg' : 'text-fg-muted hover:bg-surface-muted hover:text-fg' }}"
               :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span x-show="desktopNav !== 'collapsed'">Panel Kontrol</span>
            </a>
        </div>
        @endif
    </nav>

    {{-- User --}}
    <div class="flex-shrink-0 border-t border-border p-2 space-y-1">
        <a href="{{ route('profile') }}" @click="sidebarOpen = false"
           title="{{ $user->name }}"
           class="flex items-center gap-3 rounded-lg px-2 py-2 transition-colors {{ request()->routeIs('profile') ? 'bg-primary text-primary-fg' : 'hover:bg-surface-muted' }}"
           :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
            <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg {{ request()->routeIs('profile') ? 'bg-surface text-primary' : 'bg-primary text-primary-fg' }} text-sm font-semibold uppercase">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <span x-show="desktopNav !== 'collapsed'" class="min-w-0 flex-1">
                <span class="block truncate text-sm font-medium {{ request()->routeIs('profile') ? 'text-primary-fg' : 'text-fg' }}">{{ $user->name }}</span>
                <span class="block truncate text-xs {{ request()->routeIs('profile') ? 'text-primary-fg/80' : 'text-fg-muted' }}">{{ $user->employee_id ?? $user->email }}</span>
            </span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                title="Keluar Sesi"
                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-danger hover:bg-danger-soft transition-colors"
                :class="desktopNav === 'collapsed' ? 'justify-center' : ''">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span x-show="desktopNav !== 'collapsed'">Keluar Sesi</span>
            </button>
        </form>
    </div>
</div>

