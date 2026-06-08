<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{

    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'gender',
        'blocked',
        'photo_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'gender',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
        ];
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'A';
    }

    public function isEmployee(): bool
    {
        return $this->user_type === 'F';
    }

    public function isCustomer(): bool
    {
        return $this->user_type === 'C';
    }

    public function photoLink(): string
    {
        return $this->photo_url
            ? asset('storage/photos/'.$this->photo_url)
            : asset('storage/photos/anonymous.png');
    }
}
