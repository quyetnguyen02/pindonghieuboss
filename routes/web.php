<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\HomeController;
use App\Models\Thumb;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/product-detail/{product}-{slug}', [HomeController::class, 'productDetail'])->name('product-detail');
Route::get('/card', [HomeController::class, 'card'])->name('card');
Route::post('/order', [HomeController::class, 'order'])->name('order');
Route::post('/consultation', [HomeController::class, 'store'])->name('consultation');

// Admin
Route::prefix('admin')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/consultations', [ConsultationController::class, 'index'])
        ->name('admin.consultations');

    Route::post('/consultations/{id}/done', [ConsultationController::class, 'done'])
        ->name('admin.consultations.done');

    // Shop management
    Route::get('/shop/edit', [ShopController::class, 'editShopInfo'])
        ->name('admin.shop.edit');
    Route::put('/shop/update', [ShopController::class, 'updateShopInfo'])
        ->name('admin.shop.update');

    // Banner management
    Route::get('/banners', [ShopController::class, 'manageBanners'])
        ->name('admin.banners');
    Route::post('/banners', [ShopController::class, 'storeBanner'])
        ->name('admin.banners.store');
    Route::delete('/banners/{banner}', [ShopController::class, 'deleteBanner'])
        ->name('admin.banners.delete');

    // Product management
    Route::get('/products', [ProductController::class, 'index'])
        ->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])
        ->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])
        ->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('admin.products.destroy');

});
Route::get('/debug', function () {
    $thumbs = Thumb::all();
    foreach ($thumbs as $thumb) {

        $thumb->src = str_replace('-600x600', '', $thumb->src);
        $thumb->save();

    }
    dd('xong');

    //    return [
    //        'database' => DB::connection()->getDatabaseName(),
    //        'host' => config('database.connections.mysql.host'),
    //        'session_driver' => config('session.driver'),
    //        'has_table' => Schema::hasTable('sessions'),
    //    ];
});
