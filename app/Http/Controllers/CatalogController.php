<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // الإعلانات (إن كانت مستخدمة في الواجهة)
        $ads = \App\Models\Ad::active()->for('catalog')->latest()->take(10)->get();

        // البحث
        $q = trim((string) $request->query('q', ''));
        $locale = app()->getLocale() === 'ar' ? 'ar' : 'en';

        $products = Product::with('images')
            ->when($q !== '', function ($query) use ($q, $locale) {
                $query->where("name_{$locale}", 'like', "%{$q}%")
                      ->orWhere("short_desc_{$locale}", 'like', "%{$q}%")
                      ->orWhere('code', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        // ملاحظة: رجوع واحد صحيح يتضمن كل المتغيّرات المطلوبة
        return view('catalog.index', compact('ads', 'products', 'q'));
    }
}
