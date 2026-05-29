<?php

namespace App\Livewire\Tenant\People;

use App\Livewire\Concerns\HasDataTable;
use App\Models\Staff;
use App\Services\StaffService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Staff')]
class StaffMembers extends Component
{
    use HasDataTable;

    public ?string $editingId = null;
    public string $resetPasswordResult = '';

    public string $first_name = '';
    public string $last_name  = '';
    public string $job_title  = '';
    public string $email      = '';
    public string $phone      = '';
    public string $tsc_number = '';
    public bool   $is_active  = true;

    public function openCreate(): void
    {
        $this->reset(['editingId', 'first_name', 'last_name', 'job_title', 'email', 'phone', 'tsc_number', 'resetPasswordResult']);
        $this->is_active = true;
        $this->dispatch('open-modal', 'staff-form');
    }

    public function openEdit(string $id): void
    {
        $s = Staff::with('user')->findOrFail($id);
        $this->editingId  = $id;
        $this->first_name = $s->first_name;
        $this->last_name  = $s->last_name;
        $this->job_title  = $s->job_title ?? '';
        $this->email      = $s->email ?? '';
        $this->phone      = $s->phone ?? '';
        $this->tsc_number = $s->tsc_number ?? '';
        $this->is_active  = $s->user->is_active;
        $this->dispatch('open-modal', 'staff-form');
    }

    public function save(StaffService $service): void
    {
        $this->validate([
            'first_name' => 'required|string|max:80',
            'last_name'  => 'required|string|max:80',
            'email'      => 'nullable|email|max:120',
        ]);

        $data = $this->only(['first_name', 'last_name', 'job_title', 'email', 'phone', 'tsc_number', 'is_active']);

        if ($this->editingId) {
            $service->update(Staff::findOrFail($this->editingId), $data);
        } else {
            $service->create($data);
        }

        $this->dispatch('close-modal');
        $this->reset(['editingId']);
    }

    public function resetPassword(string $id, StaffService $service): void
    {
        $staff   = Staff::with('user')->findOrFail($id);
        $newPass = $service->resetPassword($staff);
        $this->resetPasswordResult = "Username: {$staff->user->username}  |  New password: {$newPass}";
        $this->dispatch('open-modal', 'password-result');
    }

    public function render()
    {
        $staff = Staff::with('user')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('first_name', 'ilike', "%{$this->search}%")
                  ->orWhere('last_name',  'ilike', "%{$this->search}%")
                  ->orWhere('email',      'ilike', "%{$this->search}%");
            }))
            ->orderBy($this->sortBy ?: 'last_name', $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.tenant.people.staff', compact('staff'));
    }
}
