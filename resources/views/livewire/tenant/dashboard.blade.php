<div class="space-y-6">

    {{-- Welcome / setup checklist --}}
    @unless($setupComplete)
    <div class="rounded-xl border border-warning/30 bg-warning/5 p-5">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-semibold text-text">Complete your school setup</p>
                <p class="mt-0.5 text-xs text-muted">Finish these steps before adding students or running exams.</p>
                <div class="mt-3 space-y-2">
                    @foreach($setupSteps as $step)
                    <a href="{{ $step['url'] }}" wire:navigate
                        class="flex items-center gap-2.5 text-sm {{ $step['done'] ? 'text-muted line-through' : 'text-text hover:text-primary' }} transition">
                        @if($step['done'])
                            <svg class="h-4 w-4 flex-shrink-0 text-success" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                            </svg>
                        @else
                            <span class="flex h-4 w-4 flex-shrink-0 items-center justify-center rounded-full border-2 border-warning"></span>
                        @endif
                        {{ $step['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endunless

    {{-- Quick links --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        @foreach([
            ['label' => 'Grading',    'url' => tenant_url('academic/grading'),    'icon' => 'M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z'],
            ['label' => 'Curriculum', 'url' => tenant_url('academic/curriculum'), 'icon' => 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25'],
            ['label' => 'Classes',    'url' => tenant_url('academic/classes'),    'icon' => 'M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z'],
            ['label' => 'Calendar',   'url' => tenant_url('academic/calendar'),   'icon' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5'],
        ] as $link)
        <a href="{{ $link['url'] }}" wire:navigate
            class="flex flex-col items-center gap-2 rounded-xl border border-border bg-surface p-4 hover:bg-surface-2 transition text-center">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $link['icon'] }}"/>
                </svg>
            </div>
            <span class="text-xs font-medium text-text">{{ $link['label'] }}</span>
        </a>
        @endforeach
    </div>

</div>
