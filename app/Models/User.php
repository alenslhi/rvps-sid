<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'role',
        'citizen_id',
        'is_first_login',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'is_first_login' => 'boolean',
            'last_login' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}