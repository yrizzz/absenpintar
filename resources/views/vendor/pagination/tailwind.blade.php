@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between gap-4 flex-wrap">
        {{-- Result summary --}}
        <p class="text-xs text-fg-muted">
            Menampilkan
            @if ($paginator->firstItem())
                <span class="font-medium text-fg">{{ $paginator->firstItem() }}</span>–<span class="font-medium text-fg">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            dari <span class="font-medium text-fg">{{ $paginator->total() }}</span> data
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-border text-fg-subtle opacity-50 cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </span>
            @else
                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" rel="prev"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                </button>
            @endif

            {{-- Page numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-9 min-w-9 items-center justify-center px-1 text-sm text-fg-subtle">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-primary px-3 text-sm font-medium text-primary-fg">{{ $page }}</span>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-border bg-surface px-3 text-sm font-medium text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">{{ $page }}</button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" rel="next"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-border bg-surface text-fg-muted hover:bg-surface-muted hover:text-fg transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            @else
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-border text-fg-subtle opacity-50 cursor-not-allowed">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
