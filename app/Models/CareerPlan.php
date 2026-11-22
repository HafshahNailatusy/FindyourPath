<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerPlan extends Model
{
    protected $table = 'career_plans';
    public $timestamps = false;

    public const STATUSES = ['Planning', 'In Progress', 'Completed'];

    protected $fillable = [
        'user_id', 'career_title', 'description', 'target_date', 'status',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date:Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
