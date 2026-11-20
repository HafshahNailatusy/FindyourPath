<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramManagement extends Model
{
    use HasFactory;

    protected $table = 'program_management';
    protected $primaryKey = 'id_program';

    protected $fillable = [
        'nama_program',
        'penyelenggara',
        'bidang',
        'deskripsi',
        'poster',
    ];
}
