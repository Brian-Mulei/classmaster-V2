<div class="space-y-5">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-1.5 text-sm text-muted">
        <button wire:click="backToYears" class="{{ !$yearId ? 'font-medium text-text' : 'hover:text-text' }} transition">Academic Years</button>
        @if($currentYear)
            <span>›</span>
            <button wire:click="backToClasses" class="{{ !$enrollmentId ? 'font-medium text-text' : 'hover:text-text' }} transition">{{ $currentYear->name }}</button>
        @endif
        @if($currentClass)
            <span>›</span>
            <span class="font-medium text-text">{{ $currentClass->gradeGroup->level->name }} {{ $currentClass->gradeGroup->name }}</span>
        @endif
    </div>

    {{-- ── Year selection ──────────────────────────────────────────────────── --}}
    @if(!$yearId)
        @if($academicYears->isEmpty())
            <x-ui.empty-state title="No academic years" description="Create an academic year in the Academic Calendar first."
                icon="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5"/>
        @else
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($academicYears as $year)
                <button wire:click="selectYear('{{ $year->id }}')"
                    class="rounded-xl border border-border bg-surface p-5 text-left hover:bg-surface-2 hover:border-primary/30 transition">
                    <p class="text-base font-semibold text-text">{{ $year->name }}</p>
                    <p class="mt-1 text-xs text-muted">{{ $year->start_date->format('d M Y') }} — {{ $year->end_date->format('d M Y') }}</p>
                    <p class="mt-2 text-xs text-primary">{{ $year->terms_count ?? $year->terms()->count() }} term(s)</p>
                </button>
                @endforeach
            </div>
        @endif

    {{-- ── Class list for selected year ───────────────────────────────────── --}}
    @elseif(!$enrollmentId)
        <div class="flex items-center justify-between gap-3">
            <div class="relative max-w-xs">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                </span>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Filter classes…"
                    class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
        </div>

        @if($gradeGroups->isEmpty())
            <x-ui.empty-state title="No grade groups" description="Create grade levels and streams in Grade Structure first."
                icon="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21"/>
        @else
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($gradeGroups as $group)
                <button wire:click="openClass('{{ $group->id }}')"
                    class="rounded-xl border border-border bg-surface p-5 text-left hover:bg-surface-2 hover:border-primary/30 transition">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold text-text">{{ $group->level->name }} {{ $group->name }}</p>
                        <x-ui.badge>{{ $group->studentCount }} students</x-ui.badge>
                    </div>
                    <p class="mt-1 text-xs text-muted">{{ $group->level->name }}</p>
                </button>
                @endforeach
            </div>
        @endif

    {{-- ── Student list for selected class ─────────────────────────────────── --}}
    @else
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div class="relative max-w-xs">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                    </span>
                    <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search students…"
                        class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <select wire:model.live="statusFilter" class="rounded-lg border border-border bg-surface px-3 py-2 text-sm text-text focus:border-primary focus:outline-none">
                    <option value="active">Active</option>
                    <option value="withdrawn">Withdrawn</option>
                    <option value="">All</option>
                </select>
            </div>
            <button wire:click="$dispatch('open-modal', 'add-student')"
                class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Enroll Student
            </button>
        </div>

        @error('enroll') <p class="text-sm text-danger">{{ $message }}</p> @enderror

        <div class="rounded-xl border border-border bg-surface overflow-hidden">
            @if($enrolledStudents->isEmpty())
                <x-ui.empty-state title="No students enrolled" description="Click Enroll Student to add students to this class."
                    icon="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904"/>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border bg-surface-2">
                            <x-ui.th>Name</x-ui.th>
                            <x-ui.th>Adm No.</x-ui.th>
                            <x-ui.th>Status</x-ui.th>
                            <x-ui.th></x-ui.th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($enrolledStudents as $se)
                        <tr class="hover:bg-surface-2 transition">
                            <td class="px-4 py-3 font-medium text-text">{{ $se->student->full_name }}</td>
                            <td class="px-4 py-3 font-mono text-xs text-muted">{{ $se->student->student_number }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$se->status === 'active' ? 'success' : 'warning'">{{ ucfirst($se->status) }}</x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                @if($se->status === 'active')
                                    <button wire:click="withdraw('{{ $se->id }}')" wire:confirm="Withdraw this student from the class?"
                                        class="text-xs text-muted hover:text-danger transition">Withdraw</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($enrolledStudents->hasPages())
                    <div class="border-t border-border px-4 py-3">{{ $enrolledStudents->links() }}</div>
                @endif
            @endif
        </div>

        {{-- Add student modal --}}
        <x-ui.modal name="add-student" max-width="md">
            <h3 class="mb-4 text-base font-semibold text-text">Enroll a student</h3>
            @if($unenrolledStudents->isEmpty())
                <p class="text-sm text-muted">All students are already enrolled in a class for this academic year.</p>
            @else
                <div class="max-h-72 overflow-y-auto rounded-lg border border-border divide-y divide-border">
                    @foreach($unenrolledStudents as $student)
                    <button wire:click="enroll('{{ $student->id }}')"
                        class="flex w-full items-center justify-between px-4 py-3 hover:bg-surface-2 transition text-left">
                        <div>
                            <p class="text-sm font-medium text-text">{{ $student->full_name }}</p>
                            <p class="text-xs text-muted">{{ $student->student_number }}</p>
                        </div>
                        <svg class="h-4 w-4 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                    </button>
                    @endforeach
                </div>
            @endif
            <div class="mt-4 flex justify-end">
                <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Close</button>
            </div>
        </x-ui.modal>
    @endif

</div>
