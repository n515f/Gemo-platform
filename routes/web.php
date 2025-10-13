<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryAdminController;
use App\Http\Controllers\Admin\ProductAdminController;
use App\Http\Controllers\Admin\ProjectAdminController;
use App\Http\Controllers\Admin\RfqAdminController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\ClientPortalController;
use App\Http\Controllers\Admin\AdAdminController;
use App\Http\Controllers\Admin\AdminUsersController;

/*
|--------------------------------------------------------------------------
| واجهة الزائر / العميل
|--------------------------------------------------------------------------
*/
Route::middleware(['setlocale'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::view('/about', 'about')->name('about');
    Route::view('/services', 'services.index')->name('services.index');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');

    Route::get('/works',   [ProjectController::class, 'publicIndex'])->name('works.index');
    Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');

    Route::get('/rfq',  [RfqController::class, 'create'])->name('rfq.create');
    Route::post('/rfq', [RfqController::class, 'store'])->middleware('auth')->name('rfq.store');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

    // تبديل اللغة
    Route::get('/lang/{locale}', function (string $locale) {
        $locale = in_array($locale, ['ar','en']) ? $locale : 'ar';
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return back();
    })->name('lang.switch');
});

/*
|--------------------------------------------------------------------------
| تقارير الفنيين (واجهة المستخدم/الموظف)
|--------------------------------------------------------------------------
|
| ملاحظة مهمة: اسم بارامتر الراوت {report} ليتوافق مع Requests (authorize)
|
*/
Route::middleware(['auth','setlocale'])->group(function () {
    // يمكن لأي مستخدم مسجّل عرض قائمته/تفاصيل تقريره (الفلترة تتم داخل الكنترولر)
    Route::get('/my/reports',          [ReportController::class, 'index'])->name('reports.index');
    Route::get('/my/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
});

// إنشاء/تعديل/حذف — حصراً لمن لديه دور technician
Route::middleware(['auth','role:technician','setlocale'])->group(function () {
    Route::get('/reports/create',        [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports',              [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}',      [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}',   [ReportController::class, 'destroy'])->name('reports.destroy');
});

/*
|--------------------------------------------------------------------------
| لوحة الإدارة (Admin فقط)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth','role:admin','setlocale'])
    ->group(function () {

        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        // الفئات
        Route::resource('categories', CategoryAdminController::class)->names('admin.categories');
        Route::patch('categories/{category}/toggle', [CategoryAdminController::class, 'toggle'])->name('admin.categories.toggle');
        Route::delete('categories/{category}/icon',  [CategoryAdminController::class, 'destroyIcon'])->name('admin.categories.icon.destroy');

        // المنتجات
        Route::resource('products', ProductAdminController::class)->names('admin.products');
        Route::patch('products/{product}/toggle',          [ProductAdminController::class, 'toggle'])->name('admin.products.toggle');
        Route::delete('products/{product}/images/{image}', [ProductAdminController::class, 'destroyImage'])->name('admin.products.images.destroy');
        Route::post('products/{product}/images/sort',      [ProductAdminController::class, 'sortImages'])->name('admin.products.images.sort');

        // المشاريع + تحديثاتها
        Route::resource('projects', ProjectAdminController::class)->names('admin.projects');
        Route::post('projects/{project}/updates',            [ProjectAdminController::class, 'storeUpdate'])->name('admin.projects.updates.store');
        Route::delete('projects/{project}/updates/{update}', [ProjectAdminController::class, 'destroyUpdate'])->name('admin.projects.updates.destroy');

        // RFQs
        Route::resource('rfqs', RfqAdminController::class)->only(['index','show','update','destroy'])->names('admin.rfqs');
        Route::patch('rfqs/{rfq}/status', [RfqAdminController::class, 'updateStatus'])->name('admin.rfqs.status');

        // تقارير الفنيين (إدارة)
        Route::resource('reports', ReportAdminController::class)
            ->only(['index','show','edit','update','destroy'])
            ->names('admin.reports');

        // تمكين إنشاء تقرير من لوحة الأدمن (اختياري)
        Route::get('reports/create',  [ReportAdminController::class, 'create'])->name('admin.reports.create');
        Route::post('reports',        [ReportAdminController::class, 'store'])->name('admin.reports.store');

        // حذف مرفق مفرد
        Route::delete('reports/{report}/attachment', [ReportAdminController::class,'destroyAttachment'])
            ->name('admin.reports.attachment.destroy');

        // شاشات العميل
        Route::get('/screens', [ClientPortalController::class, 'index'])->name('admin.screens.ClientPortal');

        // الإعلانات
        Route::resource('ads', AdAdminController::class)->names('admin.ads');

        // إدارة المستخدمين
        Route::get('users',                [AdminUsersController::class, 'index'])->name('admin.users.index');
        Route::post('users/{user}/role',   [AdminUsersController::class, 'updateRole'])->name('admin.users.role');
        Route::post('users/{user}/avatar', [AdminUsersController::class, 'updateAvatar'])->name('admin.users.avatar');
    });

/*
|--------------------------------------------------------------------------
| Dashboard (Breeze)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth','verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile (Breeze)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');

    // آفاتار المستخدم
    Route::post('/profile/avatar',   [ProfileController::class, 'storeAvatar'])->name('profile.avatar.store');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');
});

require __DIR__.'/auth.php';
