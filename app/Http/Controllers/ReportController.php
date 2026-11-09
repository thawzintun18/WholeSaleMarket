<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailyPurchaseReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $cropId = $request->input('crop_id');
        if (!$cropId) {
            $firstCrop = DB::table('crops')->orderBy('crop_name')->first();
            $cropId = $firstCrop ? $firstCrop->id : null;
        }

        $gradeId = $request->input('grade_id');

        $crops = DB::table('crops')->orderBy('crop_name')->get();

        $grades = collect();
        if ($cropId) {
            $grades = DB::table('grades')->where('crop_id', $cropId)->get();
        } else {
            $grades = DB::table('grades')->get();
        }

        $selectedCrop = $crops->where('id', $cropId)->first();
        $qtyPerBasket = (float) ($selectedCrop->quantity_per_basket ?? 0);

        $rawOpeningBalance = 0;
        $openingAverageCost = 0;

        if ($cropId) {
            $previousDate = \Carbon\Carbon::parse($startDate)->subDay()->toDateString();

            // daily_stock_balances မှ အရင်နေ့က total_balance နှင့် average_cost ကို ယူခြင်း
            $stockRecord = DB::table('daily_stock_balances')
                ->where('crop_id', $cropId)
                ->where('balance_date', $previousDate)
                ->select('total_balance', 'average_cost')
                ->first();

            if ($stockRecord) {
                $rawOpeningBalance = $stockRecord->total_balance ?? 0;
                $openingAverageCost = $stockRecord->average_cost ?? 0;
            }
        }

        // မူရင်းယူနစ်မှ တင်း (Basket) သို့ ပြောင်းလဲခြင်း
        $openingBalance = 0;
        if ($qtyPerBasket > 0) {
            $openingBalance = $rawOpeningBalance / $qtyPerBasket;
        }

        // နေ့စဉ်ဝယ်ယူထားသော စာရင်းများ (Purchase Items)
        $purchaseQuery = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('farmers', 'purchases.farmer_id', '=', 'farmers.id')
            ->join('crops', 'purchase_items.crop_id', '=', 'crops.id')
            ->join('grades', 'purchase_items.grade_id', '=', 'grades.id')
            ->select(
                'purchases.id as ref_id',
                'purchases.purchase_date as date',
                'farmers.name as farmer_name',
                'farmers.village as farmer_village',
                'crops.crop_name',
                'crops.unit',
                'crops.quantity_per_basket',
                'grades.grade_name',
                'purchase_items.quantity',
                'purchase_items.bag_count',
                'purchase_items.actual_buy_price',
                'purchase_items.gross_amount',
                'purchase_items.note'
            );

        if ($cropId) $purchaseQuery->where('purchase_items.crop_id', $cropId);
        if ($gradeId) $purchaseQuery->where('purchase_items.grade_id', $gradeId);
        $purchaseQuery->whereDate('purchases.purchase_date', '>=', $startDate)
                    ->whereDate('purchases.purchase_date', '<=', $endDate);
        $purchaseItems = $purchaseQuery->orderBy('purchases.id', 'desc')->get();


        // 4. အပ်နှံထားဆဲ စာရင်းများ (Crop Deposits - Status: Stored)
        $depositQuery = DB::table('crop_deposit_items')
            ->join('crop_deposits', 'crop_deposit_items.crop_deposit_id', '=', 'crop_deposits.id')
            ->join('farmers', 'crop_deposits.farmer_id', '=', 'farmers.id')
            ->join('crops', 'crop_deposit_items.crop_id', '=', 'crops.id')
            ->join('grades', 'crop_deposit_items.grade_id', '=', 'grades.id')
            ->where('crop_deposits.status', 'Stored')
            ->select(
                'crop_deposits.id as ref_id',
                'crop_deposits.deposit_date as date',
                'farmers.name as farmer_name',
                'farmers.village as farmer_village',
                'crops.crop_name',
                'crops.unit',
                'crops.quantity_per_basket',
                'grades.grade_name',
                'crop_deposit_items.quantity',
                'crop_deposit_items.bag_count',
                'crop_deposit_items.actual_buy_price',
                'crop_deposit_items.note'
            );

        if ($cropId) $depositQuery->where('crop_deposit_items.crop_id', $cropId);
        if ($gradeId) $depositQuery->where('crop_deposit_items.grade_id', $gradeId);
        $depositQuery->whereDate('crop_deposits.deposit_date', '>=', $startDate)
                    ->whereDate('crop_deposits.deposit_date', '<=', $endDate);
        $depositItems = $depositQuery->orderBy('crop_deposits.id', 'desc')->get();


        // 5. စုစုပေါင်း တွက်ချက်မှုများ (သီးသန့်စီ)
        $totalAmount = $purchaseItems->sum('gross_amount');
        $totalPurchases = $purchaseItems->pluck('ref_id')->unique()->count();
        $totalDeposits = $depositItems->pluck('ref_id')->unique()->count();

        // ဝယ်ယူမှု တင်းပေါင်း
        $totalPurchaseBasket = 0;
        $totalVissQuantity = 0;
        foreach ($purchaseItems as $item) {
            $rawUnit = strtolower($item->unit ?? '');
            $qty = (float) $item->quantity;
            $bagCount = (int) ($item->bag_count ?? 0);
            $qtyPerBasket = (float) ($item->quantity_per_basket ?? 0);

            if ($rawUnit === 'viss') {
                $totalVissQuantity += $qty;
            }

            if ($qtyPerBasket > 0) {
                $calcQty = $qty;
                if ($rawUnit === 'pound') {
                    $calcQty = max(0, $qty - ($bagCount * 1));
                }
                $totalPurchaseBasket += ($calcQty / $qtyPerBasket);
            }
        }

        // အပ်နှံမှု တင်းပေါင်း
        $totalDepositBasket = 0;
        $totalDepositVissQuantity = 0;
        foreach ($depositItems as $item) {
            $rawUnit = strtolower($item->unit ?? '');
            $qty = (float) $item->quantity;
            $bagCount = (int) ($item->bag_count ?? 0);
            $qtyPerBasket = (float) ($item->quantity_per_basket ?? 0);

            $totalDepositVissQuantity += $qty;

            if ($qtyPerBasket > 0) {
                $calcQty = $qty;
                if ($rawUnit === 'pound') {
                    $calcQty = max(0, $qty - ($bagCount * 1));
                }
                $totalDepositBasket += ($calcQty / $qtyPerBasket);
            }
        }
        // dd($purchaseItems->toArray());

        return view('reports.daily_purchase', compact(
            'purchaseItems', 'depositItems', 'crops', 'grades',
            'startDate', 'endDate', 'cropId', 'gradeId',
            'totalAmount', 'totalPurchases', 'totalDeposits',
            'totalPurchaseBasket', 'totalDepositBasket', 'openingBalance', 'openingAverageCost', 'totalVissQuantity', 'totalDepositVissQuantity'
        ));
    }

    public function dailySaleReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $cropId = $request->input('crop_id');
        if (!$cropId) {
            $firstCrop = DB::table('crops')->orderBy('crop_name')->first();
            $cropId = $firstCrop ? $firstCrop->id : null;
        }

        $crops = DB::table('crops')->orderBy('crop_name')->get();
        $selectedCrop = $crops->where('id', $cropId)->first();
        $qtyPerBasket = (float) ($selectedCrop->quantity_per_basket ?? 0);

        // ၁။ အရင်နေ့က လက်ကျန် (Opening Balance)
        $rawOpeningBalance = 0;
        $openingAverageCost = 0;

        if ($cropId) {
            $previousDate = \Carbon\Carbon::parse($startDate)->subDay()->toDateString();
            $stockRecord = DB::table('daily_stock_balances')
                ->where('crop_id', $cropId)
                ->where('balance_date', $previousDate)
                ->select('total_balance', 'average_cost')
                ->first();

            if ($stockRecord) {
                $rawOpeningBalance = $stockRecord->total_balance ?? 0;
                $openingAverageCost = $stockRecord->average_cost ?? 0;
            }
        }

        $openingBalance = $qtyPerBasket > 0 ? ($rawOpeningBalance / $qtyPerBasket) : 0;

        // ၂။ ယနေ့ ဝယ်ယူမှု တင်းပေါင်း တွက်ချက်ရန် (Daily Purchases for this date range)
        $totalPurchaseBasket = 0;
        if ($cropId) {
            $purchaseItems = DB::table('purchase_items')
                ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                ->join('crops', 'purchase_items.crop_id', '=', 'crops.id')
                ->where('purchase_items.crop_id', $cropId)
                ->whereDate('purchases.purchase_date', '>=', $startDate)
                ->whereDate('purchases.purchase_date', '<=', $endDate)
                ->select('purchase_items.quantity', 'purchase_items.bag_count', 'crops.unit', 'crops.quantity_per_basket')
                ->get();

            foreach ($purchaseItems as $pItem) {
                $rawUnit = strtolower($pItem->unit ?? '');
                $pQty = (float) $pItem->quantity;
                $pBag = (int) ($pItem->bag_count ?? 0);
                $pQpb = (float) ($pItem->quantity_per_basket ?? 0);

                if ($pQpb > 0) {
                    $calcPqty = $pQty;
                    if ($rawUnit === 'pound') {
                        $calcPqty = max(0, $pQty - ($pBag * 1));
                    }
                    $totalPurchaseBasket += ($calcPqty / $pQpb);
                }
            }
        }

        // ၃။ Sale Items များကို ဆွဲထုတ်ခြင်း
        $query = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->join('crops', 'sale_items.crop_id', '=', 'crops.id')
            ->select(
                'sale_items.sale_id',
                'sales.invoice_no',
                'sales.sale_date',
                'customers.name as customer_name',
                'customers.address as customer_address',
                'crops.crop_name',
                'crops.unit',
                'crops.quantity_per_basket',
                'sale_items.quantity',
                'sale_items.bag_count',
                'sale_items.sale_price',
                'sale_items.gross_amount',
                'sale_items.note'
            );

        if ($cropId) {
            $query->where('sale_items.crop_id', $cropId);
        }

        $query->whereDate('sales.sale_date', '>=', $startDate)
            ->whereDate('sales.sale_date', '<=', $endDate);

        $items = $query->orderBy('sales.id', 'desc')->get();

        // ၄။ ရောင်းချမှု တင်းပေါင်း တွက်ချက်ခြင်း
        $totalSaleBasket = 0;
        $totalVissQuantity = 0;
        $items->transform(function ($item) use (&$totalSaleBasket, &$totalVissQuantity) {
            $rawUnit = strtolower($item->unit ?? '');
            $item->display_unit = $rawUnit === 'viss' ? 'ပိဿာ' : ($rawUnit === 'pound' ? 'ပေါင်' : $item->unit);

            $qty = (float) $item->quantity;
            $bagCount = (int) ($item->bag_count ?? 0);
            $itemQtyPerBasket = (float) ($item->quantity_per_basket ?? 0);

            if ($rawUnit === 'viss') {
                $totalVissQuantity += $qty;
            }

            $calcQty = $qty;
            if ($rawUnit === 'pound') {
                $calcQty = max(0, $qty - ($bagCount * 1));
            }

            $item->basket_count = $itemQtyPerBasket > 0 ? ($calcQty / $itemQtyPerBasket) : 0;
            $totalSaleBasket += $item->basket_count;

            return $item;
        });

        $totalAmount = $items->sum('gross_amount');
        $totalSales = $items->pluck('sale_id')->unique()->count();

        // ၅။ ယနေ့ ကျန်ရှိလက်ကျန် (Closing Balance = Opening + Purchase - Sale)
        $closingBalance = ($openingBalance + $totalPurchaseBasket) - $totalSaleBasket;

        return view('reports.daily_sale', compact(
            'items', 'crops', 'startDate', 'endDate', 'cropId',
            'totalAmount', 'totalSales', 'totalSaleBasket', 'totalPurchaseBasket',
            'openingBalance', 'openingAverageCost', 'closingBalance', 'totalVissQuantity'
        ));
    }

    public function dailyProfitReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $cropId = $request->input('crop_id');
        if (!$cropId) {
            $firstCrop = DB::table('crops')->orderBy('crop_name')->first();
            $cropId = $firstCrop ? $firstCrop->id : null;
        }

        $crops = DB::table('crops')->orderBy('crop_name')->get();
        
        // ရွေးချယ်ထားသော သီးနှံ၏ တစ်တင်းဝင်ဆံ့ပမာဏ (quantity_per_basket) ကို ယူရန်
        $selectedCrop = $crops->where('id', $cropId)->first();
        $qtyPerBasket = (float) ($selectedCrop->quantity_per_basket ?? 0);

        // စတင်ရက်စွဲ၏ ယခင်နေ့မှ လက်ကျန်တန်ဖိုး (Opening Inventory Value) တွက်ချက်ခြင်း
        $openingInventoryValue = 0;
        if ($cropId) {
            $previousDate = \Carbon\Carbon::parse($startDate)->subDay()->toDateString();
            $openingStock = DB::table('daily_stock_balances')
                ->where('crop_id', $cropId)
                ->where('balance_date', $previousDate)
                ->select('total_balance', 'average_cost')
                ->first();

            if ($openingStock) {
                $openingBal = (float) ($openingStock->total_balance ?? 0);
                $openingAvgCost = (float) ($openingStock->average_cost ?? 0);

                // total_balance ကို တင်း (Basket) သို့ ပြောင်းလဲခြင်း
                $openingBasket = $qtyPerBasket > 0 ? ($openingBal / $qtyPerBasket) : 0;

                // တင်း (Basket) × တစ်တင်းဈေး (Average Cost)
                $openingInventoryValue = $openingBasket * $openingAvgCost;
            }
        }

        // ရွေးချယ်ထားသော ရက်စွဲအတွင်း ဝယ်ယူငွေ စုစုပေါင်း (Total Purchases from Start to End)
        $totalPurchaseAmount = 0;
        if ($cropId) {
            $totalPurchaseAmount = DB::table('purchase_items')
                ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                ->where('purchase_items.crop_id', $cropId)
                ->whereDate('purchases.purchase_date', '>=', $startDate)
                ->whereDate('purchases.purchase_date', '<=', $endDate)
                ->sum('purchase_items.gross_amount');
        }

        // ** ပွဲရုံမှ အသုံးပြုထားသော စုစုပေါင်းငွေ / ကုန်ကျစရိတ် (Total Investment) **
        $totalInvestment = $openingInventoryValue + $totalPurchaseAmount;


        // ၃။ ရွေးချယ်ထားသော ရက်စွဲအတွင်း ရောင်းရငွေ စုစုပေါင်း (Total Sales Revenue from Start to End)
        $totalSalesRevenue = 0;
        if ($cropId) {
            $totalSalesRevenue = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sale_items.crop_id', $cropId)
                ->whereDate('sales.sale_date', '>=', $startDate)
                ->whereDate('sales.sale_date', '<=', $endDate)
                ->sum('sale_items.gross_amount');
        }

        // ၄။ ကုန်ဆုံးရက်၏ ကျန်ရှိလက်ကျန်တန်ဖိုး (Ending Inventory Value) တွက်ချက်ခြင်း
        $endingInventoryValue = 0;
        if ($cropId) {
            $closingStock = DB::table('daily_stock_balances')
                ->where('crop_id', $cropId)
                ->where('balance_date', $endDate)
                ->select('total_balance', 'average_cost')
                ->first();

            if ($closingStock) {
                $closingBal = (float) ($closingStock->total_balance ?? 0);
                $closingAvgCost = (float) ($closingStock->average_cost ?? 0);

                // total_balance ကို တင်း (Basket) သို့ ပြောင်းလဲခြင်း
                $closingBasket = $qtyPerBasket > 0 ? ($closingBal / $qtyPerBasket) : 0;

                // တင်း (Basket) × တစ်တင်းဈေး (Average Cost)
                $endingInventoryValue = $closingBasket * $closingAvgCost;
            }
        }

        // ** ပွဲရုံမှ ပြန်လည်ရရှိမည့် စုစုပေါင်းတန်ဖိုး (Total Return = Sales Revenue + Remaining Stock Value) **
        $totalReturn = $totalSalesRevenue + $endingInventoryValue;

        // ၅။ အစစ်အမှန် အမြတ်ငွေ (Net Profit = Total Return - Total Investment)
        $netProfit = $totalReturn - $totalInvestment;

        return view('reports.daily_profit', compact(
            'crops', 'cropId', 'startDate', 'endDate',
            'openingInventoryValue', 'totalPurchaseAmount', 'totalInvestment',
            'totalSalesRevenue', 'endingInventoryValue', 'totalReturn',
            'netProfit'
        ));
    }
}
