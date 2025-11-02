<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;

class ContactController extends Controller
{
    public function index()
    {
        $site = SiteSetting::query()->first();
        return view('contact', compact('site'));
    }
}