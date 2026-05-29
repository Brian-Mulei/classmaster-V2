<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.central-guest')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (! auth('super_admin')->attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            $this->addError('email', 'These credentials do not match our records.');
            return;
        }

        auth('super_admin')->user()->update(['last_login_at' => now()]);

        session()->regenerate();

        $this->redirect(route('central.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
