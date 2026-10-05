<?php

use App\Http\Controllers\WholeSaleMarket\CropController;
use App\Http\Controllers\WholeSaleMarket\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'WholeSaleMarket'], function () {

    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('WholeSaleMarket#dashboard');

    Route::group(['prefix' => 'Crop'], function () {
        Route::get('directPage', [CropController::class, 'directPage'])->name('Crop#directPage');
        Route::post('create', [CropController::class, 'create'])->name('Crop#create');
        Route::get('list', [CropController::class, 'list'])->name('Crop#list');
        Route::get('delete/{id}', [CropController::class, 'delete'])->name('Crop#delete');
        Route::get('edit/{id}', [CropController::class, 'edit'])->name('Crop#edit');
        Route::post('update/{id}', [CropController::class, 'update'])->name('Crop#update');

    });

});
