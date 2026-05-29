@props(['label' => null, 'error' => null, 'leadingIcon' => null])

<div class="flex flex-col gap-1">
    @if($label)
        <label class="text-sm font-medium text-text">{{ $label }}</label>
    @endif
    <div class="relative">
        @if($leadingIcon)
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                {!! $leadingIcon !!}
            </span>
        @endif
        <input
            {{ $attributes->merge(['class' =>
                'w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-text placeholder:text-muted
                 shadow-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20
                 disabled:cursor-not-allowed disabled:opacity-50 ' . ($leadingIcon ? 'pl-9' : '') . ($error ? ' border-danger' : '')
            ]) }}
        >
    </div>
    @if($error)
        <p class="text-xs text-danger">{{ $error }}</p>
    @endif
</div>
