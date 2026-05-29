<div class="space-y-5">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-sm text-muted">
        @foreach($breadcrumb as $i => $crumb)
            @if($i < count($breadcrumb) - 1)
                <span>{{ $crumb }}</span><span>›</span>
            @else
                <span class="text-text font-medium">{{ $crumb }}</span>
            @endif
        @endforeach
    </div>

    {{-- Toolbar --}}
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            @if($syllabusId || $subjectId)
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
            class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition whitespace-nowrap">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            @if($subjectId) New Topic
            @elseif($syllabusId) New Subject
            @else New Syllabus
            @endif
        </button>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-border bg-surface overflow-hidden">
        @if($items->isEmpty())
            <x-ui.empty-state
                :title="$subjectId ? 'No topics yet' : ($syllabusId ? 'No subjects yet' : 'No syllabuses yet')"
                :description="$subjectId ? 'Add topics to organise the subject content.' : ($syllabusId ? 'Add subjects to this syllabus.' : 'Create a syllabus to group related subjects.')"
                action="openCreate"
                :action-label="$subjectId ? 'New Topic' : ($syllabusId ? 'New Subject' : 'New Syllabus')"/>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-surface-2">
                        <x-ui.th column="name" :sort-by="$sortBy" :sort-dir="$sortDir">Name</x-ui.th>
                        @if(!$syllabusId && !$subjectId)
                            <x-ui.th>Subjects</x-ui.th>
                            <x-ui.th>Grading Scale</x-ui.th>
                        @elseif($syllabusId && !$subjectId)
                            <x-ui.th>Code</x-ui.th>
                        @else
                            <x-ui.th column="sequence" :sort-by="$sortBy" :sort-dir="$sortDir">Sequence</x-ui.th>
                        @endif
                        <x-ui.th></x-ui.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($items as $item)
                    <tr class="hover:bg-surface-2 transition">
                        <td class="px-4 py-3">
                            @if(!$subjectId)
                                <button wire:click="{{ $syllabusId ? 'selectSubject' : 'selectSyllabus' }}('{{ $item->id }}')"
                                    class="font-medium text-primary hover:text-primary-hover transition">
                                    {{ $item->name }}
                                </button>
                            @else
                                <span class="font-medium text-text">{{ $item->name }}</span>
                            @endif
                        </td>
                        @if(!$syllabusId && !$subjectId)
                            <td class="px-4 py-3 text-muted">{{ $item->subjects_count }}</td>
                            <td class="px-4 py-3 text-muted text-xs">{{ $item->gradingScale?->name ?? '—' }}</td>
                        @elseif($syllabusId && !$subjectId)
                            <td class="px-4 py-3 text-muted">{{ $item->code ?? '—' }}</td>
                        @else
                            <td class="px-4 py-3 text-muted">{{ $item->sequence }}</td>
                        @endif
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEdit('{{ $item->id }}')" class="text-muted hover:text-text transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
                                </button>
                                <button wire:click="delete('{{ $item->id }}')" wire:confirm="Delete this item?" class="text-muted hover:text-danger transition">
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

    {{-- Syllabus form --}}
    <x-ui.modal name="syllabus-form" max-width="md">
        <h3 class="mb-4 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'New' }} Syllabus</h3>
        <div class="space-y-4">
            <x-ui.input label="Name" wire:model="name" placeholder="e.g. KCSE 2024" :error="$errors->first('name')"/>
            <x-ui.input label="Description" wire:model="description" placeholder="Optional description" :error="$errors->first('description')"/>
            <x-ui.select label="Grading Scale (optional)" wire:model="grading_scale_id">
                <option value="">— None —</option>
                @foreach($gradingScales as $scale)
                    <option value="{{ $scale->id }}">{{ $scale->name }}</option>
                @endforeach
            </x-ui.select>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Save</button>
        </div>
    </x-ui.modal>

    {{-- Subject form --}}
    <x-ui.modal name="subject-form" max-width="md">
        <h3 class="mb-4 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'New' }} Subject</h3>
        <div class="space-y-4">
            <x-ui.input label="Subject name" wire:model="name" placeholder="e.g. Mathematics" :error="$errors->first('name')"/>
            <x-ui.input label="Code (optional)" wire:model="code" placeholder="e.g. MATH101"/>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Save</button>
        </div>
    </x-ui.modal>

    {{-- Topic form --}}
    <x-ui.modal name="topic-form" max-width="md">
        <h3 class="mb-4 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'New' }} Topic</h3>
        <div class="space-y-4">
            <x-ui.input label="Topic name" wire:model="name" placeholder="e.g. Algebra" :error="$errors->first('name')"/>
            <x-ui.input label="Sequence" wire:model="sequence" type="number" placeholder="1"/>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Save</button>
        </div>
    </x-ui.modal>

</div>
