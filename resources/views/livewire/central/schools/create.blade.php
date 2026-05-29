<div class="mx-auto max-w-2xl space-y-6">

    {{-- Back link --}}
    <a href="{{ route('central.schools.index') }}" wire:navigate
       class="inline-flex items-center gap-1.5 text-sm text-muted hover:text-text transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
        Back to Schools
    </a>

    <div class="rounded-xl border border-border bg-surface p-6 shadow-sm">
        <div class="mb-6">
            <h2 class="text-base font-semibold text-text">Create a new school</h2>
            <p class="mt-0.5 text-sm text-muted">
                This will provision a new tenant schema, run all migrations, and seed default roles.
            </p>
        </div>

        <form wire:submit="create" class="space-y-5">

            {{-- School Name --}}
            <div class="flex flex-col gap-1">
                <label for="name" class="text-sm font-medium text-text">School name</label>
                <input
                    id="name"
                    wire:model.live="name"
                    type="text"
                    autofocus
                    placeholder="e.g. Greenfield Academy"
                    class="w-full rounded-lg border px-3 py-2 text-sm text-text placeholder:text-muted bg-surface
                           shadow-sm transition focus:outline-none focus:ring-2 focus:ring-primary/20
                           {{ $errors->has('name') ? 'border-danger' : 'border-border focus:border-primary' }}"
                >
                @error('name') <p class="text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            {{-- Level --}}
            <div class="flex flex-col gap-1">
                <label for="level" class="text-sm font-medium text-text">School level</label>
                <select id="level" wire:model="level"
                    class="w-full rounded-lg border border-border bg-surface px-3 py-2 text-sm text-text
                           shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <option value="primary">Primary</option>
                    <option value="secondary">Secondary</option>
                    <option value="mixed">Mixed (Primary + Secondary)</option>
                </select>
                @error('level') <p class="text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            {{-- Subdomain --}}
            <div class="flex flex-col gap-1">
                <label for="subdomain" class="text-sm font-medium text-text">School slug</label>
                <div class="flex rounded-lg border shadow-sm overflow-hidden
                            {{ $errors->has('subdomain') ? 'border-danger' : 'border-border focus-within:border-primary focus-within:ring-2 focus-within:ring-primary/20' }}">
                    <input
                        id="subdomain"
                        wire:model.live="subdomain"
                        type="text"
                        placeholder="greenfield-academy"
                        class="flex-1 border-0 bg-surface px-3 py-2 text-sm text-text placeholder:text-muted
                               focus:outline-none focus:ring-0"
                    >
                </div>
                @error('subdomain')
                    <p class="text-xs text-danger">{{ $message }}</p>
                @else
                    <p class="text-xs text-muted">
                        Access URL (local dev):
                        <span class="font-mono text-primary">{{ url('/') }}/{{ $subdomain ?: 'your-school' }}</span>
                    </p>
                @enderror
            </div>

            {{-- Contact email --}}
            <div class="flex flex-col gap-1">
                <label for="contactEmail" class="text-sm font-medium text-text">
                    Contact email
                    <span class="ml-1 text-xs font-normal text-muted">(optional — receives welcome email)</span>
                </label>
                <input id="contactEmail" wire:model="contactEmail" type="email"
                    placeholder="principal@school.com"
                    class="w-full rounded-lg border px-3 py-2 text-sm text-text placeholder:text-muted bg-surface shadow-sm transition focus:outline-none focus:ring-2 focus:ring-primary/20
                           {{ $errors->has('contactEmail') ? 'border-danger' : 'border-border focus:border-primary' }}">
                @error('contactEmail') <p class="text-xs text-danger">{{ $message }}</p> @enderror
            </div>

            {{-- Info box --}}
            <div class="flex gap-3 rounded-lg border border-info/30 bg-info/5 px-4 py-3 text-xs text-info">
                <svg class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                <span>
                    Creating this school will provision a dedicated PostgreSQL schema, run all tenant migrations,
                    and seed the 5 default roles (Head, Admin, Bursar, Teacher, Student).
                    This may take a few seconds.
                </span>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('central.schools.index') }}" wire:navigate
                   class="rounded-lg border border-border bg-surface px-4 py-2 text-sm font-medium text-text
                          hover:bg-surface-2 transition">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-primary px-5 py-2 text-sm font-semibold text-primary-txt
                           hover:bg-primary-hover transition focus:outline-none focus:ring-2 focus:ring-primary/40 disabled:opacity-60"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove class="flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18"/>
                        </svg>
                        Create School
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Provisioning…
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
