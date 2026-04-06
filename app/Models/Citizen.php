<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citizen extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'no_kk',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'rt',
        'rw',
        'agama',
        'status_perkawinan',
        'pekerjaan',
        'pendidikan_terakhir',
        'foto_ktp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function letterRequests()
    {
        return $this->hasMany(LetterRequest::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}