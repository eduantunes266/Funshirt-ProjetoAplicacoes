<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Item de Encomenda (representa uma linha da encomenda com a respetiva t-shirt, cor, tamanho, quantidade e subtotal).
 */
class OrderItem extends Model
{
    // Desativa a gestão de datas de criação e atualização
    public $timestamps = false;

    // Atributos preenchíveis em massa
    protected $fillable = [
        'order_id',
        'tshirt_image_id',
        'color_code',
        'size',
        'qty',
        'unit_price',
        'sub_total',
    ];

    /**
     * Relação: Um Item de Encomenda pertence a uma Imagem de T-Shirt.
     * Inclui imagens apagadas (withTrashed) para garantir que o histórico de compras não quebra se a imagem for eliminada.
     *
     * @return BelongsTo
     */
    public function tshirtImage(): BelongsTo
    {
        return $this->belongsTo(TshirtImage::class)->withTrashed();
    }
}
