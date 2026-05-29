@props(['label' => null, 'error' => null])

<div class="flex flex-col gap-1">
    @if($label)
        <label class="text-sm font-medium text-text">{{ $label }}</label>
    @endif
    <select {{ $attributes->merge(['class' =>
        'w-full rounded-lg border bg-surface px-3 py-2 text-sm text-text shadow-sm transition
         focus:outline-none focus:ring-2 focus:ring-primary/20 ' . ($error ? 'border-danger' : 'border-border focus:border-primary')
    ]) }}>
        {{ $slot }}
    </select>
    @if($error)
        <p class="text-xs text-danger">{{ $error }}</p>
    @endif
</div>
