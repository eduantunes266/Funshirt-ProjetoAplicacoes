<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
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
     * Dados do cliente (customer_id e a mesma PK do user).
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
