@props([
    'field',
    'sort',
    'dir',
    'align' => 'left',
    'method' => 'sortBy',
])
@php
    $active = $sort === $field;
    $alignCls = $align === 'right' ? 'text-right' : ($align === 'center' ? 'text-center' : '');
    $justify = $align === 'right' ? 'flex-row-reverse' : '';
@endphp
<th {{ $attributes->merge(['class' => 'px-5 py-3.5 ' . $alignCls]) }}>
    <button type="button" wire:click="{{ $method }}('{{ $field }}')"
        class="group inline-flex items-center gap-1.5 {{ $justify }} select-none transition-colors {{ $active ? 'text-fg' : 'hover:text-fg' }}">
        <span>{{ $slot }}</span>
        <span class="inline-flex flex-col leading-none">
            <svg class="h-[7px] w-[7px] {{ $active && $dir === 'asc' ? 'text-primary' : 'text-fg-subtle opacity-40 group-hover:opacity-70' }}" viewBox="0 0 10 6" fill="currentColor" aria-hidden="true"><path d="M5 0l5 6H0z" /></svg>
            <svg class="h-[7px] w-[7px] mt-px {{ $active && $dir === 'desc' ? 'text-primary' : 'text-fg-subtle opacity-40 group-hover:opacity-70' }}" viewBox="0 0 10 6" fill="currentColor" aria-hidden="true"><path d="M5 6L0 0h10z" /></svg>
        </span>
    </button>
</th>
