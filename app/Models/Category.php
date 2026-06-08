<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ['name', 'image_url'];

    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class);
    }
}
