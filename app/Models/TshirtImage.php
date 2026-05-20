<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TshirtImage extends Model
{
    protected $fillable = [
        'customer_id',
        'category_id',
        'name',
        'description',
        'image_url',
        'custom'
    ];
}