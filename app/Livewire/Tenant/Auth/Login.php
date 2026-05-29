<?php

namespace App\Livewire\Tenant\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.tenant-guest')]
class Login extends Component
{
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (! auth()->attempt([
            'username'  => $this->username,
            'password'  => $this->password,
            'is_active' => true,
        ], $this->remember)) {
            $this->addError('username', 'Invalid username or password.');
            return;
        }

        auth()->user()->update(['last_login_at' => now()]);
        session()->regenerate();

        $this->redirect(tenant_url('dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.tenant.auth.login');
    }
}
