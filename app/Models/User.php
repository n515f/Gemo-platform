<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// أبقه فقط إذا كنت تستخدم Sanctum فعلاً. وإلا احذفه من use ومن use-trait.
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
     * (لا يوجد عمود role هنا لأن الأدوار تُدار عبر جداول Spatie)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * الحقول المخفية عن المخرجات.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * التحويلات (Casts).
     * في Laravel 12، 'hashed' تُشفّر كلمة المرور تلقائياً.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Accessor اختياري: اسم الدور الأول للمستخدم (للعرض فقط).
     */
    public function getPrimaryRoleAttribute(): ?string
    {
        return $this->roles()->pluck('name')->first();
    }
}