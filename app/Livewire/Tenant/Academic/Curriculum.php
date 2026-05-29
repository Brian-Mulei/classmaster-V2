<?php

namespace App\Livewire\Tenant\Academic;

use App\Livewire\Concerns\HasDataTable;
use App\Models\GradingScale;
use App\Models\School;
use App\Models\Subject;
use App\Models\Syllabus;
use App\Models\Topic;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Curriculum')]
class Curriculum extends Component
{
    use HasDataTable;

    // drill-down state
    public ?string $syllabusId = null;
    public ?string $subjectId  = null;

    // form
    public ?string $editingId = null;
    public string $name = '';
    public string $code = '';
    public string $description = '';
    public ?string $grading_scale_id = null;
    public int $sequence = 1;

    // ── Navigation helpers ────────────────────────────────────────────────────

    public function selectSyllabus(string $id): void { $this->syllabusId = $id; $this->subjectId = null; $this->resetPage(); }
    public function selectSubject(string $id): void  { $this->subjectId  = $id; $this->resetPage(); }
    public function back(): void
    {
        if ($this->subjectId)  { $this->subjectId  = null; return; }
        if ($this->syllabusId) { $this->syllabusId = null; return; }
    }

    // ── CRUD helpers ──────────────────────────────────────────────────────────

    private function modalName(): string
    {
        return $this->subjectId ? 'topic-form' : ($this->syllabusId ? 'subject-form' : 'syllabus-form');
    }

    public function openCreate(): void
    {
        $this->reset(['editingId', 'name', 'code', 'description', 'grading_scale_id', 'sequence']);
        $this->sequence = 1;
        $this->dispatch('open-modal', $this->modalName());
    }

    public function openEdit(string $id): void
    {
        $this->editingId = $id;
        if ($this->subjectId) {
            $r = Topic::findOrFail($id);
            $this->name = $r->name; $this->sequence = $r->sequence;
        } elseif ($this->syllabusId) {
            $r = Subject::findOrFail($id);
            $this->name = $r->name; $this->code = $r->code ?? '';
        } else {
            $r = Syllabus::findOrFail($id);
            $this->name = $r->name; $this->description = $r->description ?? ''; $this->grading_scale_id = $r->grading_scale_id;
        }
        $this->dispatch('open-modal', $this->modalName());
    }

    public function save(): void
    {
        $school = School::first();

        if ($this->subjectId) {
            $this->validate(['name' => 'required|string|max:120', 'sequence' => 'required|integer|min:1']);
            $data = ['school_id' => $school->id, 'subject_id' => $this->subjectId, 'name' => $this->name, 'sequence' => $this->sequence];
            $this->editingId ? Topic::findOrFail($this->editingId)->update($data) : Topic::create($data);
        } elseif ($this->syllabusId) {
            $this->validate(['name' => 'required|string|max:120']);
            $data = ['school_id' => $school->id, 'syllabus_id' => $this->syllabusId, 'name' => $this->name, 'code' => $this->code ?: null];
            $this->editingId ? Subject::findOrFail($this->editingId)->update($data) : Subject::create($data);
        } else {
            $this->validate(['name' => 'required|string|max:120']);
            $data = ['school_id' => $school->id, 'name' => $this->name, 'description' => $this->description ?: null, 'grading_scale_id' => $this->grading_scale_id ?: null];
            $this->editingId ? Syllabus::findOrFail($this->editingId)->update($data) : Syllabus::create($data);
        }

        $this->dispatch('close-modal');
        $this->reset(['editingId', 'name', 'code', 'description', 'grading_scale_id', 'sequence']);
    }

    public function delete(string $id): void
    {
        if ($this->subjectId)      Topic::findOrFail($id)->delete();
        elseif ($this->syllabusId) Subject::findOrFail($id)->delete();
        else                       Syllabus::findOrFail($id)->delete();
    }

    public function render()
    {
        $gradingScales = GradingScale::orderBy('name')->get();

        $items = null;
        $breadcrumb = [];

        if ($this->subjectId) {
            $subject  = Subject::with('syllabus')->findOrFail($this->subjectId);
            $syllabus = $subject->syllabus;
            $breadcrumb = [$syllabus->name, $subject->name, 'Topics'];
            $items = Topic::where('subject_id', $this->subjectId)
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderBy($this->sortBy ?: 'sequence', $this->sortDir)
                ->paginate($this->perPage);
        } elseif ($this->syllabusId) {
            $syllabus = Syllabus::findOrFail($this->syllabusId);
            $breadcrumb = [$syllabus->name, 'Subjects'];
            $items = Subject::where('syllabus_id', $this->syllabusId)
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderBy($this->sortBy ?: 'name', $this->sortDir)
                ->paginate($this->perPage);
        } else {
            $breadcrumb = ['Syllabuses'];
            $items = Syllabus::withCount('subjects')
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderBy($this->sortBy ?: 'name', $this->sortDir)
                ->paginate($this->perPage);
        }

        return view('livewire.tenant.academic.curriculum', compact('items', 'breadcrumb', 'gradingScales'));
    }
}
