<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use App\Models\SiteSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // نجلب صف إعدادات الموقع (إن وُجد الجدول)
        $site = (App::runningInConsole() === false && Schema::hasTable('site_settings'))
            ? (SiteSetting::query()->first() ?? null)
            : null;

        $lang  = app()->getLocale();
        $isRtl = ($lang === 'ar');

        // مشاركة القيم مع جميع الواجهات
        View::share([
            'site'   => $site,
            'isRtl'  => $isRtl,
        ]);
    }
}