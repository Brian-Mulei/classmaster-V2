<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} — Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-app text-text antialiased">

    <div class="flex min-h-full flex-col items-center justify-center px-4 py-12">

        {{-- Logo / Wordmark --}}
        <div class="mb-8 text-center">
            <div class="inline-flex items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold tracking-tight text-text">ClassMaster</span>
            </div>
            <p class="mt-1 text-xs font-medium text-muted uppercase tracking-widest">Platform Admin</p>
        </div>

        {{-- Card --}}
        <div class="w-full max-w-md rounded-2xl border border-border bg-surface p-8 shadow-lg">
            {{ $slot }}
        </div>

        {{-- Dark mode toggle --}}
        <button x-data @click="$dispatch('toggle-theme')"
            class="mt-6 flex items-center gap-1.5 text-xs text-muted hover:text-text transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
            Toggle theme
        </button>
    </div>

    @livewireScripts
</body>
</html>
