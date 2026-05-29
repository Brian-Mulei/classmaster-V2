<?php

namespace App\Livewire\Tenant\Academic;

use App\Livewire\Concerns\HasDataTable;
use App\Models\GradeGroup;
use App\Models\GradeLevel;
use App\Models\School;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Grade Structure')]
class GradeStructure extends Component
{
    use HasDataTable;

    public ?string $levelId = null;
    public ?string $editingId = null;
    public string $name = '';
    public int $level_order = 1;

    public function selectLevel(string $id): void { $this->levelId = $id; $this->resetPage(); }
    public function back(): void { $this->levelId = null; $this->resetPage(); }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'level_order']);
        $this->level_order = $this->levelId ? 1 : (GradeLevel::max('level_order') + 1);
        $this->dispatch('open-modal', $this->levelId ? 'group-form' : 'level-form');
    }

    public function openEdit(string $id): void
    {
        $this->editingId = $id;
        if ($this->levelId) {
            $r = GradeGroup::findOrFail($id);
            $this->name = $r->name;
        } else {
            $r = GradeLevel::findOrFail($id);
            $this->name = $r->name;
            $this->level_order = $r->level_order;
        }
        $this->dispatch('open-modal', $this->levelId ? 'group-form' : 'level-form');
    }

    public function save(): void
    {
        $school = School::first();
        if ($this->levelId) {
            $this->validate(['name' => 'required|string|max:50']);
            $data = ['school_id' => $school->id, 'grade_level_id' => $this->levelId, 'name' => $this->name];
            $this->editingId ? GradeGroup::findOrFail($this->editingId)->update($data) : GradeGroup::create($data);
        } else {
            $this->validate(['name' => 'required|string|max:50', 'level_order' => 'required|integer|min:1']);
            $data = ['school_id' => $school->id, 'name' => $this->name, 'level_order' => $this->level_order];
            $this->editingId ? GradeLevel::findOrFail($this->editingId)->update($data) : GradeLevel::create($data);
        }
        $this->dispatch('close-modal');
        $this->reset(['editingId', 'name', 'level_order']);
    }

    public function delete(string $id): void
    {
        $this->levelId ? GradeGroup::findOrFail($id)->delete() : GradeLevel::findOrFail($id)->delete();
    }

    public function render()
    {
        if ($this->levelId) {
            $level = GradeLevel::findOrFail($this->levelId);
            $items = GradeGroup::where('grade_level_id', $this->levelId)
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderBy('name')
                ->paginate($this->perPage);
        } else {
            $level = null;
            $items = GradeLevel::withCount('groups')
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderBy('level_order')
                ->paginate($this->perPage);
        }
        return view('livewire.tenant.academic.grade-structure', compact('items', 'level'));
    }
}
