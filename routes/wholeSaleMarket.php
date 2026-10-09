<?php

use App\Http\Controllers\CropController;
use App\Http\Controllers\DailyPriceController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'whole-sale-market', 'middleware' => 'StaffMiddleware'], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::group(['prefix' => '/farmers'], function(){
        Route::get('list', [FarmerController::class, 'index'])->name('farmers#index');
        Route::get('create/page', [FarmerController::class, 'createPage'])->name('farmers#create#page');
        Route::post('create', [FarmerController::class, 'create'])->name('farmers#create');
    });




    Route::resource('crops', CropController::class)
        ->except(['show']);
    Route::resource('grades', GradeController::class)
        ->except(['show']);

    Route::get('/daily-prices', [DailyPriceController::class, 'index'])
        ->name('daily-prices.index');

    Route::get('/daily-prices/create', [DailyPriceController::class, 'create'])
        ->name('daily-prices.create');

    Route::get('/daily-prices/{id}/edit', [DailyPriceController::class, 'edit'])
        ->name('daily-prices.edit');

    // Route::get('/farmers', [FarmerController::class, 'index'])
    //     ->name('farmers.index');

    // Route::get('/farmers/create', [FarmerController::class, 'create'])
    //     ->name('farmers.create');

    Route::get('/farmers/{id}/edit', [FarmerController::class, 'edit'])
        ->name('farmers.edit');

    Route::get('/purchases', [PurchaseController::class, 'index'])
        ->name('purchases.index');

    Route::get('/purchases/create', [PurchaseController::class, 'create'])
        ->name('purchases.create');

    Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])
        ->name('purchases.edit');

    Route::get('/purchases/show', [PurchaseController::class, 'show'])
        ->name('purchases.show');

    Route::get('/purchases/{id}/voucher', [PurchaseController::class, 'voucher'])
        ->name('purchases.voucher');

});
