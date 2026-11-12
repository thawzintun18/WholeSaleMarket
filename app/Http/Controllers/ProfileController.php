<?php

namespace App\Http\Controllers;

// use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role === 'staff') {
            return redirect()->route('DailyPrice#list');
        }

        $today = Carbon::today();

        // ၁။ ယနေ့ ဝယ်ယူငွေ (တောဘောင်ချာ)
        $todayPurchases = DB::table('purchases')
            ->whereDate('purchase_date', $today)
            ->get();

        $todayPurchaseTotal = $todayPurchases->sum('net_amount');
        $todayPurchaseCount = $todayPurchases->count();

        // ၂။ ယနေ့ ရောင်းရငွေ (မြို့ဘောင်ချာ)
        $todaySales = DB::table('sales')
            ->whereDate('sale_date', $today)
            ->get();

        $todaySaleTotal = $todaySales->sum('net_amount');
        $todaySaleCount = $todaySales->count();

        // ၃။ တောင်သူပေးရန်ကျန်ငွေ
        $totalPendingBalance = DB::table('purchases')
            ->sum('balance_amount');

        // ၄။ တောင်သူကြိုသုံးငွေ (Advances)
        $totalSalePendingBalance = DB::table('sales')
            ->sum('balance_amount');

        // ၅။ လတ်တလော တောဘောင်ချာများ (နောက်ဆုံး ၅ စောင်)
        $recentPurchases = DB::table('purchases')
            ->leftJoin('farmers', 'farmers.id', 'purchases.farmer_id')
            ->orderBy('purchase_date', 'desc')
            ->limit(5)
            ->get();

        // ၆။ လတ်တလော မြို့ဘောင်ချာများ (နောက်ဆုံး ၅ စောင်)
        $recentSales = DB::table('sales')
            ->leftJoin('customers', 'customers.id', 'sales.customer_id')
            ->orderBy('sale_date', 'desc')
            ->limit(5)
            ->get();

        // ၇။ ၇ ရက်စာ နေ့စဉ် အရောင်းအဝယ် ဂရပ်အချက်အလက်
        $chartLabels = [];
        $chartPurchaseData = [];
        $chartSaleData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('M d');

            $chartPurchaseData[] = DB::table('purchases')
                ->whereDate('purchase_date', $date)
                ->sum('net_amount');

            $chartSaleData[] = DB::table('sales')
                ->whereDate('sale_date', $date)
                ->sum('net_amount');
        }

        // ၈။ သီးနှံအမျိုးအစားအလိုက် ဝယ်ယူမှုအချိုးအစား (Pie/Doughnut Chart)
        $cropShares = DB::table('purchase_items')
            ->join('crops', 'purchase_items.crop_id', '=', 'crops.id')
            ->select('crops.crop_name', DB::raw('SUM(purchase_items.quantity) as total_qty'))
            ->groupBy('crops.crop_name')
            ->get();

        $cropLabels = $cropShares->pluck('crop_name')->toArray();
        $cropData = $cropShares->pluck('total_qty')->toArray();

        // ၉။ ယခုနှစ်အတွက် လစဉ် (1 မှ 12 လ) အရောင်းအဝယ် နှိုင်းယှဉ်ချက် (Monthly Performance)
        $monthlyPurchaseData = [];
        $monthlySaleData = [];
        $currentYear = Carbon::now()->year;

        for ($m = 1; $m <= 12; $m++) {
            $monthlyPurchaseData[] = DB::table('purchases')
                ->whereYear('purchase_date', $currentYear)
                ->whereMonth('purchase_date', $m)
                ->sum('net_amount');

            $monthlySaleData[] = DB::table('sales')
                ->whereYear('sale_date', $currentYear)
                ->whereMonth('sale_date', $m)
                ->sum('net_amount');
        }
        $monthlyLabels = ['ဇန်နဝါရီ', 'ဖေဖော်ဝါရီ', 'မတ်', 'ဧပြီ', 'မေ', 'ဇွန်', 'ဇူလိုင်', 'ဩဂုတ်', 'စက်တင်ဘာ', 'အောက်တိုဘာ', 'နိုဝင်ဘာ', 'ဒီဇင်ဘာ'];


        // ၁၀။ ဝင်ငွေအကောင်းဆုံး သီးနှံစာရင်း (လနှင့် နှစ် အလိုက် စစ်ထုတ်ခြင်း)
        $selectedMonth = $request->input('crop_month', Carbon::now()->month);
        $selectedYear = $request->input('crop_year', Carbon::now()->year);

        $topCropsQuery = DB::table('purchase_items')
            ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->join('crops', 'purchase_items.crop_id', '=', 'crops.id')
            ->whereNull('purchase_items.deleted_at') // SoftDeletes သုံးထားလျှင် ဖျက်ပြီးသားများ မပါစေရန်
            ->whereYear('purchases.purchase_date', $selectedYear);

        // 'all' မဟုတ်မှသာ သတ်မှတ်ထားသော လအလိုက် စစ်ထုတ်မည်
        if ($selectedMonth !== 'all') {
            $topCropsQuery->whereMonth('purchases.purchase_date', $selectedMonth);
        }

        $topCrops = $topCropsQuery->select(
                'crops.crop_name',
                'crops.unit',
                DB::raw('SUM(purchase_items.quantity) as total_qty'),
                DB::raw('SUM(purchase_items.gross_amount) as total_amount')
            )
            ->groupBy('crops.crop_name', 'crops.unit')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'todayPurchaseTotal',
            'todayPurchaseCount',
            'todaySaleTotal',
            'todaySaleCount',
            'totalPendingBalance',
            'totalSalePendingBalance',
            'recentPurchases',
            'recentSales',
            'chartLabels',
            'chartPurchaseData',
            'chartSaleData',
            'cropLabels',
            'cropData',
            'monthlyLabels',
            'monthlyPurchaseData',
            'monthlySaleData',
            'topCrops'
        ));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
