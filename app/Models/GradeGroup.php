<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeGroup extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function level(): BelongsTo { return $this->belongsTo(GradeLevel::class, 'grade_level_id'); }
}
