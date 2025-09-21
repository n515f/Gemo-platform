<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // نحمي أنفسنا أثناء أوامر CLI أو قبل إنشاء الجداول
        $settings = [];
        if (App::runningInConsole() === false && Schema::hasTable('settings')) {
            // خزّن الإعدادات في الكاش لتحسين الأداء
            $settings = Cache::rememberForever('app.settings', function () {
                return Setting::pluck('value', 'key')->toArray();
            });
        }

        // اللغة والـRTL
        $lang  = app()->getLocale();
        $isRtl = ($lang === 'ar');

        // قيَم مشتقّة وجاهزة للاستخدام في القوالب
        $brand = $settings['company.name_' . ($lang === 'ar' ? 'ar' : 'en')]
                 ?? config('app.name', 'Laravel');

        $tag   = $settings['company.tagline_' . ($lang === 'ar' ? 'ar' : 'en')]
                 ?? __('app.tagline');

        $logoPath    = $settings['company.logo']    ?? 'images/logo.png';
        $managerPath = $settings['company.manager'] ?? 'images/manager.png';

        // الوضع الليلي الافتراضي (مخزّن كنص "true"/"false" في DB؟)
        $darkDefault = ($settings['ui.dark_mode_default'] ?? 'false') === 'true';

        // مشاركة القيم مع جميع الواجهات
        View::share([
            'settings'    => $settings,
            'isRtl'       => $isRtl,
            'brand'       => $brand,
            'tag'         => $tag,
            'logoPath'    => $logoPath,
            'managerPath' => $managerPath,
            'darkDefault' => $darkDefault,
        ]);
    }
}