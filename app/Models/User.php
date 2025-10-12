<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * الحارس الافتراضي لـ Spatie/Permission (إن كنت تحتاجه).
     */
    protected string $guard_name = 'web';

    /**
     * الحقول المسموح تعبئتها جماعياً.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image', // نخزّن فيه المسار النسبي مثل "avatars/xxx.png"
        'role_id',
    ];

    /**
     * الحقول المخفية.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * التحويلات.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role_id'           => 'integer',
    ];

    /**
     * نطلب إظهار الحقل المحسوب profile_photo_url تلقائياً في الـ JSON/Array.
     */
    protected $appends = ['profile_photo_url'];

    /**
     * Accessor: رابط صورة البروفايل للعرض في الواجهة.
     *
     * الأولوية:
     * 1) إن كان profile_image رابطًا كاملاً (http/https) -> نرجعه كما هو.
     * 2) إن كان مسارًا نسبيًا على قرص public وتوجد الملف -> نرجع Storage::url().
     * 3) إن كان يبدأ بـ /storage/ -> نرجعه كما هو.
     * 4) خلاف ذلك -> صورة افتراضية.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        $path = $this->profile_image;

        if (!empty($path)) {
            // رابط خارجي
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            // يبدأ بـ /storage (مربوط مسبقًا) -> استخدمه كما هو
            if (str_starts_with($path, '/storage/')) {
                return $path;
            }

            // ملف محفوظ على قرص public (مثلاً avatars/xxx.png)
            if (Storage::disk('public')->exists($path)) {
                return Storage::url($path); // ينتج /storage/avatars/xxx.png
            }
        }

        // fallback
        return asset('images/user.png');
    }

    /**
     * اسم الدور الأساسي (اختياري للعرض).
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        return $this->roles()->pluck('name')->first();
    }
}