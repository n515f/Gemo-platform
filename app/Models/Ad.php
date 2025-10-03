<?php
// app/Models/Ad.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title_ar','title_en','desc_ar','desc_en','location_title',
        'images','is_active','created_by','placement',
        'duration_min','duration_sec',
    ];

    protected $casts = [
        'images'    => 'array',
        'is_active' => 'boolean',
    ];

    // القيم المسموحة
    public const PLACEMENTS = ['home','catalog','categories','services','rfq','all'];

    /* نشط فقط */
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    /* فلترة حسب المكان (نصي وليس JSON) */
    public function scopeFor($q, ?string $place)
    {
        if (!$place) return $q;
        return $q->where(function ($qq) use ($place) {
            $qq->where('placement', $place)
               ->orWhere('placement', 'all');
        });
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}