<?php

use App\Http\Controllers\CropController;
use App\Http\Controllers\DailyPriceController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\GradeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'whole-sale-market', 'middleware' => 'StaffMiddleware'], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');





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

    Route::get('/farmers', [FarmerController::class, 'index'])
        ->name('farmers.index');

    Route::get('/farmers/create', [FarmerController::class, 'create'])
        ->name('farmers.create');

    Route::get('/farmers/{id}/edit', [FarmerController::class, 'edit'])
        ->name('farmers.edit');



});
