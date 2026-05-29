<?php

namespace App\Livewire\Central\Schools;

use App\Services\SchoolCreationService;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.central-app')]
#[Title('Create School')]
class Create extends Component
{
    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|in:primary,secondary,mixed')]
    public string $level = 'mixed';

    #[Validate('required|alpha_dash:ascii|max:63|min:2')]
    public string $subdomain = '';

    #[Validate('nullable|email|max:120')]
    public string $contactEmail = '';

    public bool $autoSlug = true;

    public function updatedName(string $value): void
    {
        if ($this->autoSlug) {
            $this->subdomain = Str::slug($value);
        }
    }

    public function updatedSubdomain(): void
    {
        $this->autoSlug = false;
    }

    public function create(SchoolCreationService $service): void
    {
        $this->validate();

        try {
            $result = $service->create($this->name, $this->level, $this->subdomain, $this->contactEmail ?: null);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->getMessageBag());
            return;
        }

        session()->flash('school_created', [
            'name'     => $this->name,
            'loginUrl' => $result['loginUrl'],
            'username' => $result['username'],
            'password' => $result['password'],
            'emailed'  => ! empty($this->contactEmail),
        ]);

        $this->redirect(route('central.schools.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.central.schools.create');
    }
}
