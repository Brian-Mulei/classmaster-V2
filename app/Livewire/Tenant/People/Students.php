<?php

namespace App\Livewire\Tenant\People;

use App\Livewire\Concerns\HasDataTable;
use App\Models\Student;
use App\Services\StudentService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Students')]
class Students extends Component
{
    use HasDataTable;

    public ?string $editingId = null;
    public string $resetPasswordResult = '';

    // form fields
    public string $first_name = '';
    public string $middle_name = '';
    public string $last_name = '';
    public string $student_number = '';
    public string $gender = '';
    public string $date_of_birth = '';
    public string $admission_date = '';
    public string $guardian_name = '';
    public string $guardian_phone = '';
    public string $guardian_email = '';
    public string $guardian_relationship = '';

    public function openCreate(): void
    {
        $this->reset(['editingId', 'first_name', 'middle_name', 'last_name', 'student_number',
            'gender', 'date_of_birth', 'admission_date', 'guardian_name', 'guardian_phone',
            'guardian_email', 'guardian_relationship', 'resetPasswordResult']);
        $this->admission_date = now()->format('Y-m-d');
        $this->dispatch('open-modal', 'student-form');
    }

    public function openEdit(string $id): void
    {
        $s = Student::findOrFail($id);
        $this->editingId           = $id;
        $this->first_name          = $s->first_name;
        $this->middle_name         = $s->middle_name ?? '';
        $this->last_name           = $s->last_name;
        $this->student_number      = $s->student_number;
        $this->gender              = $s->gender ?? '';
        $this->date_of_birth       = $s->date_of_birth?->format('Y-m-d') ?? '';
        $this->admission_date      = $s->admission_date?->format('Y-m-d') ?? '';
        $this->guardian_name       = $s->guardian_name ?? '';
        $this->guardian_phone      = $s->guardian_phone ?? '';
        $this->guardian_email      = $s->guardian_email ?? '';
        $this->guardian_relationship = $s->guardian_relationship ?? '';
        $this->dispatch('open-modal', 'student-form');
    }

    public function save(StudentService $service): void
    {
        $this->validate([
            'first_name'     => 'required|string|max:80',
            'last_name'      => 'required|string|max:80',
            'student_number' => 'required|string|max:30',
            'guardian_email' => 'nullable|email|max:120',
        ]);

        $data = $this->only([
            'first_name', 'middle_name', 'last_name', 'student_number', 'gender',
            'date_of_birth', 'admission_date', 'guardian_name', 'guardian_phone',
            'guardian_email', 'guardian_relationship',
        ]);

        if ($this->editingId) {
            $service->update(Student::findOrFail($this->editingId), $data);
        } else {
            $service->create($data);
        }

        $this->dispatch('close-modal');
        $this->reset(['editingId']);
    }

    public function resetPassword(string $id, StudentService $service): void
    {
        $student = Student::findOrFail($id);
        $newPass = $service->resetPassword($student);
        $this->resetPasswordResult = "New password for {$student->full_name}: {$newPass}";
        $this->dispatch('open-modal', 'password-result');
    }

    public function toggleActive(string $id): void
    {
        $student = Student::findOrFail($id);
        $student->user->update(['is_active' => ! $student->user->is_active]);
    }

    public function render()
    {
        $students = Student::with('user')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('first_name', 'ilike', "%{$this->search}%")
                  ->orWhere('last_name',  'ilike', "%{$this->search}%")
                  ->orWhere('student_number', 'ilike', "%{$this->search}%");
            }))
            ->orderBy($this->sortBy ?: 'last_name', $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.tenant.people.students', compact('students'));
    }
}
