@props([
    'variant' => 'primary',   // primary | secondary | danger | ghost
    'size'    => 'md',        // sm | md | lg
    'type'    => 'button',
])

@php
$base = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
    'primary'   => 'bg-primary text-primary-txt hover:bg-primary-hover focus:ring-primary/40',
    'secondary' => 'border border-border bg-surface text-text hover:bg-surface-2 focus:ring-border',
    'danger'    => 'bg-danger text-white hover:opacity-90 focus:ring-danger/40',
    'ghost'     => 'text-muted hover:text-text hover:bg-surface-2 focus:ring-border',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-5 py-2.5 text-base',
];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "$base {$variants[$variant]} {$sizes[$size]}"]) }}>
    {{ $slot }}
</button>
