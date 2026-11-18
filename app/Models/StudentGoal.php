<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentGoal extends Model
{
    protected $table = 'student_goals';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'title', 'category', 'current_level', 'target_level',
        'progress', 'target_date', 'description',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'target_date' => 'date:Y-m-d',
        ];
    }

    public function isCompleted(): bool
    {
        return (int) $this->progress >= 100;
    }
}
