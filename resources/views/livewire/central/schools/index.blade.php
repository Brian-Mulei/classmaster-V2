<div class="space-y-5">

    {{-- School just created: show admin credentials once --}}
    @if(session('school_created'))
    @php $c = session('school_created'); @endphp
    <div class="rounded-xl border border-success/40 bg-success/5 p-5">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-success" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-semibold text-text">{{ $c['name'] }} is live!</p>
                <p class="mt-0.5 text-xs text-muted">
                    Schema provisioned, migrations complete, roles seeded.
                    @if($c['emailed']) Welcome email with these credentials was sent. @endif
                </p>
                <div class="mt-3 inline-flex flex-wrap items-center gap-4 rounded-lg border border-border bg-surface px-4 py-3">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-muted">Login URL</p>
                        <a href="{{ $c['loginUrl'] }}" target="_blank" class="font-mono text-xs text-primary hover:text-primary-hover transition">{{ $c['loginUrl'] }}</a>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-muted">Username</p>
                        <p class="font-mono text-xs text-text">{{ $c['username'] }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-muted">Temporary password</p>
                        <p class="font-mono text-xs text-text">{{ $c['password'] }}</p>
                    </div>
                </div>
                <p class="mt-2 text-[11px] text-warning">⚠ This password is shown only once. Share it with the school and ask them to change it on first login.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="rounded-lg border border-success/30 bg-success/10 px-4 py-3 text-sm text-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Toolbar --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 items-center gap-2">
            <div class="relative flex-1 max-w-sm">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-muted">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </span>
                <input wire:model.live.debounce.300ms="search"
                    type="search" placeholder="Search schools…"
                    class="w-full rounded-lg border border-border bg-surface pl-9 pr-3 py-2 text-sm text-text
                           placeholder:text-muted focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <select wire:model.live="status"
                class="rounded-lg border border-border bg-surface px-3 py-2 text-sm text-text focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                <option value="">All status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <a href="{{ route('central.schools.create') }}" wire:navigate
           class="inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt
                  hover:bg-primary-hover transition whitespace-nowrap">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            New School
        </a>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-border bg-surface overflow-hidden">
        @if($tenants->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <p class="text-sm font-medium text-text">No schools found</p>
                <p class="mt-1 text-xs text-muted">
                    @if($search || $status) Try changing your filters. @else Create your first school above. @endif
                </p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-border bg-surface-2">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">School</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">URL (local dev)</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-muted">Created</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($tenants as $tenant)
                    <tr class="hover:bg-surface-2 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-brand-100 text-xs font-bold text-brand-600">
                                    {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-text">{{ $tenant->name }}</p>
                                    <p class="text-xs text-muted">{{ $tenant->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $schoolUrl = config('tenancy.route_mode') === 'subdomain'
                                    ? 'http://' . $tenant->id . '.' . config('tenancy.central_domain') . '/login'
                                    : rtrim(config('app.url'), '/') . '/' . $tenant->id . '/login';
                            @endphp
                            <div class="flex flex-col gap-0.5">
                                <a href="{{ $schoolUrl }}" target="_blank"
                                   class="font-mono text-xs text-primary hover:text-primary-hover transition">
                                    {{ $schoolUrl }} ↗
                                </a>
                                <button
                                    onclick="navigator.clipboard.writeText('{{ $schoolUrl }}'); this.textContent='Copied!'; setTimeout(() => this.textContent='Copy', 1500)"
                                    class="text-left text-[10px] text-muted hover:text-text transition">
                                    Copy
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="capitalize text-text text-xs">{{ $tenant->data['level'] ?? '—' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <x-ui.badge :variant="$tenant->status === 'active' ? 'success' : 'warning'">
                                {{ ucfirst($tenant->status) }}
                            </x-ui.badge>
                        </td>
                        <td class="px-6 py-4 text-xs text-muted">
                            {{ $tenant->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-3">
                                {{-- Reset admin password --}}
                                <button wire:click="resetAdminPassword('{{ $tenant->id }}')"
                                    title="Reset admin password"
                                    class="text-muted hover:text-warning transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                    </svg>
                                </button>
                                {{-- Delete --}}
                                <button wire:click="confirmDelete('{{ $tenant->id }}')"
                                    title="Delete school"
                                    class="text-muted hover:text-danger transition">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($tenants->hasPages())
                <div class="border-t border-border px-6 py-4">
                    {{ $tenants->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- Delete confirmation modal --}}
    @if($deletingId)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" wire:key="delete-modal">
        <div class="mx-4 w-full max-w-sm rounded-2xl border border-border bg-surface p-6 shadow-2xl">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-danger/10">
                <svg class="h-6 w-6 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-text">Delete school?</h3>
            <p class="mt-1 text-sm text-muted">This will permanently remove the tenant and all its data. This action cannot be undone.</p>
            <div class="mt-5 flex gap-3">
                <button wire:click="cancelDelete"
                    class="flex-1 rounded-lg border border-border bg-surface px-4 py-2 text-sm font-medium text-text hover:bg-surface-2 transition">
                    Cancel
                </button>
                <button wire:click="delete"
                    class="flex-1 rounded-lg bg-danger px-4 py-2 text-sm font-semibold text-white hover:opacity-90 transition">
                    Delete
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Admin password reset result --}}
    <x-ui.modal name="admin-password-result" max-width="sm">
        <div class="flex flex-col items-center gap-3 text-center">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning/10">
                <svg class="h-6 w-6 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-text">Admin password reset</p>
            <div class="w-full rounded-lg border border-border bg-surface-2 px-4 py-3 text-left">
                <p class="text-xs text-muted">Username</p>
                <p class="font-mono text-sm text-text">{{ $resetResult['username'] ?? '' }}</p>
                <p class="mt-2 text-xs text-muted">New password</p>
                <p class="font-mono text-sm text-text">{{ $resetResult['password'] ?? '' }}</p>
            </div>
            <p class="text-xs text-muted">Share these credentials with the school administrator. Shown only once.</p>
        </div>
        <div class="mt-4 flex justify-center">
            <button x-on:click="$dispatch('close-modal')"
                class="rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">
                Done
            </button>
        </div>
    </x-ui.modal>

</div>
