<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'gender',
        'blocked',
        'photo_url',
    ];

    /**
     * Atributos escondidos na serializacao.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blocked' => 'boolean',
        ];
    }

    /**
     * Dados especificos do cliente (customers e subclasse de users).
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id');
    }

    /**
     * Tipo de utilizador: A - Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'A';
    }

    /**
     * Tipo de utilizador: F - Funcionario.
     */
    public function isEmployee(): bool
    {
        return $this->user_type === 'F';
    }

    /**
     * Tipo de utilizador: C - Cliente.
     */
    public function isCustomer(): bool
    {
        return $this->user_type === 'C';
    }

    /**
     * URL da foto/avatar do utilizador (com fallback para o avatar anonimo).
     */
    public function photoLink(): string
    {
        return $this->photo_url
            ? asset('storage/photos/'.$this->photo_url)
            : asset('storage/photos/anonymous.png');
    }
}
