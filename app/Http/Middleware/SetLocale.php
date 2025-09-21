<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        // اجلب اللغة من السيشن أو من config
        $locale = session('locale', config('app.locale', 'ar'));

        // تحقق أنها لغة مدعومة
        if (! in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }

        // ثبت اللغة للتطبيق
        App::setLocale($locale);

        // شارك اللغة مع الطلب الحالي (مفيد إذا احتجت تقرأها في الكنترولر)
        $request->attributes->set('locale', $locale);

        // شارك اتجاه الصفحة + فلاغ isRtl لكل الواجهات
        view()->share([
            'dir'   => $locale === 'ar' ? 'rtl' : 'ltr',
            'isRtl' => $locale === 'ar',
            'locale'=> $locale,
        ]);

        return $next($request);
    }
}