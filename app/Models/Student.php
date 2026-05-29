<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'admission_date' => 'date',
            'is_active'     => 'boolean',
        ];
    }

    public function user(): BelongsTo            { return $this->belongsTo(User::class); }
    public function school(): BelongsTo          { return $this->belongsTo(School::class); }
    public function enrollments(): HasMany        { return $this->hasMany(StudentEnrollment::class); }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
}
