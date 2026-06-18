@php
    $isManagerStage = $stage === 'manager';
    $avatarCls = $isManagerStage ? 'bg-warning-soft text-warning' : 'bg-info-soft text-info';
    $accentCls = $isManagerStage ? 'text-warning' : 'text-info';
@endphp
<div class="rounded-xl border border-border bg-surface-muted p-4 flex flex-col justify-between gap-4">
    <div>
        <div class="flex items-start justify-between gap-2">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg text-xs font-semibold {{ $avatarCls }}">
                    {{ strtoupper(substr($pl->user->name ?? 'K', 0, 2)) }}
                </div>
                <div>
                    <span class="block label-md">{{ $pl->user->name ?? 'N/A' }}</span>
                    <span class="block label-xs">ID Karyawan: {{ $pl->user->employee_id ?? 'N/A' }}</span>
                </div>
            </div>
            <span class="badge-rect-neutral flex-shrink-0">{{ ucfirst($pl->leave_type) }}</span>
        </div>
        <div class="mt-3 rounded-lg border border-border bg-surface p-3 text-xs text-fg-muted">
            <p class="font-medium {{ $accentCls }}">Rentang: {{ \Carbon\Carbon::parse($pl->start_date)->translatedFormat('d M') }} s/d {{ \Carbon\Carbon::parse($pl->end_date)->translatedFormat('d M Y') }} ({{ $pl->total_days }} Hari)</p>
            <p class="mt-1 leading-relaxed">"{{ $pl->reason }}"</p>
            @if(!$isManagerStage && $pl->manager)
                <p class="mt-2 pt-2 border-t border-border text-xs text-success font-medium">
                    Disetujui Manajer: {{ $pl->manager->name }} &middot; {{ \Carbon\Carbon::parse($pl->manager_approved_at)->translatedFormat('d M H:i') }}
                </p>
            @endif
            @if($pl->attachment_path)
                <a href="{{ asset('storage/' . $pl->attachment_path) }}" target="_blank" class="mt-2 inline-flex items-center gap-1 text-xs font-medium {{ $accentCls }} hover:opacity-80">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                    Lihat Lampiran
                </a>
            @endif
        </div>
    </div>
    <div class="flex gap-2">
        <button wire:click="openAction({{ $pl->id }}, '{{ $stage }}', 'approve')" class="flex-1 btn-success btn-sm">
            {{ $isManagerStage ? 'Setujui (Manajer)' : 'Finalisasi (HR)' }}
        </button>
        <button wire:click="openAction({{ $pl->id }}, '{{ $stage }}', 'reject')" class="flex-1 btn-danger-outline btn-sm">Tolak</button>
    </div>
</div>
