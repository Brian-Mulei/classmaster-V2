<?php

namespace App\Livewire\Tenant\Academic;

use App\Livewire\Concerns\HasDataTable;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Term;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Academic Calendar')]
class AcademicCalendar extends Component
{
    use HasDataTable;

    public ?string $yearId    = null;
    public ?string $editingId = null;
    public string $name       = '';
    public string $start_date = '';
    public string $end_date   = '';
    public int $sequence      = 1;

    public function selectYear(string $id): void { $this->yearId = $id; $this->resetPage(); }
    public function back(): void { $this->yearId = null; $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'start_date', 'end_date', 'sequence']);
        $this->sequence = $this->yearId ? (Term::where('academic_year_id', $this->yearId)->max('sequence') + 1) : 1;
        $this->dispatch('open-modal', $this->yearId ? 'term-form' : 'year-form');
    }

    public function openEdit(string $id): void
    {
        $this->editingId = $id;
        if ($this->yearId) {
            $r = Term::findOrFail($id);
            $this->name = $r->name; $this->start_date = $r->start_date->format('Y-m-d'); $this->end_date = $r->end_date->format('Y-m-d'); $this->sequence = $r->sequence;
        } else {
            $r = AcademicYear::findOrFail($id);
            $this->name = $r->name; $this->start_date = $r->start_date->format('Y-m-d'); $this->end_date = $r->end_date->format('Y-m-d');
        }
        $this->dispatch('open-modal', $this->yearId ? 'term-form' : 'year-form');
    }

    public function save(): void
    {
        $school = School::first();
        if ($this->yearId) {
            $this->validate(['name' => 'required|string|max:50', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date', 'sequence' => 'required|integer|min:1']);
            $data = ['school_id' => $school->id, 'academic_year_id' => $this->yearId, 'name' => $this->name, 'start_date' => $this->start_date, 'end_date' => $this->end_date, 'sequence' => $this->sequence];
            $this->editingId ? Term::findOrFail($this->editingId)->update($data) : Term::create($data);
        } else {
            $this->validate(['name' => 'required|string|max:20', 'start_date' => 'required|date', 'end_date' => 'required|date|after:start_date']);
            $data = ['school_id' => $school->id, 'name' => $this->name, 'start_date' => $this->start_date, 'end_date' => $this->end_date];
            $this->editingId ? AcademicYear::findOrFail($this->editingId)->update($data) : AcademicYear::create($data);
        }
        $this->dispatch('close-modal');
        $this->reset(['editingId', 'name', 'start_date', 'end_date', 'sequence']);
    }

    public function delete(string $id): void
    {
        $this->yearId ? Term::findOrFail($id)->delete() : AcademicYear::findOrFail($id)->delete();
    }

    public function render()
    {
        if ($this->yearId) {
            $year  = AcademicYear::findOrFail($this->yearId);
            $items = Term::where('academic_year_id', $this->yearId)
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderBy('sequence')
                ->paginate($this->perPage);
        } else {
            $year  = null;
            $items = AcademicYear::withCount('terms')
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderBy('start_date', 'desc')
                ->paginate($this->perPage);
        }
        return view('livewire.tenant.academic.academic-calendar', compact('items', 'year'));
    }
}
