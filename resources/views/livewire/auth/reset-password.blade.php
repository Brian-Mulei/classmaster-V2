<div>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-text">Set new password</h2>
        <p class="mt-1 text-sm text-muted">Choose a strong password for your account</p>
    </div>

    @if($done)
        <div class="rounded-lg border border-success/30 bg-success/10 px-4 py-4 text-center">
            <p class="text-sm font-medium text-success">Password updated</p>
            <p class="mt-1 text-xs text-muted">You can now sign in with your new password.</p>
        </div>
        <div class="mt-4 text-center">
            <a href="/login" wire:navigate class="text-sm text-primary hover:text-primary-hover transition">Sign in →</a>
        </div>
    @else
        <form wire:submit="submit" class="space-y-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-text">Email</label>
                <input wire:model="email" type="email" readonly
                    class="w-full rounded-lg border border-border bg-surface-2 px-3 py-2 text-sm text-muted cursor-not-allowed">
                @error('email') <p class="text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-text">New password</label>
                <input wire:model="password" type="password" autocomplete="new-password"
                    class="w-full rounded-lg border px-3 py-2 text-sm text-text bg-surface shadow-sm transition focus:outline-none focus:ring-2 focus:ring-primary/20
                           {{ $errors->has('password') ? 'border-danger' : 'border-border focus:border-primary' }}">
                @error('password') <p class="text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-text">Confirm password</label>
                <input wire:model="password_confirmation" type="password" autocomplete="new-password"
                    class="w-full rounded-lg border border-border px-3 py-2 text-sm text-text bg-surface shadow-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
            </div>
            <button type="submit"
                class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-txt hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary/40 disabled:opacity-60 transition"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Reset password</span>
                <span wire:loading>Updating…</span>
            </button>
        </form>
    @endif
</div>
