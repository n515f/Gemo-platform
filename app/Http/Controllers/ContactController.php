<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class ContactController extends Controller
{
    public function index()
    {
        // نجلب جميع الإعدادات على شكل مصفوفة ['key' => 'value']
        $settings = Setting::query()
            ->pluck('value', 'key')
            ->toArray();

        return view('contact', compact('settings'));
    }
}