<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para as imagens a estampar nas t-shirts (pode ser uma imagem de catálogo ou uma imagem personalizada de um cliente).
 */
class TshirtImage extends Model
{
    // Permite "eliminação suave" (manter histórico para encomendas anteriores)
    use SoftDeletes;

    // Atributos preenchíveis em massa
    protected $fillable = [
        'customer_id',
        'category_id',
        'name',
        'description',
        'image_url',
        'custom', // True/False, se for null não é fillable nativamente, mas usado para imagens do cliente
    ];

    /**
     * Relação: Uma Imagem pertence a uma Categoria (se for do catálogo).
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relação: Uma Imagem pode pertencer a um Cliente (se for personalizada).
     *
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Verifica se a imagem é personalizada (ou seja, se está associada a um cliente).
     *
     * @return bool
     */
    public function isCustom(): bool
    {
        return $this->customer_id !== null;
    }

    /**
     * Retorna o URL correto para aceder à imagem.
     * Se for personalizada, usa uma rota protegida (file).
     * Se for do catálogo, usa um asset público (pasta storage).
     *
     * @return string
     */
    public function fileUrl(): string
    {
        return $this->isCustom()
            ? route('custom-images.file', $this)
            : asset('storage/tshirt_images/'.$this->image_url);
    }
}
