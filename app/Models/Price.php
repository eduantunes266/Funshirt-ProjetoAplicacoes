<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo de Preços (configurações globais de preços da loja).
 */
class Price extends Model
{
    // Desativa a gestão de datas
    public $timestamps = false;

    // Atributos de preços base e descontos que podem ser alterados pelo admin
    protected $fillable = [
        'unit_price_catalog',
        'unit_price_catalog_discount',
        'unit_price_own',
        'unit_price_own_discount',
        'qty_discount',
    ];
}
