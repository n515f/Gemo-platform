<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    /**
     * صفحة "شاشات العميل" للأدمن فقط.
     * تعرض بطائق تقود إلى صفحات الواجهة العامة (الرئيسية، الخدمات، الكتالوج، RFQ، تواصل معنا).
     */
    public function index(Request $request)
    {
        return view('admin.screens.ClientPortal');
    }
}