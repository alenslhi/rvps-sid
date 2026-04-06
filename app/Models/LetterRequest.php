<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_request_id',
        'nomor_surat',
        'qr_code_token',
        'file_path',
        'signed_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];

    public function letterRequest()
    {
        return $this->belongsTo(LetterRequest::class);
    }
}