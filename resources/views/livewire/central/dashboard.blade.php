<div class="space-y-6">

    {{-- Stats row --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        @foreach([
            ['label' => 'Total Schools',    'value' => $stats['total'],    'icon' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z', 'color' => 'text-primary bg-primary/10'],
            ['label' => 'Active',           'value' => $stats['active'],   'icon' => 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',                                                                                                    'color' => 'text-success bg-success/10'],
            ['label' => 'Inactive / Trial', 'value' => $stats['inactive'], 'icon' => 'M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z',                                                                                         'color' => 'text-warning bg-warning/10'],
        ] as $stat)
        <div class="flex items-center gap-4 rounded-xl border border-border bg-surface p-5">
            <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl {{ $stat['color'] }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-text">{{ $stat['value'] }}</p>
                <p class="text-sm text-muted">{{ $stat['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Recent schools --}}
    <div class="rounded-xl border border-border bg-surface">
        <div class="flex items-center justify-between border-b border-border px-6 py-4">
            <h2 class="text-sm font-semibold text-text">Recent Schools</h2>
            <a href="{{ route('central.schools.index') }}" wire:navigate
               class="text-xs font-medium text-primary hover:text-primary-hover transition">
                View all →
            </a>
        </div>

        @if($recent->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-surface-2">
                    <svg class="h-7 w-7 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-text">No schools yet</p>
                <p class="mt-1 text-xs text-muted">Create your first school to get started</p>
                <a href="{{ route('central.schools.create') }}" wire:navigate
                   class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-txt hover:bg-primary-hover transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Create School
                </a>
            </div>
        @else
            <ul class="divide-y divide-border">
                @foreach($recent as $tenant)
                <li class="flex items-center justify-between px-6 py-4 hover:bg-surface-2 transition">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-brand-100 text-xs font-bold text-brand-600">
                            {{ strtoupper(substr($tenant->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-text">{{ $tenant->name }}</p>
                            <p class="text-xs text-muted">{{ $tenant->id }}.classmaster.test</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-ui.badge :variant="$tenant->status === 'active' ? 'success' : 'warning'">
                            {{ ucfirst($tenant->status) }}
                        </x-ui.badge>
                        <span class="text-xs text-muted">{{ $tenant->created_at->diffForHumans() }}</span>
                    </div>
                </li>
                @endforeach
            </ul>
        @endif
    </div>

</div>
