<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'tshirt_image_id',
        'color_code',
        'size',
        'qty',
        'unit_price',
        'sub_total',
    ];

    public function tshirtImage(): BelongsTo
    {
        return $this->belongsTo(TshirtImage::class)->withTrashed();
    }
}
