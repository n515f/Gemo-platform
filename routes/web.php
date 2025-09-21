<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\ProjectAdminController;
use App\Http\Controllers\Admin\RfqAdminController;
use App\Http\Controllers\Admin\ClientPortalController; // ✅
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| واجهة الزائر / العميل
|--------------------------------------------------------------------------
*/
Route::middleware(['setlocale'])->group(function () {
    Route::view('/', 'home')->name('home');
    Route::view('/about', 'about')->name('about');
    Route::view('/services', 'services.index')->name('services.index');
    Route::view('/contact', 'contact')->name('contact');

    Route::get('/works', [ProjectController::class, 'publicIndex'])->name('works.index');
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

    Route::get('/rfq',  [RfqController::class, 'create'])->name('rfq.create');
    Route::post('/rfq', [RfqController::class, 'store'])->middleware('auth')->name('rfq.store');

    // تبديل اللغة
    Route::get('/lang/{locale}', function (string $locale) {
        $locale = in_array($locale, ['ar', 'en']) ? $locale : 'ar';
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return back();
    })->name('lang.switch');
});

/*
|--------------------------------------------------------------------------
| لوحة الإدارة (Admin فقط)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:admin', 'setlocale'])
    ->group(function () {

        Route::view('/', 'admin.dashboard')->name('admin.dashboard');

        // إدارة الكتالوج / المنتجات
        Route::resource('products', ProductAdminController::class)->names('admin.products');
        // مسارات إضافية اختيارية (لو أردت استخدامها)
        
Route::patch('products/{product}/toggle', [ProductAdminController::class, 'toggle'])
    ->name('admin.products.toggle');

Route::delete('products/{product}/images/{image}', [ProductAdminController::class, 'destroyImage'])
    ->name('admin.products.images.destroy');

Route::post('products/{product}/images/sort', [ProductAdminController::class, 'sortImages'])
    ->name('admin.products.images.sort');

        // إدارة المشاريع
        Route::resource('projects', ProjectAdminController::class)->names('admin.projects');
        
    // تحديثات المشروع
    Route::post('projects/{project}/updates', [ProjectAdminController::class, 'storeUpdate'])->name('admin.projects.updates.store');
    Route::delete('projects/{project}/updates/{update}', [ProjectAdminController::class, 'destroyUpdate'])->name('admin.projects.updates.destroy');

        // إدارة RFQs
       // داخل مجموعة الأدمن الحالية:
Route::resource('rfqs', RfqAdminController::class)
    ->only(['index','show','update','destroy'])
    ->names('admin.rfqs');

Route::patch('rfqs/{rfq}/status', [RfqAdminController::class, 'updateStatus'])
    ->name('admin.rfqs.status');

        // تقارير الفنيين
        Route::get('/reports',  [ReportController::class, 'create'])->name('admin.reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('admin.reports.store');

        // ⭐ شاشات العميل (بطائق)
        Route::get('/screens', [ClientPortalController::class, 'index'])->name('admin.screens.ClientPortal');
    });

/*
|--------------------------------------------------------------------------
| Dashboard (Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (لو أردتها لاحقًا من Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';