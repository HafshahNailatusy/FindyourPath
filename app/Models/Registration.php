<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model {
    protected $table = 'registrations';
    protected $primaryKey = 'registration_id';
    const CREATED_AT = 'tanggal_daftar';
    const UPDATED_AT = null;

    protected $fillable = [
        'program_id',
        'student_id',
        'full_name',
        'email',
        'phone',
        'university',
        'faculty',
        'team_name',
        'document',
        'payment_receipt',
        'status',
        'tanggal_daftar',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
    ];

    public static function getExistingRegistration(int $studentId, int $programId): ?self {
        return self::where('student_id', $studentId)
            ->where('program_id', $programId)
            ->first();
    }

    public static function getByIdForStudent(int $registrationId, int $studentId): ?self {
        return self::where('registration_id', $registrationId)
            ->where('student_id', $studentId)
            ->first();
    }
}