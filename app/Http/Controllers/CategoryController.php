<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ad;

class CategoryController extends Controller
{
    // عرض الفئات النشطة فقط
    public function index()
    {
        $categories = Category::where('is_active', true)->latest()->paginate(12);

        // إعلانات خاصة بالكتالوج
        $categoryAds = Ad::active()
            ->for('categories')   // أو 'all'
            ->latest()
            ->take(10)
            ->get();

        return view('categories.index', compact('categories','categoryAds'));
    }

    // عرض منتجات فئة معيّنة
    public function show(Category $category)
    {
        $products = $category->products()
            ->with('images')
            ->latest('id')
            ->paginate(12);

        // إعلانات خاصة بالكتالوج أيضًا
        $categoryAds = Ad::active()
            ->for('categories')
            ->latest()
            ->take(10)
            ->get();

        return view('categories.show', compact('category','products','categoryAds'));
    }
}