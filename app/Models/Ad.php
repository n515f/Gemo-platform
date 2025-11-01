<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Ad extends Model
{
    protected $table = 'ads';

    protected $fillable = [
        'title_ar','title_en','desc_ar','desc_en','location_title',
        'images','is_active','created_by','placement',
        'duration_min','duration_sec',
    ];

    protected $casts = [
        'images'    => 'array',
        'is_active' => 'boolean',
    ];

    public const PLACEMENTS = ['home','catalog','categories','services','rfq','all'];

    public function scopeActive($q){ return $q->where('is_active', true); }

    public function scopeFor($q, ?string $place)
    {
        if (!$place) return $q;
        return $q->where(fn($qq)=> $qq->where('placement',$place)->orWhere('placement','all'));
    }

    public function creator(){ return $this->belongsTo(\App\Models\User::class,'created_by'); }

    public function getImageUrlsAttribute(): array
    {
        $raw = is_array($this->images) ? $this->images : (json_decode($this->images ?? '[]', true) ?: []);
        $paths = array_filter($raw, fn($p)=>!empty($p));

        $urls = array_map(function ($p) {
            $p = str_replace('\\','/', trim($p));

            // لو رابط خارجي كامل
            if (Str::startsWith($p, ['http://','https://'])) {
                return $p;
            }

            // إزالة بادئة storage/ إن وُجدت
            $p = ltrim(preg_replace('#^/?storage/#', '', $p), '/'); // ads/xxx.png

            // تحقق من الملفات في المسارات الممكنة
            $storagePublic = 'storage/'.$p;
            if (File::exists(public_path($storagePublic))) {
                return asset($storagePublic);
            }

            $storageReal = 'app/public/'.$p;
            if (File::exists(storage_path($storageReal))) {
                return asset('storage/'.$p);
            }

            $publicAds = 'ads/'.$p;
            if (File::exists(public_path($publicAds))) {
                return asset($publicAds);
            }

            return null;
        }, $paths);

        return array_values(array_filter($urls, fn($u)=>!is_null($u)));
    }

    public function getFirstImageUrlAttribute(): ?string
    {
        $arr = $this->image_urls;
        $url = $arr[0] ?? null;

        if (!$url) return asset('images/full-line.png');

        if (Str::startsWith($url, ['http://','https://'])) return $url;

        return $url;
    }
}
