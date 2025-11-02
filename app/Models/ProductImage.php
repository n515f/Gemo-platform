<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';
    public $timestamps = true; // تم تغييرها لتتوافق مع migration

    protected $fillable = ['product_id','path','sort_order','is_primary'];
    
    // العلاقة مع المنتج
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
