<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Cliente. Representa os dados específicos de faturação e compras de um utilizador do tipo Cliente.
 */
class Customer extends Model
{
    // Permite a "eliminação suave"
    use SoftDeletes;

    // A chave primária (id) corresponde ao ID do utilizador (User) associado e não é auto-incremental
    public $incrementing = false;

    // Desativa a gestão automática de created_at e updated_at
    public $timestamps = false;

    // Atributos preenchíveis em massa
    protected $fillable = [
        'id',
        'nif',
        'address',
        'default_payment_type',
        'default_payment_ref',
    ];

    /**
     * Relação: Um Cliente pertence a um Utilizador (partilham o mesmo ID).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }

    /**
     * Relação: Um Cliente tem muitas Encomendas.
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    /**
     * Relação: Um Cliente tem muitas Imagens de T-Shirt (imagens personalizadas).
     *
     * @return HasMany
     */
    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class, 'customer_id');
    }
}
