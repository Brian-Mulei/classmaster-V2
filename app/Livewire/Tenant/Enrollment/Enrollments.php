<?php

namespace App\Livewire\Tenant\Enrollment;

use App\Livewire\Concerns\HasDataTable;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\GradeGroup;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Enrollment')]
class Enrollments extends Component
{
    use HasDataTable;

    #[Url]
    public ?string $yearId      = null;

    #[Url]
    public ?string $enrollmentId = null;

    public ?string $addingStudentId = null;
    public string $statusFilter    = 'active';

    // ── Year/class navigation ─────────────────────────────────────────────────

    public function selectYear(string $id): void  { $this->yearId = $id; $this->enrollmentId = null; $this->resetPage(); }
    public function selectClass(string $id): void { $this->enrollmentId = $id; $this->resetPage(); }
    public function backToYears(): void  { $this->yearId = null; $this->enrollmentId = null; }
    public function backToClasses(): void { $this->enrollmentId = null; }

    // ── Ensure a class (enrollment) row exists for a grade group + year ───────

    public function openClass(string $gradeGroupId): void
    {
        $school = School::first();
        $enrollment = Enrollment::firstOrCreate(
            ['grade_group_id' => $gradeGroupId, 'academic_year_id' => $this->yearId],
            ['school_id' => $school->id],
        );
        $this->selectClass($enrollment->id);
    }

    // ── Add a student to the current enrollment ───────────────────────────────

    public function enroll(string $studentId): void
    {
        $enrollment = Enrollment::findOrFail($this->enrollmentId);
        $school     = School::first();

        // Enforce one enrollment per student per academic year
        $existing = StudentEnrollment::where('student_id', $studentId)
            ->where('academic_year_id', $this->yearId)
            ->first();

        if ($existing) {
            $this->addError('enroll', 'This student is already enrolled in a class for this academic year.');
            return;
        }

        StudentEnrollment::create([
            'school_id'       => $school->id,
            'student_id'      => $studentId,
            'enrollment_id'   => $this->enrollmentId,
            'academic_year_id'=> $this->yearId,
            'status'          => 'active',
        ]);

        $this->addingStudentId = null;
        $this->dispatch('close-modal');
    }

    public function withdraw(string $studentEnrollmentId): void
    {
        StudentEnrollment::findOrFail($studentEnrollmentId)->update(['status' => 'withdrawn']);
    }

    public function render()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $currentYear   = $this->yearId ? AcademicYear::find($this->yearId) : null;
        $currentClass  = $this->enrollmentId ? Enrollment::with('gradeGroup.level')->find($this->enrollmentId) : null;

        // Grade groups for the selected year (class list)
        $gradeGroups = null;
        if ($this->yearId && ! $this->enrollmentId) {
            $gradeGroups = GradeGroup::with('level')
                ->when($this->search, fn($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                ->orderByHas('level', fn($q) => $q->orderBy('level_order'))
                ->orderBy('name')
                ->get()
                ->map(function ($group) {
                    $group->enrollment = Enrollment::where('grade_group_id', $group->id)
                        ->where('academic_year_id', $this->yearId)->first();
                    $group->studentCount = $group->enrollment
                        ? StudentEnrollment::where('enrollment_id', $group->enrollment->id)->where('status', 'active')->count()
                        : 0;
                    return $group;
                });
        }

        // Students in a specific class
        $enrolledStudents = null;
        $unenrolledStudents = null;
        if ($this->enrollmentId) {
            $enrolledStudents = StudentEnrollment::with('student')
                ->where('enrollment_id', $this->enrollmentId)
                ->where('status', $this->statusFilter ?: 'active')
                ->when($this->search, fn($q) => $q->whereHas('student', fn($sq) =>
                    $sq->where('first_name', 'ilike', "%{$this->search}%")
                       ->orWhere('last_name', 'ilike', "%{$this->search}%")
                       ->orWhere('student_number', 'ilike', "%{$this->search}%")
                ))
                ->paginate($this->perPage);

            // Students not yet enrolled in any class this year (for add modal)
            $enrolledIds = StudentEnrollment::where('academic_year_id', $this->yearId)->pluck('student_id');
            $unenrolledStudents = Student::whereNotIn('id', $enrolledIds)
                ->orderBy('last_name')
                ->get();
        }

        return view('livewire.tenant.enrollment.enrollments', compact(
            'academicYears', 'currentYear', 'currentClass',
            'gradeGroups', 'enrolledStudents', 'unenrolledStudents',
        ));
    }
}
