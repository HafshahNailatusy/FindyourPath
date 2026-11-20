<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleManagement extends Model
{
    protected $table = 'schedules_management';
    protected $primaryKey = 'id_schedule';

    protected $fillable = [
        'id_program',
        'stage_name',
        'status',
        'start_date',
        'end_date',
        'event_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date:Y-m-d',
            'end_date'   => 'date:Y-m-d',
            'event_date' => 'date:Y-m-d',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramManagement::class, 'id_program', 'id_program');
    }
}