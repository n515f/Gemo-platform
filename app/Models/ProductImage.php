<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';
    public $timestamps = true;

    protected $fillable = ['product_id', 'path', 'sort_order', 'is_primary'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
