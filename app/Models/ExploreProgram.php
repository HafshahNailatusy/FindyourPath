<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExploreProgram extends Model
{
    protected $table = 'programs';
    public $timestamps = false;

    protected $fillable = [
        'type', 'title', 'organizer', 'location', 'date', 'category', 'desc', 'image',
    ];
}
