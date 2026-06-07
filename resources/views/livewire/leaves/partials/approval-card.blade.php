@php
    // Full literal Tailwind class strings so the JIT scanner picks them up.
    $isManagerStage = $stage === 'manager';
    $avatarCls = $isManagerStage ? 'bg-amber-500/10 border-amber-500/20 text-amber-400' : 'bg-blue-500/10 border-blue-500/20 text-blue-400';
    $rangeCls  = $isManagerStage ? 'text-amber-300' : 'text-blue-300';
    $linkCls   = $isManagerStage ? 'text-amber-400 hover:text-amber-300' : 'text-blue-400 hover:text-blue-300';
@endphp
<div class="bg-[#0d1527]/90 border border-white/5 rounded-2xl p-4 flex flex-col justify-between space-y-4">
    <div>
        <div class="flex items-start justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl border flex items-center justify-center font-black text-xs {{ $avatarCls }}">
                    {{ strtoupper(substr($pl->user->name ?? 'K', 0, 2)) }}
                </div>
                <div>
                    <span class="block label-sm font-bold text-white">{{ $pl->user->name ?? 'N/A' }}</span>
                    <span class="block label-xs">ID Karyawan: {{ $pl->user->employee_id ?? 'N/A' }}</span>
                </div>
            </div>
            <span class="badge-rect-neutral">{{ ucfirst($pl->leave_type) }}</span>
        </div>
        <div class="mt-3 bg-white/5 rounded-xl p-3 text-xs text-slate-300">
            <p class="font-semibold {{ $rangeCls }}">Rentang: {{ \Carbon\Carbon::parse($pl->start_date)->translatedFormat('d M') }} s/d {{ \Carbon\Carbon::parse($pl->end_date)->translatedFormat('d M Y') }} ({{ $pl->total_days }} Hari)</p>
            <p class="mt-1 text-slate-400 font-medium leading-relaxed">"{{ $pl->reason }}"</p>
            @if(!$isManagerStage && $pl->manager)
                <p class="mt-2 pt-2 border-t border-white/5 text-[11px] text-emerald-300/90 font-semibold">
                    Disetujui Manajer: {{ $pl->manager->name }} &middot; {{ \Carbon\Carbon::parse($pl->manager_approved_at)->translatedFormat('d M H:i') }}
                </p>
            @endif
            @if($pl->attachment_path)
                <a href="{{ asset('storage/' . $pl->attachment_path) }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold {{ $linkCls }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    Lihat Lampiran
                </a>
            @endif
        </div>
    </div>
    <div class="flex space-x-2">
        <button wire:click="openAction({{ $pl->id }}, '{{ $stage }}', 'approve')" class="flex-1 btn-success btn-xs">
            {{ $isManagerStage ? 'Setujui (Manajer)' : 'Finalisasi (HR)' }}
        </button>
        <button wire:click="openAction({{ $pl->id }}, '{{ $stage }}', 'reject')" class="flex-1 btn-danger-outline btn-xs">
            Tolak
        </button>
    </div>
</div>
