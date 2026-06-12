<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Categoria para as imagens estampadas nas t-shirts.
 */
class Category extends Model
{
    // Permite a "eliminação suave" (soft delete), mantendo o registo na base de dados (deleted_at)
    use SoftDeletes;

    // Desativa a gestão automática de datas de criação e atualização (created_at, updated_at)
    public $timestamps = false;

    // Atributos que podem ser preenchidos em massa
    protected $fillable = ['name', 'image_url'];

    /**
     * Relação: Uma Categoria tem muitas Imagens de T-Shirt (TshirtImages).
     *
     * @return HasMany
     */
    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class);
    }
}
