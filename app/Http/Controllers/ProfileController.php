<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();

        // كشف الأدمن بعدة طرق شائعة
        $isAdmin = false;
        if (method_exists($user, 'hasRole')) {
            $isAdmin = (bool) $user->hasRole('admin');
        } elseif (isset($user->is_admin)) {
            $isAdmin = (bool) $user->is_admin;
        } elseif (isset($user->role)) {
            $isAdmin = $user->role === 'admin';
        }

        return view('profile.edit', [
            'user'    => $user,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email,'.$user->id],
            'password' => ['nullable','confirmed', Password::min(8)],
        ]);

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('status','profile-updated');
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => ['required']]);

        if (! Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => __('The password is incorrect.')]);
        }
         $user = $request->user();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status','account-deleted');
    }

    // ===== الصورة الشخصية (تعتمد الحقل: profile_image) =====
    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ]);

        $user = $request->user();

        // حذف القديمة إن وُجدت
        if (!empty($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        // تخزين الجديدة
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->profile_image = $path;
        $user->save();

        return back()->with('status','avatar-updated');
    }

    public function destroyAvatar(Request $request)
    {
        $user = $request->user();

        if (!empty($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
            $user->profile_image = null;
            $user->save();
        }

        return back()->with('status','avatar-deleted');
    }
}

