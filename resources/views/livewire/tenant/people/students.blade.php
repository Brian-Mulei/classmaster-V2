<div class="space-y-5">

    <div class="flex items-center justify-between gap-3">
        <div class="relative max-w-sm flex-1">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search by name or number…"
                class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition whitespace-nowrap">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Student
        </button>
    </div>

    <div class="rounded-xl border border-border bg-surface overflow-hidden">
        @if($students->isEmpty())
            <x-ui.empty-state title="No students yet" description="Add your first student to get started."
                action="openCreate" action-label="Add Student"
                icon="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-surface-2">
                        <x-ui.th column="last_name" :sort-by="$sortBy" :sort-dir="$sortDir">Name</x-ui.th>
                        <x-ui.th column="student_number" :sort-by="$sortBy" :sort-dir="$sortDir">Adm No.</x-ui.th>
                        <x-ui.th>Username</x-ui.th>
                        <x-ui.th>Guardian</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th></x-ui.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($students as $student)
                    <tr class="hover:bg-surface-2 transition">
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-text">{{ $student->full_name }}</p>
                                <p class="text-xs text-muted capitalize">{{ $student->gender ?? '—' }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 font-mono text-xs text-muted">{{ $student->student_number }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-muted">{{ $student->user->username }}</td>
                        <td class="px-4 py-3">
                            <p class="text-xs text-text">{{ $student->guardian_name ?? '—' }}</p>
                            <p class="text-[10px] text-muted">{{ $student->guardian_email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <x-ui.badge :variant="$student->user->is_active ? 'success' : 'warning'">
                                {{ $student->user->is_active ? 'Active' : 'Inactive' }}
                            </x-ui.badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEdit('{{ $student->id }}')" title="Edit" class="text-muted hover:text-text transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                </button>
                                <button wire:click="resetPassword('{{ $student->id }}')" title="Reset password" class="text-muted hover:text-warning transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                </button>
                                <button wire:click="toggleActive('{{ $student->id }}')" title="{{ $student->user->is_active ? 'Deactivate' : 'Activate' }}" class="text-muted hover:text-text transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $student->user->is_active ? 'M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636' : 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' }}"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($students->hasPages())
                <div class="border-t border-border px-4 py-3">{{ $students->links() }}</div>
            @endif
        @endif
    </div>

    {{-- Student form modal --}}
    <x-ui.modal name="student-form" max-width="2xl">
        <h3 class="mb-5 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'Add' }} Student</h3>
        <div class="space-y-5">
            <div>
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-muted">Personal details</p>
                <div class="grid grid-cols-3 gap-3">
                    <x-ui.input label="First name *" wire:model="first_name" :error="$errors->first('first_name')"/>
                    <x-ui.input label="Middle name" wire:model="middle_name"/>
                    <x-ui.input label="Last name *" wire:model="last_name" :error="$errors->first('last_name')"/>
                </div>
                <div class="mt-3 grid grid-cols-3 gap-3">
                    <x-ui.input label="Admission No. *" wire:model="student_number" :error="$errors->first('student_number')"/>
                    <x-ui.select label="Gender" wire:model="gender">
                        <option value="">— Select —</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </x-ui.select>
                    <x-ui.input label="Date of birth" wire:model="date_of_birth" type="date"/>
                </div>
                <div class="mt-3">
                    <x-ui.input label="Admission date" wire:model="admission_date" type="date"/>
                </div>
            </div>
            <div>
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-muted">Guardian details</p>
                <div class="grid grid-cols-2 gap-3">
                    <x-ui.input label="Guardian name" wire:model="guardian_name"/>
                    <x-ui.input label="Relationship" wire:model="guardian_relationship" placeholder="Mother, Father…"/>
                    <x-ui.input label="Guardian phone" wire:model="guardian_phone" type="tel"/>
                    <x-ui.input label="Guardian email" wire:model="guardian_email" type="email"
                        :error="$errors->first('guardian_email')"/>
                </div>
                @unless($editingId)
                    <p class="mt-1.5 text-xs text-muted">A login email will be sent to the guardian email if provided.</p>
                @endunless
            </div>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">
                {{ $editingId ? 'Save changes' : 'Add Student' }}
            </button>
        </div>
    </x-ui.modal>

    {{-- Password reset result modal --}}
    <x-ui.modal name="password-result" max-width="sm">
        <div class="flex flex-col items-center gap-3 text-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning/10">
                <svg class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
            </div>
            <p class="text-sm font-semibold text-text">Password reset</p>
            <p class="rounded-lg bg-surface-2 px-4 py-2 font-mono text-sm text-text">{{ $resetPasswordResult }}</p>
            <p class="text-xs text-muted">Share this with the student / guardian. They should change it on first login.</p>
        </div>
        <div class="mt-5 flex justify-center">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Done</button>
        </div>
    </x-ui.modal>

</div>
