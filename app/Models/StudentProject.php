<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProject extends Model
{
    protected $table = 'student_projects';
    public $timestamps = false;

    public const STATUSES = ['Planned', 'In Progress', 'Completed'];

    protected $fillable = [
        'user_id', 'project_name', 'category', 'technologies',
        'project_url', 'description', 'status',
    ];

    public function isCompleted(): bool
    {
        return strtolower((string) $this->status) === 'completed';
    }
}
