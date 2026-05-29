<div class="space-y-5">

    <div class="flex items-center justify-between gap-3">
        <div class="relative max-w-sm flex-1">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            </span>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search by name or email…"
                class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition whitespace-nowrap">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Staff
        </button>
    </div>

    <div class="rounded-xl border border-border bg-surface overflow-hidden">
        @if($staff->isEmpty())
            <x-ui.empty-state title="No staff yet" description="Add your first staff member."
                action="openCreate" action-label="Add Staff Member"
                icon="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-surface-2">
                        <x-ui.th column="last_name" :sort-by="$sortBy" :sort-dir="$sortDir">Name</x-ui.th>
                        <x-ui.th>Username</x-ui.th>
                        <x-ui.th>Job Title</x-ui.th>
                        <x-ui.th>Email</x-ui.th>
                        <x-ui.th>Status</x-ui.th>
                        <x-ui.th></x-ui.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($staff as $member)
                    <tr class="hover:bg-surface-2 transition">
                        <td class="px-4 py-3 font-medium text-text">{{ $member->full_name }}</td>
                        <td class="px-4 py-3 font-mono text-xs text-muted">{{ $member->user->username }}</td>
                        <td class="px-4 py-3 text-xs text-muted">{{ $member->job_title ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-muted">{{ $member->email ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <x-ui.badge :variant="$member->user->is_active ? 'success' : 'warning'">
                                {{ $member->user->is_active ? 'Active' : 'Inactive' }}
                            </x-ui.badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openEdit('{{ $member->id }}')" title="Edit" class="text-muted hover:text-text transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/></svg>
                                </button>
                                <button wire:click="resetPassword('{{ $member->id }}')" title="Reset password" class="text-muted hover:text-warning transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($staff->hasPages())
                <div class="border-t border-border px-4 py-3">{{ $staff->links() }}</div>
            @endif
        @endif
    </div>

    <x-ui.modal name="staff-form" max-width="lg">
        <h3 class="mb-4 text-base font-semibold text-text">{{ $editingId ? 'Edit' : 'Add' }} Staff Member</h3>
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-3">
                <x-ui.input label="First name *" wire:model="first_name" :error="$errors->first('first_name')"/>
                <x-ui.input label="Last name *"  wire:model="last_name"  :error="$errors->first('last_name')"/>
            </div>
            <x-ui.input label="Job title" wire:model="job_title" placeholder="e.g. Mathematics Teacher"/>
            <div class="grid grid-cols-2 gap-3">
                <x-ui.input label="Email" wire:model="email" type="email" :error="$errors->first('email')"/>
                <x-ui.input label="Phone" wire:model="phone" type="tel"/>
            </div>
            <x-ui.input label="TSC Number" wire:model="tsc_number"/>
            @if($editingId)
                <div class="flex items-center gap-2">
                    <input wire:model="is_active" type="checkbox" id="is_active" class="h-4 w-4 rounded border-border text-primary">
                    <label for="is_active" class="text-sm text-text">Account active</label>
                </div>
            @else
                <p class="text-xs text-muted">A welcome email with login credentials will be sent to the email address if provided.</p>
            @endif
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg border border-border px-4 py-2 text-sm text-text hover:bg-surface-2 transition">Cancel</button>
            <button wire:click="save" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">
                {{ $editingId ? 'Save changes' : 'Add Staff' }}
            </button>
        </div>
    </x-ui.modal>

    <x-ui.modal name="password-result" max-width="sm">
        <div class="flex flex-col items-center gap-3 text-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning/10">
                <svg class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/></svg>
            </div>
            <p class="text-sm font-semibold text-text">Password reset</p>
            <p class="rounded-lg bg-surface-2 px-4 py-2 font-mono text-xs text-text">{{ $resetPasswordResult }}</p>
            <p class="text-xs text-muted">Share these with the staff member. They should change the password after first login.</p>
        </div>
        <div class="mt-5 flex justify-center">
            <button x-on:click="$dispatch('close-modal')" class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">Done</button>
        </div>
    </x-ui.modal>

</div>
