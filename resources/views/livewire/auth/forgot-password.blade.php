<div>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-text">Reset password</h2>
        <p class="mt-1 text-sm text-muted">Enter your email and we'll send a reset link</p>
    </div>

    @if($sent)
        <div class="rounded-lg border border-success/30 bg-success/10 px-4 py-4 text-center">
            <p class="text-sm font-medium text-success">Check your inbox</p>
            <p class="mt-1 text-xs text-muted">If that email is registered you'll receive a reset link shortly.</p>
        </div>
        <div class="mt-4 text-center">
            <a href="/login" wire:navigate class="text-sm text-primary hover:text-primary-hover transition">← Back to login</a>
        </div>
    @else
        <form wire:submit="send" class="space-y-4">
            <div class="flex flex-col gap-1">
                <label for="email" class="text-sm font-medium text-text">Email address</label>
                <input id="email" wire:model="email" type="email" autofocus autocomplete="email"
                    placeholder="admin@classmaster.test"
                    class="w-full rounded-lg border px-3 py-2 text-sm text-text placeholder:text-muted bg-surface shadow-sm transition focus:outline-none focus:ring-2 focus:ring-primary/20
                           {{ $errors->has('email') ? 'border-danger' : 'border-border focus:border-primary' }}">
                @error('email') <p class="text-xs text-danger">{{ $message }}</p> @enderror
            </div>
            <button type="submit"
                class="w-full rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-primary-txt hover:bg-primary-hover focus:outline-none focus:ring-2 focus:ring-primary/40 disabled:opacity-60 transition"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Send reset link</span>
                <span wire:loading>Sending…</span>
            </button>
        </form>
        <div class="mt-4 text-center">
            <a href="/login" wire:navigate class="text-sm text-muted hover:text-text transition">← Back to login</a>
        </div>
    @endif
</div>
