<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ad;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // عرض جميع الفئات النشطة (بدون paginate) مع دعم البحث
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $allCategories = Category::query()
            ->where('is_active', true)
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function($w) use ($q){
                    $w->where('name_ar', 'like', "%{$q}%")
                      ->orWhere('name_en', 'like', "%{$q}%")
                      ->orWhere('description_ar', 'like', "%{$q}%")
                      ->orWhere('description_en', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->get();

        $categoryAds = Ad::active()
            ->for('categories')   // أو 'all'
            ->latest()
            ->take(10)
            ->get();

        return view('categories.index', [
            'allCategories' => $allCategories,
            'categoryAds'   => $categoryAds,
            'q'             => $q,
        ]);
    }

    // عرض منتجات فئة معيّنة
    public function show(Category $category)
    {
        $products = $category->products()
            ->with('images')
            ->latest('id')
            ->paginate(12);

        $categoryAds = Ad::active()
            ->for('categories')
            ->latest()
            ->take(10)
            ->get();

        return view('categories.show', compact('category','products','categoryAds'));
    }
}
