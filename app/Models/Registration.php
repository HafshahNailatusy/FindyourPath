<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Registration extends Model {
    protected $table = 'registrations';
    protected $primaryKey = 'registration_id';

    const CREATED_AT = 'registered_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'registration_code',
        'program_id',
        'student_id',
        'full_name',
        'email',
        'phone',
        'university',
        'study_program',
        'team_name',
        'reason',
        'payment_proof',
        'payment_status',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'updated_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(
            RegistrationDocument::class,
            'registration_id',
            'registration_id'
        );
    }

    public static function generateRegistrationCode(): string
    {
        return 'REG-' . strtoupper(Str::random(12));
    }

    public static function getAllByStudent(int $studentId) {
        return self::with('documents')
            ->where('student_id', $studentId)
            ->orderByDesc('registered_at')
            ->get();
    }

    public static function getByIdForStudent(
        int $registrationId,
        int $studentId
    ): ?self {
        return self::with('documents')
            ->where('registration_id', $registrationId)
            ->where('student_id', $studentId)
            ->first();
    }

    public static function getExistingRegistration(
        int $studentId,
        int $programId
    ): ?self {
        return self::where('student_id', $studentId)
            ->where('program_id', $programId)
            ->first();
    }

    public function updateRegistration(array $data): bool {
        if ($this->status !== 'Menunggu Verifikasi') {
            return false;
        }

        return $this->update([
            'phone' => $data['phone'],
            'university' => $data['university'],
            'study_program' => $data['study_program'],
            'team_name' => $data['team_name'] ?? '',
            'reason' => $data['reason'],
        ]);
    }

    public function cancelRegistration(): bool {
        if ($this->status !== 'Menunggu Verifikasi') {
            return false;
        }

        $this->status = 'Dibatalkan';
        return $this->save();
    }

    public static function createRegistration(
        array $data,
        array $documents
    ): self {
        return DB::transaction(function () use ($data, $documents) {
            $registration = self::create([
                'registration_code' => self::generateRegistrationCode(),
                'program_id' => $data['program_id'],
                'student_id' => $data['student_id'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'university' => $data['university'],
                'study_program' => $data['study_program'],
                'team_name' => $data['team_name'] ?? '',
                'reason' => $data['reason'],
                'payment_proof' => $data['payment_proof'] ?? null,
                'payment_status' => $data['payment_status'] ?? null,
                'status' => 'Menunggu Verifikasi',
            ]);

            foreach ($documents as $document) {
                $registration->documents()->create([
                    'program_detail_id' => $document['program_detail_id'],
                    'file_name' => $document['file_name'],
                    'file_path' => $document['file_path'],
                    'file_type' => $document['file_type'],
                    'file_size' => $document['file_size'],
                ]);
            }
            return $registration;
        });
    }
}