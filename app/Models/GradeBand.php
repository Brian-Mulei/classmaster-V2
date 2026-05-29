<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeBand extends Model
{
    use HasUuids;
    protected $guarded = [];
    protected function casts(): array
    {
        return [
            'min_percentage' => 'decimal:2',
            'max_percentage' => 'decimal:2',
            'points'         => 'decimal:2',
            'is_active'      => 'boolean',
        ];
    }

    public function scale(): BelongsTo { return $this->belongsTo(GradingScale::class, 'grading_scale_id'); }
}
