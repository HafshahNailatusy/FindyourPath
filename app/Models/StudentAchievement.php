<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentAchievement extends Model
{
    protected $table = 'student_achievements';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'title', 'category', 'organizer',
        'achievement_date', 'description', 'certificate_file',
    ];

    protected function casts(): array
    {
        return [
            'achievement_date' => 'date:Y-m-d',
        ];
    }
    
    public function certificateUrl(): ?string
    {
        if (! $this->certificate_file) {
            return null;
        }

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');

        return $disk->url($this->certificate_file);
    }

    protected $appends = ['certificate_url'];

    public function getCertificateUrlAttribute(): ?string
    {
        return $this->certificateUrl();
    }
}
