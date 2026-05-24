<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'status',
        'date',
        'total_price',
        'nif',
        'address',
        'payment_type',
        'payment_ref',
        'notes',
        'receipt_url',
        'reason_for_cancellation'
    ];
}