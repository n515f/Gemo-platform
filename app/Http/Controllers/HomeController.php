<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ad;

class HomeController extends Controller
{
    public function index()
    {
        // منتجات مميزة للواجهة
        $featuredProducts = Product::with('images')
            ->latest('id')
            ->take(3)
            ->get();

        // إعلانات مخصصة لصفحة الهوم فقط (أو العامة 'all')
        $homeAds = Ad::active()
            ->for('home')      // ← يطابق scopeFor() في موديل Ad
            ->latest()
            ->take(10)
            ->get();

        return view('home', compact('featuredProducts', 'homeAds'));
    }
}