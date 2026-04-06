<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'citizen_id',
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'kategori',
        'foto_produk',
        'whatsapp_seller',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'foto_produk' => 'array',
    ];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }
}