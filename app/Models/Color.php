<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Cor para as t-shirts (base).
 */
class Color extends Model
{
    // Permite a "eliminação suave"
    use SoftDeletes;

    // Desativa a gestão de datas (created_at, updated_at)
    public $timestamps = false;

    // A chave primária deste modelo é o código da cor (ex: 'ffffff' para branco)
    protected $primaryKey = 'code';
    
    // Indica que a chave primária é uma string (e não um inteiro)
    protected $keyType = 'string';
    
    // Indica que a chave primária não é auto-incremental
    public $incrementing = false;

    // Atributos preenchíveis em massa
    protected $fillable = ['code', 'name'];

    /**
     * Relação: Uma Cor pode estar associada a muitos Itens de Encomenda.
     *
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        // Define a chave estrangeira 'color_code' e a chave local 'code'
        return $this->hasMany(OrderItem::class, 'color_code', 'code');
    }
}
