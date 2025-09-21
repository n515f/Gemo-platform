<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use HasFactory;

    /**
     * الحقول المسموح بملؤها (Mass Assignment)
     */
    protected $fillable = [
        'client_name',
        'email',
        'phone',
        'location',
        'service',
        'budget',
        'brief',
    ];

    /**
     * العلاقة مع المنتج (اختياري إذا ربطت RFQ بمنتج)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * العلاقة مع المستخدم الذي أنشأ الطلب (اختياري)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function user() 
        { 
        return $this->belongsTo(User::class);
     }
}
