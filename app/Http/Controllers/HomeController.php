<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ad;

class HomeController extends Controller
{
    public function index()
    {
        // منتجات مختارة (مع الصور)
        $featuredProducts = Product::with(['images' => function ($q) {
                $q->oldest(); // أول صورة تظهر أولاً
            }])
            ->latest('id')
            ->take(3)
            ->get();

        // الإعلانات المفعّلة فقط (بحد أقصى 10)
        $ads = Ad::where('is_active', true)
            ->latest()
            ->take(10)
            ->get();

        return view('home', compact('featuredProducts', 'ads'));
    }
}