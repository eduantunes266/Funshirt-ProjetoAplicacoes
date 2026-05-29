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

    /**
     * Imagem do catalogo (customer_id null) vs imagem propria do cliente.
     */
    public function isCustom(): bool
    {
        return $this->customer_id !== null;
    }

    /**
     * URL para mostrar a imagem do design:
     * - catalogo: ficheiro publico em storage/tshirt_images
     * - propria: rota protegida que serve o ficheiro privado (so dono + staff)
     */
    public function fileUrl(): string
    {
        return $this->isCustom()
            ? route('custom-images.file', $this)
            : asset('storage/tshirt_images/'.$this->image_url);
    }
}
