<div class="space-y-5">

    <div class="flex items-center gap-1.5 text-sm text-muted">
        <span class="{{ $year ? 'cursor-pointer hover:text-text' : 'text-text font-medium' }}" wire:click="{{ $year ? 'back' : '' }}">Academic Years</span>
        @if($year) <span>›</span><span class="font-medium text-text">{{ $year->name }} — Terms</span> @endif
    </div>

    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            @if($year)
                <button wire:click="back" class="text-sm text-muted hover:text-text transition flex items-center gap-1">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                    Back
                </button>
            @endif
            <div class="relative max-w-xs">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search…"
                    class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            {{ $year ? 'New Term' : 'New Academic Year' }}
        </button>
    </div>

    <div class="rounded-xl border border-border bg-surface overflow-hidden">
        @if($items->isEmpty())
            <x-ui.empty-state
                :title="$year ? 'No terms yet' : 'No academic years yet'"
                :description="$year ? 'Add terms (e.g. Term 1, Semester 1) for this year.' : 'Create an academic year (e.g. 2024/2025).'"
                action="openCreate" :action-label="$year ? 'New Term' : 'New Academic Year'"/>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-surface-2">
                        <x-ui.th>Name</x-ui.th>
                        <x-ui.th>Start</x-ui.th>
                        <x-ui.th>End</x-ui.th>
                        @unless($year) <x-ui.th>Terms</x-ui.th> @endunless
                        @if($year) <x-ui.th>Seq</x-ui.th> @endif
                        <x-ui.th></x-ui.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($items as $item)
                    <tr class="hover:bg-surface-2 transition">
                        <td class="px-4 py-3">
                            @unless($year)
                                <button wire:click="selectYear('{{ $item->id }}')" class="font-medium text-primary hover:text-primary-hover transition">{{ $item->name }}</button>
                            @else
                                <span class="font-medium text-text">{{ $item->name }}</span>
                            @endunless
                        </td>
                        <td class="px-4 py-3 text-muted text-xs">{{ $item->start_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-muted text-xs">{{ $item->end_date->format('d M Y') }}</td>
                        @unless($year) <td class="px-4 py-3 text-muted">{{ $item->terms_count }}</td> @endunless
                        @if($year) <td class="px-4 py-3 text-muted">{{ $item->sequence }}</td> @endif
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEdit('{{ $item->id }}')" class="text-muted hover:text-text transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                </button>
                                <button wire:click="delete('{{ $item->id }}')" wire:confirm="Delete?" class="text-muted hover:text-danger transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($items->hasPages())
                <div class="border-t border-border px-4 py-3">{{ $items->links() }}</div>
            @endif
        @endif
    </div>

    <x-ui.modal name="year-form" max-width="md">
        <h3 class="mb-4 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'New' }} Academic Year</h3>
        <div class="space-y-4">
            <x-ui.input label="Name" wire:model="name" placeholder="e.g. 2024/2025" :error="$errors->first('name')"/>
            <div class="grid grid-cols-2 gap-3">
                <x-ui.input label="Start date" wire:model="start_date" type="date" :error="$errors->first('start_date')"/>
                <x-ui.input label="End date"   wire:model="end_date"   type="date" :error="$errors->first('end_date')"/>
            </div>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Save</button>
        </div>
    </x-ui.modal>

    <x-ui.modal name="term-form" max-width="md">
        <h3 class="mb-4 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'New' }} Term</h3>
        <div class="space-y-4">
            <x-ui.input label="Name" wire:model="name" placeholder="e.g. Term 1" :error="$errors->first('name')"/>
            <x-ui.input label="Sequence" wire:model="sequence" type="number" :error="$errors->first('sequence')"/>
            <div class="grid grid-cols-2 gap-3">
                <x-ui.input label="Start date" wire:model="start_date" type="date" :error="$errors->first('start_date')"/>
                <x-ui.input label="End date"   wire:model="end_date"   type="date" :error="$errors->first('end_date')"/>
            </div>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Save</button>
        </div>
    </x-ui.modal>

</div>
