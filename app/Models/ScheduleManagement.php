<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleManagement extends Model
{
    // Menggunakan nama tabel yang sudah ada (pakai huruf s)
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

    // Relasi untuk mengambil data program (nama, bidang, dll)
    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramManagement::class, 'id_program', 'id_program');
    }
}