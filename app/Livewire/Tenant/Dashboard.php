<?php

namespace App\Livewire\Tenant;

use App\Models\AcademicYear;
use App\Models\GradeLevel;
use App\Models\School;
use App\Models\Syllabus;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.tenant-app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $school = School::first();

        $setupSteps = [
            ['label' => 'Grading scale',    'done' => \App\Models\GradingScale::exists(), 'url' => tenant_url('academic/grading')],
            ['label' => 'Curriculum',       'done' => Syllabus::exists(),                 'url' => tenant_url('academic/curriculum')],
            ['label' => 'Grade structure',  'done' => GradeLevel::exists(),               'url' => tenant_url('academic/classes')],
            ['label' => 'Academic year',    'done' => AcademicYear::exists(),             'url' => tenant_url('academic/calendar')],
        ];

        $setupComplete = collect($setupSteps)->every(fn($s) => $s['done']);

        return view('livewire.tenant.dashboard', compact('school', 'setupSteps', 'setupComplete'));
    }
}
