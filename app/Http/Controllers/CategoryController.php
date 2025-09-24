<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    // عرض الفئات النشطة فقط
    public function index()
    {
        $categories = Category::where('is_active', true)->latest()->paginate(12);
        return view('categories.index', compact('categories'));
    }

    // عرض منتجات فئة معيّنة (الفئة قد تكون غير نشطة ولكن سنعرض لو رابطها مباشر — غيّر السلوك إن أردت)
    public function show(Category $category)
    {
        $products = $category->products()
            ->with('images')
            ->latest('id')
            ->paginate(12);

        return view('categories.show', compact('category','products'));
    }
}