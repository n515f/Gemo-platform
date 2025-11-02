<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CeoCertificate extends Model
{
    protected $fillable = [
        'site_setting_id','image_path','title_ar','title_en','issuer_ar','issuer_en','issued_at','sort_order'
    ];

    public function siteSetting()
    {
        return $this->belongsTo(SiteSetting::class, 'site_setting_id');
    }
}