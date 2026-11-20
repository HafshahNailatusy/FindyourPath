<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['name', 'username', 'email', 'password', 'role'])]
#[Hidden(['password'])]
class User extends Authenticatable {
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MAHASISWA = 'mahasiswa';

    protected $table = 'users';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMahasiswa(): bool
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function homeUrl(): string
    {
        return $this->isAdmin()
            ? route('admin.dashboard')
            : route('explore-program'); 
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(StudentGoal::class)->latest('id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(StudentProject::class)->latest('id');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(StudentAchievement::class)->latest('id');
    }

    public function careerPlans(): HasMany
    {
        return $this->hasMany(CareerPlan::class)->latest('id');
    }
}
