<div x-data="{ open: false }" class="relative" wire:poll.30s>
    {{-- Bell trigger --}}
    <button @click="open = !open" type="button"
        class="relative flex h-9 w-9 items-center justify-center rounded-xl border border-border bg-surface text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full border-2 border-surface bg-danger px-1 text-[10px] font-semibold text-white">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-cloak @click.outside="open = false" x-transition
        class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-border bg-surface shadow-md z-50">
        <div class="flex items-center justify-between px-4 py-3 border-b border-border">
            <span class="text-sm font-semibold text-fg">Notifikasi</span>
            @if($unreadCount > 0)
                <button wire:click="markAllRead" class="text-xs font-medium text-primary hover:opacity-80">Tandai semua dibaca</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto divide-y divide-border">
            @forelse($notifications as $n)
                @php
                    $isUnread = is_null($n->read_at);
                    $status = $n->data['status'] ?? 'info';
                    $dot = $status === 'approved' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-info');
                @endphp
                <button type="button" wire:click="open('{{ $n->id }}')"
                    class="w-full text-left px-4 py-3 flex gap-3 transition-colors hover:bg-surface-muted {{ $isUnread ? 'bg-surface-muted/60' : '' }}">
                    <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full {{ $dot }} {{ $isUnread ? '' : 'opacity-30' }}"></span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-sm font-medium text-fg truncate">{{ $n->data['title'] ?? 'Notifikasi' }}</span>
                        <span class="block text-xs text-fg-muted leading-snug mt-0.5">{{ $n->data['message'] ?? '' }}</span>
                        <span class="block text-xs text-fg-subtle mt-1">{{ $n->created_at->diffForHumans() }}</span>
                    </span>
                </button>
            @empty
                <div class="px-4 py-10 text-center text-sm text-fg-muted">Belum ada notifikasi.</div>
            @endforelse
        </div>
    </div>
</div>
