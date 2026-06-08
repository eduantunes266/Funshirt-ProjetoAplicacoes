<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TshirtImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'category_id',
        'name',
        'description',
        'image_url',
        'custom',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isCustom(): bool
    {
        return $this->customer_id !== null;
    }

    public function fileUrl(): string
    {
        return $this->isCustom()
            ? route('custom-images.file', $this)
            : asset('storage/tshirt_images/'.$this->image_url);
    }
}
