<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function dailyPurchaseReport(Request $request)
    {
        // 1. ရက်စွဲ (Default: ယနေ့ရက်စွဲ)
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $cropId = $request->input('crop_id');
        if (!$cropId) {
            $firstCrop = DB::table('crops')->orderBy('crop_name')->first();
            $cropId = $firstCrop ? $firstCrop->id : null;
        }

        $gradeId = $request->input('grade_id');

        // 2. သီးနှံအမျိုးအစားများ (Filter တွင် ရွေးချယ်ရန်)
        $crops = DB::table('crops')->orderBy('crop_name')->get();

        // ရွေးချယ်ထားသော သီးနှံအလိုက် Grade များကို ဆွဲထုတ်ရန် (Ajax သို့မဟုတ် Select အတွက်)
        $grades = collect();
        if ($cropId) {
            $grades = DB::table('grades')->where('crop_id', $cropId)->get();
        } else {
            $grades = DB::table('grades')->get();
        }

        // 3. Purchase Items များကို ရက်စွဲနှင့် Filter များအလိုက် ဆွဲထုတ်ခြင်း
        $query = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('farmers', 'purchases.farmer_id', '=', 'farmers.id')
            ->join('crops', 'purchase_items.crop_id', '=', 'crops.id')
            ->join('grades', 'purchase_items.grade_id', '=', 'grades.id')
            ->select(
                'purchase_items.purchase_id',
                'purchases.purchase_date',
                'farmers.name as farmer_name',
                'farmers.village as farmer_village',
                'crops.crop_name',
                'crops.unit',
                'grades.grade_name',
                'purchase_items.quantity',
                'purchase_items.bag_count',
                'purchase_items.actual_buy_price',
                'purchase_items.gross_amount',
                'purchase_items.note'
            );

        if ($cropId) {
            $query->where('purchase_items.crop_id', $cropId);
        }

        if ($gradeId) {
            $query->where('purchase_items.grade_id', $gradeId);
        }

        $query->whereDate('purchases.purchase_date', '>=', $startDate)
            ->whereDate('purchases.purchase_date', '<=', $endDate);

        $items = $query->orderBy('purchases.id', 'desc')->get();

        // 4. စုစုပေါင်းများ တွက်ချက်ခြင်း (Total Quantity & Total Amount)
        $totalQuantity = $items->sum('quantity');
        $totalAmount = $items->sum('gross_amount');
        $totalBagCount = $items->where('unit', 'pound')->sum('bag_count');
        // စုစုပေါင်း ဘောင်ချာအရေအတွက် (Total Purchases Count)
        $totalPurchases = $items->pluck('purchase_id')->unique()->count();

        return view('reports.daily_purchase', compact(
            'items', 'crops', 'grades', 'startDate', 'endDate', 'cropId', 'gradeId', 'totalQuantity', 'totalAmount', 'totalPurchases', 'totalBagCount'
        ));
    }
}
