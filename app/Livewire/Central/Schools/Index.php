<?php

namespace App\Livewire\Central\Schools;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.central-app')]
#[Title('Schools')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $status = '';

    public ?string $deletingId = null;

    // Admin credential reset result
    public array $resetResult = [];

    public function updatedSearch(): void  { $this->resetPage(); }
    public function updatedStatus(): void  { $this->resetPage(); }

    public function confirmDelete(string $id): void { $this->deletingId = $id; }
    public function cancelDelete(): void            { $this->deletingId = null; }

    public function delete(): void
    {
        if (! $this->deletingId) return;
        Tenant::findOrFail($this->deletingId)->delete();
        $this->deletingId = null;
        session()->flash('success', 'School deleted successfully.');
    }

    public function resetAdminPassword(string $tenantId): void
    {
        $tenant = Tenant::findOrFail($tenantId);

        $newPass = $tenant->run(function () use ($tenant) {
            $adminUsername = $tenant->data['admin_username'] ?? ($tenant->id . '-admin');
            $user = User::where('username', $adminUsername)->first();

            if (! $user) {
                // Fallback: find the first Head-role user
                $user = User::whereHas('roles', fn($q) => $q->where('name', 'Head'))
                            ->orWhere('user_type', 'staff')
                            ->first();
            }

            if (! $user) return null;

            $pass = Str::upper(Str::random(3)) . rand(100, 999) . Str::random(3);
            $user->update(['password' => $pass]);
            return ['username' => $user->username, 'password' => $pass];
        });

        if ($newPass) {
            $this->resetResult = $newPass;
            $this->dispatch('open-modal', 'admin-password-result');
        }
    }

    public function render()
    {
        $tenants = Tenant::query()
            ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->latest()
            ->paginate(20);

        return view('livewire.central.schools.index', compact('tenants'));
    }
}
