<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key','value'];

    // نخزّن JSON كسلسلة نصية ولكن نوفّر كاست مريح
    protected $casts = [
        // يمكنك لاحقًا تبديل هذا إلى 'array' إذا حفظت JSON فعلاً
    ];
}
