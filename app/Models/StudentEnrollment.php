<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentEnrollment extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function student(): BelongsTo    { return $this->belongsTo(Student::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYear::class); }
}
