<?php

namespace App\Livewire\Central;

use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.central-app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $tenants = Tenant::latest()->get();

        $stats = [
            'total'    => $tenants->count(),
            'active'   => $tenants->where('status', 'active')->count(),
            'inactive' => $tenants->where('status', '!=', 'active')->count(),
        ];

        $recent = $tenants->take(5);

        return view('livewire.central.dashboard', compact('stats', 'recent'));
    }
}
