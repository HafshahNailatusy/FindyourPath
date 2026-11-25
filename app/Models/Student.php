<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model {
    protected $table = 'students';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'nim', 'phone', 'address', 'gender', 'birth_date',
        'university', 'faculty', 'major', 'year', 'semester', 'gpa', 'avatar',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
