<div>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-text">Welcome back</h2>
        <p class="mt-1 text-sm text-muted">Sign in to the platform dashboard</p>
    </div>

    <form wire:submit="login" class="space-y-4">

        {{-- Email --}}
        <div class="flex flex-col gap-1">
            <label for="email" class="text-sm font-medium text-text">Email address</label>
            <input
                id="email"
                wire:model="email"
                type="email"
                autocomplete="email"
                autofocus
                placeholder="admin@classmaster.test"
                class="w-full rounded-lg border px-3 py-2 text-sm text-text placeholder:text-muted bg-surface
                       shadow-sm transition focus:outline-none focus:ring-2 focus:ring-primary/20
                       {{ $errors->has('email') ? 'border-danger focus:border-danger' : 'border-border focus:border-primary' }}"
            >
            @error('email')
                <p class="text-xs text-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="flex flex-col gap-1">
            <label for="password" class="text-sm font-medium text-text">Password</label>
            <input
                id="password"
                wire:model="password"
                type="password"
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full rounded-lg border border-border px-3 py-2 text-sm text-text placeholder:text-muted bg-surface
                       shadow-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
            >
            @error('password')
                <p class="text-xs text-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2">
            <input
                id="remember"
                wire:model="remember"
                type="checkbox"
                class="h-4 w-4 rounded border-border text-primary focus:ring-primary/20"
            >
            <label for="remember" class="text-sm text-muted select-none">Remember me</label>
        </div>

        {{-- Forgot password --}}
        <div class="text-right">
            <a href="/forgot-password" wire:navigate class="text-xs text-muted hover:text-primary transition">
                Forgot password?
            </a>
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-txt
                   transition hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary/40
                   disabled:opacity-60"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Sign in</span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Signing in…
            </span>
        </button>
    </form>
</div>
