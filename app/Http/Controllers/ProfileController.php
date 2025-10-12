<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // ... موجود عندك: edit() / update() / destroy() ...

    /**
     * رفع صورة الأفاتار إلى storage/app/public/avatars
     * وتحديث عمود profile_image للمستخدم الحالي.
     */
    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'], // 3MB
        ]);

        $user = $request->user();

        // احذف القديمة إن وُجدت
        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // خزّن الجديدة داخل مجلد avatars على قرص public
        $path = $request->file('avatar')->store('avatars', 'public');

        // حدّث العمود profile_image
        $user->forceFill([
            'profile_image' => $path,
        ])->save();

        return back()->with('status', __('تم تحديث الصورة.'));
    }

    /**
     * حذف صورة الأفاتار واستبدالها بالافتراضية.
     */
    public function destroyAvatar(Request $request)
    {
        $user = $request->user();

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->forceFill([
            'profile_image' => null,
        ])->save();

        return back()->with('status', __('تم حذف الصورة.'));
    }
}