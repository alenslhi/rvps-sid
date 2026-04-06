<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id',
        'judul_laporan',
        'isi_laporan',
        'foto_lampiran',
        'status',
        'is_anonymous',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}