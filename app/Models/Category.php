<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'icon',
        'is_active',
    ];

    // فئة تحتوي على منتجات
   public function products()
{
    return $this->belongsToMany(\App\Models\Product::class, 'category_product');
}

    // خاصية محسوبة للحصول على الاسم المترجم بناءً على اللغة الحالية
    public function getTransNameAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    // خاصية محسوبة للحصول على الوصف المترجم بناءً على اللغة الحالية
    public function getTransDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->description_ar : $this->description_en;
    }   
            
}