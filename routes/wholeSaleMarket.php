<?php

use App\Http\Controllers\CarFeeController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\CropDepositController;
use App\Http\Controllers\CropQualityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DailyPriceController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\FarmerDebtController;
use App\Http\Controllers\LeaveCountController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'whole-sale-market', 'middleware' => ['role:staff,admin,superadmin']], function () {

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', [ProfileController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => '/farmers'], function () {
        Route::get('list', [FarmerController::class, 'index'])->name('farmers#index');

        // Route::get('create/page', [FarmerController::class, 'createPage'])->name('farmers#create#page');
        // Route::post('create', [FarmerController::class, 'create'])->name('farmers#create');

        Route::get('search', [FarmerController::class, 'search'])->name('farmers#search');
        Route::post('create', [FarmerController::class, 'store'])->name('farmers#store');

        Route::get('edit/{id}/page', [FarmerController::class, 'edit'])->name('farmers#edit#page');
        Route::post('update/{id}', [FarmerController::class, 'update'])->name('farmers#update');
        Route::get('history', [FarmerController::class, 'history'])->name('farmers#history');
        Route::get('trashList', [FarmerController::class, 'trashList'])->name('farmers#trashList');
        Route::get('restore/{id}', [FarmerController::class, 'restore'])->name('farmers#restore');
        Route::get('delete/{id}', [FarmerController::class, 'delete'])->name('farmers#delete');

        //farmer settlement
        Route::get('farmerSettlementList/{id}', [FarmerController::class, 'farmerSettlementList'])->name('farmer#farmerSettlementList');
    });

    Route::group(['prefix' => 'Crop'], function () {
        Route::get('directPage', [CropController::class, 'directPage'])->name('Crop#directPage');
        Route::post('create', [CropController::class, 'create'])->name('Crop#create');
        Route::get('list', [CropController::class, 'list'])->name('Crop#list');
        Route::get('edit/{id}', [CropController::class, 'edit'])->name('Crop#edit');
        Route::post('update/{id}', [CropController::class, 'update'])->name('Crop#update');
        Route::get('history', [CropController::class, 'history'])->name('Crop#history');
        Route::get('delete/{id}', [CropController::class, 'delete'])->name('Crop#delete');
        Route::get('trashList', [CropController::class, 'trashList'])->name('Crop#trashList');
        Route::get('restore/{id}', [CropController::class, 'restore'])->name('Crop#restore');
    });

    // Route::group(['middleware' => 'AdminMiddleware'], function () {

    //     Route::group(['prefix' => 'Crop'], function () {
    // Route::get('delete/{id}', [CropController::class, 'delete'])->name('Crop#delete');
    // Route::get('trashList', [CropController::class, 'trashList'])->name('Crop#trashList');
    // Route::get('restore/{id}', [CropController::class, 'restore'])->name('Crop#restore');
    //     });

    // });

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
        Route::get('list', [PurchaseController::class, 'index'])->name('purchases#index');
        // ဝယ်ယူမှု ကျန်ငွေ ထပ်မံပေးချေရန် Route
        Route::post('/{id}/pay-balance', [PurchaseController::class, 'payBalance'])
            ->name('purchases#payBalance');

        Route::get('create/page', [PurchaseController::class, 'createPage'])->name('purchases#create#page');
        Route::get('grades/{crop}', [PurchaseController::class, 'getGrades'])->name('purchases#grades');
        Route::get('reference-price', [PurchaseController::class, 'getReferencePrice'])->name('purchases#reference-price');

        Route::post('/create', [PurchaseController::class, 'store'])->name('purchases#store');
        Route::get('/{purchaseId}/voucher', [PurchaseController::class, 'voucher'])->whereNumber('purchaseId')->name('purchases#voucher');
    });

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

    Route::group(['prefix' => 'FarmerDebt'], function () {
        Route::get('directPage', [FarmerDebtController::class, 'directPage'])->name('FarmerDebt#directPage');
        Route::post('create', [FarmerDebtController::class, 'create'])->name('FarmerDebt#create');
        Route::get('list', [FarmerDebtController::class, 'list'])->name('FarmerDebt#list');
        Route::get('settlement/{id}', [FarmerDebtController::class, 'settlement'])->name('FarmerDebt#settlement');
        Route::post('settlementCreate/{id}', [FarmerDebtController::class, 'settlementCreate'])->name('FarmerDebt#settlementCreate');
        Route::get('settlementList/{id}', [FarmerDebtController::class, 'settlementList'])->name('FarmerDebt#settlementList');
        Route::get('directPageSettlement', [FarmerDebtController::class, 'directPageSettlement'])->name('FarmerDebt#directPageSettlement');
        Route::post('createSettlement', [FarmerDebtController::class, 'createSettlement'])->name('FarmerDebt#createSettlement');
    });

    Route::group(['prefix' => 'crop-diposit'], function () {
        Route::get('list', [CropDepositController::class, 'index'])->name('crop#deposits#index');
        Route::get('createPage', [CropDepositController::class, 'createPage'])->name('crop#deposits#createPage');
        Route::post('create', [CropDepositController::class, 'storeDeposit'])->name('crop#deposits#create');
        Route::get('convert-form/{purchaseId}', [CropDepositController::class, 'showConvertForm'])->name('crop#deposits#convert#form');
        Route::post('convert-to-purchase/{purchaseId}', [CropDepositController::class, 'convertToPurchase'])->name('crop#deposits#convert#purchase');
    });

    Route::group(['prefix' => 'Customer'], function () {
        Route::post('create', [CustomerController::class, 'create'])->name('Customer#create');
        Route::get('search', [CustomerController::class, 'search'])->name('Customer#search');
    });

    Route::group(['prefix' => 'Sale'], function () {
        Route::get('directPage', [SaleController::class, 'directPage'])->name('Sale#directPage');
        Route::post('create', [SaleController::class, 'create'])->name('Sale#create');
        Route::get('avgPrice/{crop_id}', [SaleController::class, 'avgPrice'])->name('Sale#avgPrice');
        Route::post('calculateTodayAveragePrices', [SaleController::class, 'calculateTodayAveragePrices'])->name('Sale#calculateTodayAveragePrices');
        Route::get('voucher/{id}', [SaleController::class, 'voucher'])->name('Sale#voucher');
        Route::get('smallVoucher/{id}', [SaleController::class, 'smallVoucher'])->name('Sale#smallVoucher');
        Route::get('list', [SaleController::class, 'list'])->name('Sale#list');
        Route::post('payBalance/{sale_id}', [SaleController::class, 'payBalance'])->name('Sale#payBlance');
    });

    Route::group(['prefix' => 'Leave'], function () {
        Route::post('create', [LeaveCountController::class, 'create'])->name('Leave#create');
        Route::get('list', [LeaveCountController::class, 'list'])->name('Leave#list');
    });

    Route::group(['prefix' => 'CarFee'], function () {
        Route::get('directPage', [CarFeeController::class, 'directPage'])->name('CarFee#directPage');
        Route::post('create', [CarFeeController::class, 'create'])->name('CarFee#create');
        Route::get('list', [CarFeeController::class, 'list'])->name('CarFee#list');
        Route::get('search', [CarFeeController::class, 'search'])->name('CarFee#search');
        Route::get('voucher/{id}', [CarFeeController::class, 'voucher'])->name('CarFee#voucher');
        Route::get('detail/{id}', [CarFeeController::class, 'detail'])->name('CarFee#detail');
    });

    Route::group(['prefix' => 'Customer'], function () {
        Route::get('list', [CustomerController::class, 'list'])->name('Customer#list');
        Route::get('edit/{id}', [CustomerController::class, 'edit'])->name('Customer#edit');
        Route::post('update/{id}', [CustomerController::class, 'update'])->name('Customer#update');
        Route::get('delete/{id}', [CustomerController::class, 'delete'])->name('Customer#delete');
        Route::get('history', [CustomerController::class, 'history'])->name('Customer#history');
        Route::get('trashList', [CustomerController::class, 'trashList'])->name('Customer#trashList');
        Route::get('restore/{id}', [CustomerController::class, 'restore'])->name('Customer#restore');
    });







    Route::group(['prefix' => 'reports', 'middleware' => ['role:admin,superadmin']], function () {
        Route::get('daily/purchases', [ReportController::class, 'dailyPurchaseReport'])->name('reports#daily#purchases');
        Route::get('daily/sale', [ReportController::class, 'dailySaleReport'])->name('reports#daily#sales');
    });

});
