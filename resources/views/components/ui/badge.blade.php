@props(['variant' => 'default'])  {{-- default | success | warning | danger | info --}}

@php
$styles = [
    'default' => 'bg-surface-2 text-text',
    'success' => 'bg-success/10 text-success',
    'warning' => 'bg-warning/10 text-warning',
    'danger'  => 'bg-danger/10 text-danger',
    'info'    => 'bg-info/10 text-info',
];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {$styles[$variant]}"]) }}>
    {{ $slot }}
</span>
