<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $ads = \App\Models\Ad::active()->for('catalog')->latest()->take(10)->get();
        $q = trim($request->get('q', ''));
        $locale = app()->getLocale() === 'ar' ? 'ar' : 'en';

        $products = Product::with('images')
            ->when($q !== '', function ($query) use ($q, $locale) {
                $query->where("name_{$locale}", 'like', "%{$q}%")
                      ->orWhere("short_desc_{$locale}", 'like', "%{$q}%")
                      ->orWhere('code', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(12)
            ->withQueryString();
         return view('catalog.index', compact('ads', /* باقي البيانات */));
        return view('catalog.index', compact('products', 'q'));

    }
}
