<?php

namespace App\Livewire\Tenant\Academic;

use App\Livewire\Concerns\HasDataTable;
use App\Models\GradeBand;
use App\Models\GradingScale;
use App\Models\School;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Grading Scales')]
class GradingScales extends Component
{
    use HasDataTable;

    // Form fields
    public ?string $editingId = null;
    public string $name = '';
    public string $scale_type = 'letter';

    // Band being edited inline
    public ?string $editingScaleId = null; // which scale's bands panel is open
    public array $bands = [];              // bands for the currently open scale

    // ── Scale CRUD ───────────────────────────────────────────────────────────

    public function openCreate(): void
    {
        $this->editingId  = null;
        $this->name       = '';
        $this->scale_type = 'letter';
        $this->dispatch('open-modal', 'scale-form');
    }

    public function openEdit(string $id): void
    {
        $scale = GradingScale::findOrFail($id);
        $this->editingId  = $id;
        $this->name       = $scale->name;
        $this->scale_type = $scale->scale_type;
        $this->dispatch('open-modal', 'scale-form');
    }

    public function save(): void
    {
        $this->validate([
            'name'       => 'required|string|max:100',
            'scale_type' => 'required|in:letter,band,points',
        ]);

        $school = School::first();
        $data   = ['name' => $this->name, 'scale_type' => $this->scale_type, 'school_id' => $school->id];

        if ($this->editingId) {
            GradingScale::findOrFail($this->editingId)->update($data);
        } else {
            GradingScale::create($data);
        }

        $this->dispatch('close-modal');
        $this->reset(['editingId', 'name', 'scale_type']);
    }

    public function delete(string $id): void
    {
        GradingScale::findOrFail($id)->delete();
    }

    // ── Grade Bands panel ────────────────────────────────────────────────────

    public function openBands(string $scaleId): void
    {
        $this->editingScaleId = $scaleId;
        $scale = GradingScale::with('bands')->findOrFail($scaleId);
        $this->bands = $scale->bands->map(fn($b) => [
            'id'             => $b->id,
            'label'          => $b->label,
            'min_percentage' => $b->min_percentage,
            'max_percentage' => $b->max_percentage,
            'points'         => $b->points,
            'remark'         => $b->remark,
            'sequence'       => $b->sequence,
        ])->toArray();
        $this->dispatch('open-modal', 'bands-panel');
    }

    public function addBandRow(): void
    {
        $this->bands[] = ['id' => null, 'label' => '', 'min_percentage' => '', 'max_percentage' => '', 'points' => '', 'remark' => '', 'sequence' => count($this->bands) + 1];
    }

    public function removeBandRow(int $index): void
    {
        if (isset($this->bands[$index]['id'])) {
            GradeBand::find($this->bands[$index]['id'])?->delete();
        }
        array_splice($this->bands, $index, 1);
        $this->bands = array_values($this->bands);
    }

    public function saveBands(): void
    {
        $school = School::first();
        foreach ($this->bands as $i => $band) {
            $data = [
                'school_id'      => $school->id,
                'grading_scale_id' => $this->editingScaleId,
                'label'          => $band['label'],
                'min_percentage' => $band['min_percentage'],
                'max_percentage' => $band['max_percentage'],
                'points'         => $band['points'] ?: null,
                'remark'         => $band['remark'] ?: null,
                'sequence'       => $i + 1,
            ];
            if ($band['id']) {
                GradeBand::find($band['id'])?->update($data);
            } else {
                GradeBand::create($data);
            }
        }
        $this->dispatch('close-modal');
    }

    public function render()
    {
        $scales = GradingScale::query()
            ->withCount('bands')
            ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
            ->orderBy($this->sortBy ?: 'name', $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.tenant.academic.grading-scales', compact('scales'));
    }
}
