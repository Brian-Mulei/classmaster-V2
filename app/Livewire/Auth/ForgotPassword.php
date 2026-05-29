<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.central-guest')]
class ForgotPassword extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public bool $sent = false;

    public function send(): void
    {
        $this->validate();

        $status = Password::broker('super_admins')->sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->sent = true;
        } else {
            // Intentionally vague — don't reveal whether the email exists
            $this->sent = true;
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
