<?php

use App\Http\Controllers\CropController;
use App\Http\Controllers\CropQualityController;
use App\Http\Controllers\DailyPriceController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'whole-sale-market', 'middleware' => 'StaffMiddleware'], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::group(['prefix' => '/farmers'], function () {
        Route::get('list', [FarmerController::class, 'index'])->name('farmers#index');

        Route::get('create/page', [FarmerController::class, 'createPage'])->name('farmers#create#page');
        // Route::post('create', [FarmerController::class, 'create'])->name('farmers#create');

        Route::get('search', [FarmerController::class, 'search'])->name('farmers#search');
        Route::post('create', [FarmerController::class, 'store'])->name('farmers#store');

        Route::get('edit/{id}/page', [FarmerController::class, 'edit'])->name('farmers#edit#page');
        Route::post('update/{id}', [FarmerController::class, 'update'])->name('farmers#update');
        Route::get('history', [FarmerController::class, 'history'])->name('farmers#history');
        Route::get('trashList', [FarmerController::class, 'trashList'])->name('farmers#trashList');
        Route::get('restore/{id}', [FarmerController::class, 'restore'])->name('farmers#restore');
        Route::get('delete/{id}', [FarmerController::class, 'delete'])->name('farmers#delete');
    });

    Route::group(['prefix' => 'Crop'], function () {
        Route::get('directPage', [CropController::class, 'directPage'])->name('Crop#directPage');
        Route::post('create', [CropController::class, 'create'])->name('Crop#create');
        Route::get('list', [CropController::class, 'list'])->name('Crop#list');
        Route::get('delete/{id}', [CropController::class, 'delete'])->name('Crop#delete');
        Route::get('edit/{id}', [CropController::class, 'edit'])->name('Crop#edit');
        Route::post('update/{id}', [CropController::class, 'update'])->name('Crop#update');
        Route::get('history', [CropController::class, 'history'])->name('Crop#history');
        Route::get('trashList', [CropController::class, 'trashList'])->name('Crop#trashList');
        Route::get('restore/{id}', [CropController::class, 'restore'])->name('Crop#restore');
    });

    Route::group(['prefix' => 'Quality'], function () {
        Route::get('directPage', [CropQualityController::class, 'directPage'])->name('Quality#directPage');
        Route::post('create', [CropQualityController::class, 'create'])->name('Quality#create');
        Route::get('list', [CropQualityController::class, 'list'])->name('Quality#list');
        Route::get('delete/{id}', [CropQualityController::class, 'delete'])->name('Quality#delete');
        Route::get('edit/{id}', [CropQualityController::class, 'edit'])->name('Quality#edit');
        Route::post('update/{id}', [CropQualityController::class, 'update'])->name('Quality#update');
        Route::get('trashList', [CropQualityController::class, 'trashList'])->name('Quality#trashList');
        Route::get('restore/{id}', [CropQualityController::class, 'restore'])->name('Quality#restore');
        Route::get('history', [CropQualityController::class, 'history'])->name('Quality#history');
    });

    Route::group(['prefix' => 'purchases'], function () {
        Route::get('create/page', [PurchaseController::class, 'createPage'])->name('purchases#create#page');
        Route::get('grades/{crop}', [PurchaseController::class, 'getGrades'])->name('purchases#grades');
        Route::get('reference-price', [PurchaseController::class, 'getReferencePrice'])->name('purchases#reference-price');
    });

    // Route::resource('crops', CropController::class)
    //     ->except(['show']);
    // Route::resource('grades', GradeController::class)
    //     ->except(['show']);

    // Route::get('/daily-prices', [DailyPriceController::class, 'index'])
    //     ->name('daily-prices.index');

    // Route::get('/daily-prices/create', [DailyPriceController::class, 'create'])
    //     ->name('daily-prices.create');

    // Route::get('/daily-prices/{id}/edit', [DailyPriceController::class, 'edit'])
    //     ->name('daily-prices.edit');

    // Route::get('/farmers', [FarmerController::class, 'index'])
    //     ->name('farmers.index');

    // Route::get('/farmers/create', [FarmerController::class, 'create'])
    //     ->name('farmers.create');

    Route::get('/farmers/{id}/edit', [FarmerController::class, 'edit'])
        ->name('farmers.edit');

    Route::get('/purchases', [PurchaseController::class, 'index'])
        ->name('purchases.index');

    // Route::get('/purchases/create', [PurchaseController::class, 'create'])
    //     ->name('purchases.create');

    Route::get('/purchases/{id}/edit', [PurchaseController::class, 'edit'])
        ->name('purchases.edit');

    Route::get('/purchases/show', [PurchaseController::class, 'show'])
        ->name('purchases.show');

    Route::get('/purchases/{id}/voucher', [PurchaseController::class, 'voucher'])
        ->name('purchases.voucher');

    Route::group(['prefix' => 'DailyPrice'], function () {
        Route::get('directPage', [DailyPriceController::class, 'directPage'])->name('DailyPrice#directPage');
        Route::post('create', [DailyPriceController::class, 'create'])->name('DailyPrice#create');
        Route::get('list', [DailyPriceController::class, 'list'])->name('DailyPrice#list');
        Route::get('getGrades/{id}', [DailyPriceController::class, 'getGrades'])->name('DailyPrice#getGrades');
        Route::get('delete/{id}', [DailyPriceController::class, 'delete'])->name('DailyPrice#delete');
        Route::get('trashList', [DailyPriceController::class, 'trashList'])->name('DailyPrice#trashList');
        Route::get('restore/{id}', [DailyPriceController::class, 'restore'])->name('DailyPrice#restore');
        Route::get('edit/{id}', [DailyPriceController::class, 'edit'])->name('DailyPrice#edit');
        Route::post('update/{id}', [DailyPriceController::class, 'update'])->name('DailyPrice#update');
        Route::get('history', [DailyPriceController::class, 'history'])->name('DailyPrice#history');
    });
});
