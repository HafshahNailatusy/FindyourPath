<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationDocument extends Model {
    protected $table = 'registration_documents';
    protected $primaryKey = 'document_id';
    public $timestamps = false;

    protected $fillable = [
        'registration_id',
        'program_detail_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function registration(): BelongsTo {
        return $this->belongsTo(
            Registration::class,
            'registration_id',
            'registration_id'
        );
    }
}