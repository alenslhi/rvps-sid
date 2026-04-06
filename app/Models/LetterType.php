<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LetterType extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_surat',
        'kode_surat',
        'template_file',
        'persyaratan',
    ];

    protected $casts = [
        'persyaratan' => 'array',
    ];

    public function letterRequests()
    {
        return $this->hasMany(LetterRequest::class);
    }
}