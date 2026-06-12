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

/**
 * Modelo de Utilizador que autentica na plataforma.
 * Implementa a interface MustVerifyEmail para exigir verificação de email.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    // Utiliza factories, notificações e "eliminação suave" (soft delete)
    use HasFactory, Notifiable, SoftDeletes;

    // Atributos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // 'C' = Cliente, 'F' = Funcionário, 'A' = Administrador
        'gender',    // 'M' ou 'F'
        'blocked',   // Indica se o utilizador tem a conta bloqueada (true/false)
        'photo_url',
    ];

    // Atributos que não devem ser expostos em arrays ou JSON (ex: respostas de API)
    protected $hidden = [
        'password',
        'remember_token',
        'gender',
    ];

    /**
     * Mapeamento de tipos (casts) para os atributos.
     *
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Converte para instância Carbon
            'password' => 'hashed',            // Efetua o hash automático
            'blocked' => 'boolean',            // Garante que é tratado como booleano
        ];
    }

    /**
     * Relação: Um Utilizador (se for Cliente) tem um perfil de Customer associado.
     *
     * @return HasOne
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id');
    }

    /**
     * Relação: Um Utilizador tem muitas encomendas (assumindo que o customer_id liga ao id do utilizador).
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    /**
     * Verifica se o utilizador é um Administrador ('A').
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->user_type === 'A';
    }

    /**
     * Verifica se o utilizador é um Funcionário ('F').
     *
     * @return bool
     */
    public function isEmployee(): bool
    {
        return $this->user_type === 'F';
    }

    /**
     * Verifica se o utilizador é um Cliente ('C').
     *
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->user_type === 'C';
    }

    /**
     * Retorna o link para a foto de perfil.
     * Caso não tenha foto, devolve uma imagem anónima por defeito.
     *
     * @return string
     */
    public function photoLink(): string
    {
        return $this->photo_url
            ? asset('storage/photos/'.$this->photo_url)
            : asset('storage/photos/anonymous.png');
    }
}
