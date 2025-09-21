<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code','slug',
        'name_ar','name_en',
        'short_desc_ar','short_desc_en',
        'specs_ar','specs_en',
        'price','is_active','sort_order',
        // أضِف هنا أية أعمدة أخرى لديك فعلاً في الجدول (مثلاً badge أو segment إن وجدت)
    ];

    protected $casts = [
        'specs_ar'  => 'array',
        'specs_en'  => 'array',
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    /**
     * التحميل المسبق للصور لتقليل عدد الاستعلامات
     */
    protected $with = ['images'];

    /**
     * لإظهار خصائص محسوبة تلقائياً في التحويل إلى مصفوفة/JSON
     */
    protected $appends = [
        'trans_name',
        'trans_short_desc',
        'badge',
        'first_image_url',
    ];

    /* ============================ العلاقات ============================ */

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function rfqs()
    {
        return $this->hasMany(Rfq::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /* ============================ Scopes ============================ */

    /**
     * فلترة المنتجات المفعّلة فقط
     */
    public function scopeActive(Builder $q, bool $active = true): Builder
    {
        return $q->where('is_active', $active);
    }

    /**
     * بحث ذكي حسب اللغة الحالية + كود المنتج
     */
    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') return $q;

        $loc = app()->getLocale() === 'ar' ? 'ar' : 'en';

        return $q->where(function (Builder $qq) use ($term, $loc) {
            $qq->where("name_{$loc}", 'like', "%{$term}%")
               ->orWhere("short_desc_{$loc}", 'like', "%{$term}%")
               ->orWhere('code', 'like', "%{$term}%")
               ->orWhere('slug', 'like', "%{$term}%");
        });
    }

    /* ============================ Accessors ============================ */

    /**
     * اسم المنتج حسب اللغة الجارية
     */
    public function getTransNameAttribute(): string
    {
        $loc = app()->getLocale() === 'ar' ? 'ar' : 'en';
        return (string) ($this->{"name_{$loc}"} ?? $this->name_en ?? $this->name_ar ?? '');
    }

    /**
     * وصف مختصر حسب اللغة الجارية
     */
    public function getTransShortDescAttribute(): string
    {
        $loc = app()->getLocale() === 'ar' ? 'ar' : 'en';
        return (string) ($this->{"short_desc_{$loc}"} ?? $this->short_desc_en ?? $this->short_desc_ar ?? '');
    }

    /**
     * شارة أقصى 3 أحرف (من badge/segment/code أو من الكود نفسه)
     */
    public function getBadgeAttribute(): string
    {
        $source = $this->badge
                ?? $this->segment
                ?? $this->code
                ?? '';

        return strtoupper(Str::of($source)->replace(['-', '_', ' '], '')->substr(0, 3));
    }

    /**
     * رابط أول صورة أو Placeholder افتراضي
     */
    public function getFirstImageUrlAttribute(): string
    {
        $path = optional($this->images->first())->path;
        return $path ? asset($path) : 'https://picsum.photos/seed/'.$this->id.'/900/600';
    }
}
