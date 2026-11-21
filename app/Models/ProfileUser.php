<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class ProfileUser {
    public function getByUserId(int $userId): ?object {
        return DB::table('users as u')
            ->join('students as s', 's.user_id', '=', 'u.id')
            ->where('u.id', $userId)
            ->select([
                'u.id as user_id',
                'u.name',
                'u.email',
                'u.username',
                's.id as student_id',
                's.nim',
                's.phone',
                's.address',
                's.gender',
                's.birth_date',
                's.university',
                's.faculty',
                's.major',
                's.year',
                's.semester',
                's.gpa',
                's.avatar',
            ])
            ->first();
    }

    public function updatePersonal(
        int $userId,
        string $name,
        string $nim,
        string $phone,
        string $address,
        string $gender,
        ?string $birthDate
    ): void {
        DB::transaction(function () use (
            $userId, $name, $nim, $phone,
            $address, $gender, $birthDate
        ) {
            $nimExists = DB::table('students')
                ->where('nim', $nim)
                ->where('user_id', '!=', $userId)
                ->exists();

            if ($nimExists) {
                throw new \DomainException(
                    'NIM sudah digunakan oleh mahasiswa lain.'
                );
            }

            DB::table('users')
                ->where('id', $userId)
                ->update(['name' => $name]);

            DB::table('students')
                ->where('user_id', $userId)
                ->update([
                    'nim' => $nim,
                    'phone' => $phone,
                    'address' => $address,
                    'gender' => $gender,
                    'birth_date' => $birthDate,
                ]);
        });
    }

    public function updateAcademic(
        int $userId,
        string $university,
        string $faculty,
        string $major,
        int $year,
        int $semester,
        float $gpa
    ): void {
        DB::table('students')
            ->where('user_id', $userId)
            ->update([
                'university' => $university,
                'faculty' => $faculty,
                'major' => $major,
                'year' => $year,
                'semester' => $semester,
                'gpa' => $gpa,
            ]);
    }
}