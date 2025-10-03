<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * الحارس الافتراضي الذي تستخدمه Spatie/Permission.
     */
    protected string $guard_name = 'web';

    /**
     * الحقول المسموح تعبئتها جماعياً.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',   // مسار الصورة داخل storage/app/public مثلاً
        'role_id',         // اختياري؛ إن كنت تستخدم Spatie يكفي pivot لكن أبقيناه بناءً على طلبك
    ];

    /**
     * الحقول المخفية.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * التحويلات (Casts).
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'role_id'           => 'integer',
    ];

    /**
     * اسم الدور الأساسي (للعرض فقط).
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        return $this->roles()->pluck('name')->first();
    }

    /**
     * رابط الصورة المعروضة في الواجهة.
     * - لو كانت قيمة profile_image URL كامل أو مسار يبدأ بـ "/" نعيده كما هو.
     * - غير ذلك نفترض أنها محفوظة تحت storage/public فنربطها بـ asset('storage/...').
     * - وإلا صورة افتراضية.
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        if (!empty($this->profile_image)) {
            if (Str::startsWith($this->profile_image, ['http://', 'https://', '/'])) {
                return $this->profile_image;
            }
            return asset('storage/' . ltrim($this->profile_image, '/'));
        }

        return asset('images/user.png');
    }
}