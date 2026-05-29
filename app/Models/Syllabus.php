<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Syllabus extends Model
{
    use HasUuids;
    protected $table = 'syllabuses';
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function gradingScale(): BelongsTo { return $this->belongsTo(GradingScale::class); }
    public function subjects(): HasMany { return $this->hasMany(Subject::class); }
}
