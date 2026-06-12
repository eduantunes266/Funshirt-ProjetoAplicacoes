<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo de Encomenda.
 */
class Order extends Model
{
    // Atributos preenchíveis em massa, que compõem o estado, valores e faturação da encomenda
    protected $fillable = [
        'customer_id',
        'status',
        'date',
        'total_price',
        'notes',
        'reason_for_cancellation',
        'nif',
        'address',
        'payment_type',
        'payment_ref',
        'receipt_url',
    ];

    /**
     * Relação: Uma Encomenda pertence a um Cliente (User/Customer).
     * Nota: Embora aponte para customer_id, relaciona-se com a tabela de utilizadores (User).
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Relação: Uma Encomenda tem muitos Itens (produtos comprados).
     *
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
