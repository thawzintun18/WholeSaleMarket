<?php

// use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/wholeSaleMarket.php';

Route::fallback(function () {
    return back();
});
