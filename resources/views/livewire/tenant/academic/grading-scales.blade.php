<div class="space-y-5">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between gap-3">
        <div class="relative max-w-sm flex-1">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search scales…"
                class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition whitespace-nowrap">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Scale
        </button>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-border bg-surface overflow-hidden">
        @if($scales->isEmpty())
            <x-ui.empty-state title="No grading scales yet" description="Create a scale to define how marks translate to grades."
                action="openCreate" action-label="New Grading Scale"
                icon="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-surface-2">
                        <x-ui.th column="name" :sort-by="$sortBy" :sort-dir="$sortDir">Name</x-ui.th>
                        <x-ui.th>Type</x-ui.th>
                        <x-ui.th>Bands</x-ui.th>
                        <x-ui.th></x-ui.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($scales as $scale)
                    <tr class="hover:bg-surface-2 transition">
                        <td class="px-4 py-3 font-medium text-text">{{ $scale->name }}</td>
                        <td class="px-4 py-3"><x-ui.badge>{{ ucfirst($scale->scale_type) }}</x-ui.badge></td>
                        <td class="px-4 py-3 text-muted">{{ $scale->bands_count }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openBands('{{ $scale->id }}')" class="text-xs text-primary hover:text-primary-hover transition">Bands</button>
                                <button wire:click="openEdit('{{ $scale->id }}')" class="text-muted hover:text-text transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button wire:click="delete('{{ $scale->id }}')" wire:confirm="Delete this grading scale?" class="text-muted hover:text-danger transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($scales->hasPages())
                <div class="border-t border-border px-4 py-3">{{ $scales->links() }}</div>
            @endif
        @endif
    </div>

    {{-- Create / Edit scale modal --}}
    <x-ui.modal name="scale-form" max-width="md">
        <h3 class="mb-4 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'New' }} Grading Scale</h3>
        <div class="space-y-4">
            <x-ui.input label="Scale name" wire:model="name" placeholder="e.g. KCSE Grading" :error="$errors->first('name')"/>
            <x-ui.select label="Scale type" wire:model="scale_type" :error="$errors->first('scale_type')">
                <option value="letter">Letter grades (A, B, C…)</option>
                <option value="band">Band grades (E1, E2, M1…)</option>
                <option value="points">Points system</option>
            </x-ui.select>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')"
                class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save"
                class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Save</button>
        </div>
    </x-ui.modal>

    {{-- Grade bands panel --}}
    <x-ui.modal name="bands-panel" max-width="2xl">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-semibold text-text">Grade Bands</h3>
            <button wire:click="addBandRow" class="text-xs text-primary hover:text-primary-hover transition">+ Add row</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border text-left text-xs text-muted">
                        <th class="pb-2 pr-2 font-medium">Label</th>
                        <th class="pb-2 pr-2 font-medium">Min %</th>
                        <th class="pb-2 pr-2 font-medium">Max %</th>
                        <th class="pb-2 pr-2 font-medium">Points</th>
                        <th class="pb-2 font-medium">Remark</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($bands as $i => $band)
                    <tr>
                        <td class="py-1.5 pr-2"><input wire:model="bands.{{ $i }}.label" class="w-16 rounded border border-border bg-surface px-2 py-1 text-xs focus:border-primary focus:outline-none" placeholder="A"/></td>
                        <td class="py-1.5 pr-2"><input wire:model="bands.{{ $i }}.min_percentage" type="number" class="w-16 rounded border border-border bg-surface px-2 py-1 text-xs focus:border-primary focus:outline-none" placeholder="80"/></td>
                        <td class="py-1.5 pr-2"><input wire:model="bands.{{ $i }}.max_percentage" type="number" class="w-16 rounded border border-border bg-surface px-2 py-1 text-xs focus:border-primary focus:outline-none" placeholder="100"/></td>
                        <td class="py-1.5 pr-2"><input wire:model="bands.{{ $i }}.points" type="number" class="w-16 rounded border border-border bg-surface px-2 py-1 text-xs focus:border-primary focus:outline-none" placeholder="12"/></td>
                        <td class="py-1.5 pr-2"><input wire:model="bands.{{ $i }}.remark" class="w-28 rounded border border-border bg-surface px-2 py-1 text-xs focus:border-primary focus:outline-none" placeholder="Excellent"/></td>
                        <td class="py-1.5"><button wire:click="removeBandRow({{ $i }})" class="text-muted hover:text-danger transition">×</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')"
                class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="saveBands"
                class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Save Bands</button>
        </div>
    </x-ui.modal>

</div>
