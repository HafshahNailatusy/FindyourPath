<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends Model
{
    protected $table = 'admins';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'admin_id', 'position', 'phone', 'address',
        'birth_date', 'gender', 'avatar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
