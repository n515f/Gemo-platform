<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->input('q',''));
        $users = User::query()
            ->when($q, fn($qq)=>$qq->where(fn($w)=>$w->where('name','like',"%$q%")->orWhere('email','like',"%$q%")))
            ->with('roles')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $roles = Role::orderBy('name')->pluck('name','id'); // id => name
        return view('admin.users.index', compact('users','roles','q'));
    }

    // تحديث صلاحية المستخدم
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // إزالة الأدوار ثم إسناد الدور الجديد
        $user->syncRoles([$request->role]);
        // اختياري تخزينه كـ role_id في users
        $roleId = \Spatie\Permission\Models\Role::where('name',$request->role)->value('id');
        $user->update(['role_id' => $roleId]);

        return back()->with('success','تم تحديث صلاحية المستخدم');
    }

    // رفع/تحديث الصورة الشخصية
    public function updateAvatar(Request $request, User $user)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048', // 2MB
        ]);

        // حذف القديمة إن كانت في storage
        if ($user->profile_image && !str_starts_with($user->profile_image,'http')) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('avatar')->store('avatars','public');
        $user->update(['profile_image' => $path]);

        return back()->with('success','تم تحديث الصورة الشخصية');
    }
}