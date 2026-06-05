<div x-data="{ open: false }" class="relative" wire:poll.30s>
    <!-- Bell trigger -->
    <button @click="open = !open" type="button"
        class="relative w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/5 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($unreadCount > 0)
            <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-black flex items-center justify-center border-2 border-[#0b1222]">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" x-cloak @click.outside="open = false" x-transition
        class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] bg-[#121d33] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
            <span class="text-xs font-black text-white tracking-tight">Notifikasi</span>
            @if($unreadCount > 0)
                <button wire:click="markAllRead" class="text-[11px] font-bold text-blue-400 hover:text-blue-300">Tandai semua dibaca</button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto divide-y divide-white/5">
            @forelse($notifications as $n)
                @php
                    $isUnread = is_null($n->read_at);
                    $status = $n->data['status'] ?? 'info';
                    $dot = $status === 'approved' ? 'bg-emerald-400' : ($status === 'rejected' ? 'bg-rose-400' : 'bg-blue-400');
                @endphp
                <button type="button" wire:click="open('{{ $n->id }}')"
                    class="w-full text-left px-4 py-3 flex gap-3 transition-colors hover:bg-white/5 {{ $isUnread ? 'bg-white/[0.03]' : '' }}">
                    <span class="mt-1 w-2 h-2 rounded-full flex-shrink-0 {{ $dot }} {{ $isUnread ? '' : 'opacity-30' }}"></span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-xs font-bold text-white truncate">{{ $n->data['title'] ?? 'Notifikasi' }}</span>
                        <span class="block text-[11px] text-slate-400 leading-snug mt-0.5">{{ $n->data['message'] ?? '' }}</span>
                        <span class="block text-[10px] text-slate-500 mt-1">{{ $n->created_at->diffForHumans() }}</span>
                    </span>
                </button>
            @empty
                <div class="px-4 py-10 text-center text-xs text-slate-500 font-semibold">Belum ada notifikasi.</div>
            @endforelse
        </div>
    </div>
</div>
