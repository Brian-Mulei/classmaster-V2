@props(['title' => 'Nothing here yet', 'description' => null, 'icon' => null, 'action' => null, 'actionLabel' => 'Create'])

<div class="flex flex-col items-center justify-center py-20 text-center">
    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-2">
        @if($icon)
            <svg class="h-7 w-7 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
            </svg>
        @else
            <svg class="h-7 w-7 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
            </svg>
        @endif
    </div>
    <p class="text-sm font-medium text-text">{{ $title }}</p>
    @if($description)
        <p class="mt-1 text-xs text-muted">{{ $description }}</p>
    @endif
    @if($action)
        <button wire:click="{{ $action }}"
            class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            {{ $actionLabel }}
        </button>
    @endif
</div>
