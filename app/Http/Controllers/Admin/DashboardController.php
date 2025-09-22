<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Project;
use App\Models\Rfq;
use App\Models\TechnicianReport;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'products'        => Product::count(),
            'projects'        => Project::count(),
            // نعتبر الحالات التالية “نشطة”
            'active_projects' => Project::whereIn('status', ['supply','install','maintenance'])->count(),
            'rfqs'            => Rfq::count(),
            'reports_today'   => TechnicianReport::whereDate('created_at', $today)->count(),
        ];

        $recentReports = TechnicianReport::with('project:id,title')
            ->latest()->limit(6)->get();

        $recentRfqs = Rfq::latest()->limit(6)->get(['id','client_name','phone','service','budget','created_at']);

        return view('admin.dashboard', compact('stats','recentReports','recentRfqs'));
    }
}