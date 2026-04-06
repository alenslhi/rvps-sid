<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun',
        'kategori',
        'label',
        'nominal',
        'realisasi',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'nominal' => 'decimal:2',
        'realisasi' => 'decimal:2',
    ];
}