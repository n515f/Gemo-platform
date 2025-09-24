<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryAdminController extends Controller
{
    // قائمة
    public function index(Request $request)
    {
        $q       = trim((string)$request->get('q',''));
        $active  = $request->filled('active') ? (int) $request->get('active') : null;

        $rows = Category::query()
            ->when($q !== '', function($qq) use ($q){
                $qq->where('name_ar','like',"%{$q}%")
                   ->orWhere('name_en','like',"%{$q}%")
                   ->orWhere('description_ar','like',"%{$q}%")
                   ->orWhere('description_en','like',"%{$q}%");
            })
            ->when(!is_null($active), fn($qq)=> $qq->where('is_active', $active))
            ->latest()
            ->paginate(12);

        return view('admin.categories.index', compact('rows','q','active'));
    }

    // إنشاء
    public function create()
    {
        return view('admin.categories.create');
    }

    // حفظ جديد
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'        => ['required','string','max:255'],
            'name_en'        => ['required','string','max:255'],
            'description_ar' => ['nullable','string'],
            'description_en' => ['nullable','string'],
            'is_active'      => ['nullable','boolean'],
            'icon'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        if ($request->hasFile('icon')) {
            $stored = $request->file('icon')->store('categories', 'public'); // storage/app/public/categories/...
            $data['icon'] = 'storage/'.$stored; // للعرض عبر asset()
        }

        Category::create($data + ['is_active' => $data['is_active'] ?? true]);

        return redirect()->route('admin.categories.index')
            ->with('ok','✅ تمت إضافة الفئة.');
    }

    // تعديل
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // تحديث
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name_ar'        => ['required','string','max:255'],
            'name_en'        => ['required','string','max:255'],
            'description_ar' => ['nullable','string'],
            'description_en' => ['nullable','string'],
            'is_active'      => ['nullable','boolean'],
            'icon'           => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
            'remove_icon'    => ['nullable','boolean'],
        ]);

        // حذف الأيقونة الحالية لو طلب ذلك
        if (!empty($data['remove_icon']) && $category->icon) {
            $this->deletePublicPath($category->icon);
            $category->icon = null;
        }

        // أيقونة جديدة
        if ($request->hasFile('icon')) {
            if ($category->icon) $this->deletePublicPath($category->icon);
            $stored = $request->file('icon')->store('categories', 'public');
            $data['icon'] = 'storage/'.$stored;
        }

        $category->update($data + ['is_active' => $data['is_active'] ?? false]);

        return redirect()->route('admin.categories.index')
            ->with('ok','🛠 تم تحديث الفئة.');
    }

    // حذف
    public function destroy(Category $category)
    {
        if ($category->icon) $this->deletePublicPath($category->icon);
        $category->delete();

        return back()->with('ok','🗑 تم حذف الفئة.');
    }

    protected function deletePublicPath(?string $publicPath): void
    {
        if (!$publicPath) return;
        if (str_starts_with($publicPath, 'storage/')) {
            $relative = substr($publicPath, strlen('storage/'));
            Storage::disk('public')->delete($relative);
        }
    }
}