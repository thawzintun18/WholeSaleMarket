<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\DailyPrice;
use App\Models\Grade;
use Illuminate\Http\Request;


class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchases.index');
    }

    public function createPage()
    {
        $crops = Crop::orderBy('crop_name')->get();
        return view('purchases.create', compact('crops'));
    }

    // သီးနှံအလိုက် Grade ရှာမည်
    public function getGrades($crop)
    {
        $grades = Grade::where('crop_id', $crop)
            ->orderBy('grade_name')
            ->get(['id', 'grade_name']);
        return response()->json($grades);
    }

    // ယနေ့ ရည်ညွှန်းဝယ်ဈေး ရှာမည်
    public function getReferencePrice(Request $request)
    {
        $price = DailyPrice::where('price_date', today())
            ->where('crop_id', $request->crop_id)
            ->where('grade_id', $request->grade_id)
            ->value('reference_buy_price');
        // dd($price->toArray());

        return response()->json([
            'reference_price' => $price ?? 0
        ]);
    }

    public function edit($id)
    {
        return view('purchases.edit');
    }
}
