{{-- Sortable table header. Usage: <x-ui.th :column="'name'" :sort-by="$sortBy" :sort-dir="$sortDir">Name</x-ui.th> --}}
@props(['column' => null, 'sortBy' => '', 'sortDir' => 'asc'])

<th {{ $attributes->merge(['class' => 'px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted']) }}>
    @if($column)
        <button wire:click="sortOn('{{ $column }}')"
            class="inline-flex items-center gap-1 hover:text-text transition">
            {{ $slot }}
            <span class="text-[10px]">
                @if($sortBy === $column)
                    {{ $sortDir === 'asc' ? '↑' : '↓' }}
                @else
                    <span class="opacity-30">↕</span>
                @endif
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>
