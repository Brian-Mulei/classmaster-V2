<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function gradeGroup(): BelongsTo      { return $this->belongsTo(GradeGroup::class); }
    public function academicYear(): BelongsTo    { return $this->belongsTo(AcademicYear::class); }
    public function studentEnrollments(): HasMany { return $this->hasMany(StudentEnrollment::class); }
}
