<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
