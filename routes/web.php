<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ConsultationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/product-detail/{product}-{slug}', [HomeController::class, 'productDetail'])->name('product-detail');
Route::get('/card', [HomeController::class, 'card'])->name('card');
Route::post('/order',[HomeController::class,'order'])->name('order');
Route::post('/consultation', [HomeController::class,'store'])->name('consultation');


//Admin
Route::prefix('admin')->group(function () {

    Route::get('/consultations', [ConsultationController::class, 'index'])
        ->name('admin.consultations');

    Route::post('/consultations/{id}/done', [ConsultationController::class, 'done'])
        ->name('admin.consultations.done');

});
Route::get('/debug', function () {
    $thumbs = \App\Models\Thumb::all();
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
