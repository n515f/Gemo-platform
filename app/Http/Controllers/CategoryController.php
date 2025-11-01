<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
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

        // ✅ نأخذ فقط الإعلانات التي صورتها الأولى موجودة فعلاً
        $categoryAds = Ad::active()
            ->for('categories')
            ->latest()
            ->take(10)
            ->get()
            ->filter(function ($ad) {
                $url = $ad->first_image_url;
                if (!$url) return false;

                // لو رابط خارجي صالح
                if (Str::startsWith($url, ['http://','https://'])) return true;

                // حوّل الرابط إلى مسارات فعلية للتحقق
                $path = parse_url($url, PHP_URL_PATH);
                $path = $path ? ltrim($path, '/') : '';

                $possiblePaths = [
                    public_path($path),
                    public_path('storage/' . Str::after($path, 'storage/')),
                    storage_path('app/public/' . Str::after($path, 'storage/')),
                ];

                foreach ($possiblePaths as $p) {
                    if (file_exists($p)) return true;
                }

                return false;
            })
            ->values();

        return view('categories.index', [
            'allCategories' => $allCategories,
            'categoryAds'   => $categoryAds,
            'q'             => $q,
        ]);
    }

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
            ->get()
            ->filter(function ($ad) {
                $url = $ad->first_image_url;
                if (!$url) return false;
                if (Str::startsWith($url, ['http://','https://'])) return true;

                $path = parse_url($url, PHP_URL_PATH);
                $path = $path ? ltrim($path, '/') : '';

                $possiblePaths = [
                    public_path($path),
                    public_path('storage/' . Str::after($path, 'storage/')),
                    storage_path('app/public/' . Str::after($path, 'storage/')),
                ];

                foreach ($possiblePaths as $p) {
                    if (file_exists($p)) return true;
                }

                return false;
            })
            ->values();

        return view('categories.show', compact('category','products','categoryAds'));
    }
}
