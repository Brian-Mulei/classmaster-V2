<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — ClassMaster Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-app text-text antialiased" x-data="{ sidebarOpen: false }">

<div class="flex h-full">

    {{-- ── Sidebar ─────────────────────────────────────────────────────── --}}
    <aside class="hidden w-64 flex-shrink-0 flex-col border-r border-border bg-surface lg:flex">

        {{-- Logo --}}
        <div class="flex h-16 items-center gap-2.5 px-5 border-b border-border">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-text">ClassMaster</p>
                <p class="text-[10px] font-medium uppercase tracking-widest text-muted">Platform</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto p-3 space-y-0.5">

            @php
            $nav = [
                ['route' => 'central.dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                ['route' => 'central.schools.index', 'label' => 'Schools', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z"/>'],
            ];
            @endphp

            @foreach($nav as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   wire:navigate
                   class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition
                          {{ $active ? 'bg-primary/10 text-primary' : 'text-muted hover:bg-surface-2 hover:text-text' }}">
                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        {!! $item['icon'] !!}
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- User footer --}}
        <div class="border-t border-border p-3">
            <div class="flex items-center gap-3 rounded-lg px-3 py-2">
                <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-primary/20 text-xs font-bold text-primary">
                    {{ substr(auth('super_admin')->user()->name ?? 'S', 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-medium text-text">{{ auth('super_admin')->user()->name }}</p>
                    <p class="truncate text-[10px] text-muted">{{ auth('super_admin')->user()->role }}</p>
                </div>
                <form method="POST" action="{{ route('central.logout') }}">
                    @csrf
                    <button type="submit" class="text-muted hover:text-danger transition" title="Sign out">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main ──────────────────────────────────────────────────────────── --}}
    <div class="flex flex-1 flex-col overflow-hidden">

        {{-- Top header --}}
        <header class="flex h-16 flex-shrink-0 items-center justify-between border-b border-border bg-surface px-6">
            <h1 class="text-base font-semibold text-text">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-3">
                {{-- Theme toggle --}}
                <button x-data @click="$dispatch('toggle-theme')"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-muted hover:bg-surface-2 hover:text-text transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                </button>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
