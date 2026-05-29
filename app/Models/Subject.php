<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function syllabus(): BelongsTo { return $this->belongsTo(Syllabus::class); }
    public function topics(): HasMany { return $this->hasMany(Topic::class)->orderBy('sequence'); }
}
