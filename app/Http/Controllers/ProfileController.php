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
    public function index()
    {
        $today = Carbon::today();

        // ၁။ ယနေ့ ဝယ်ယူငွေ (တောဘောင်ချာ) နှင့် ဘောင်ချာအရေအတွက်
        $todayPurchases = DB::table('purchases')
            ->whereDate('purchase_date', $today)
            ->get();

        $todayPurchaseTotal = $todayPurchases->sum('net_amount');
        $todayPurchaseCount = $todayPurchases->count();

        // ၂။ ယနေ့ ရောင်းရငွေ (မြို့ဘောင်ချာ / စျေးအရောင်း)
        // (Note: Your sale table name or column might differ, adjust as needed)
        $todaySales = DB::table('sales') // သို့မဟုတ် 'Sale' model / table
            ->whereDate('sale_date', $today)
            ->get();

        $todaySaleTotal = $todaySales->sum('net_amount');
        $todaySaleCount = $todaySales->count();

        // ၃။ တောင်သူများကို ပေးရန်ကျန်ငွေ စုစုပေါင်း (Pending Balance)
        $totalPendingBalance = DB::table('purchases')
            ->sum('balance_amount');

        // ၄။ တောင်သူကြိုသုံးငွေ (Advances) စုစုပေါင်း လက်ကျန်
        // (Note: Adjust table name based on your database schema, e.g., 'farmer_debts' or 'advances')
        $totalAdvanceDebt = DB::table('farmer_debts')
            ->sum('balance_amount'); // သို့မဟုတ် sum('amount')

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

        // ၇။ ၇ ရက်စာ ဂရပ်အတွက် အချက်အလက်များ (Weekly Chart Data)
        $chartLabels = [];
        $chartPurchaseData = [];
        $chartSaleData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('M d'); // ဥပမာ - Oct 24

            // နေ့အလိုက် ဝယ်ယူငွေ
            $dailyBuy = DB::table('purchases')
                ->whereDate('purchase_date', $date)
                ->sum('net_amount');
            $chartPurchaseData[] = $dailyBuy;

            // နေ့အလိုက် ရောင်းရငွေ
            $dailySale = DB::table('sales')
                ->whereDate('sale_date', $date)
                ->sum('net_amount');
            $chartSaleData[] = $dailySale;
        }

        // ၈။ သီးနှံအမျိုးအစားအလိုက် ဝယ်ယူမှုအချိုးအစား (Pie Chart အတွက်)
        // (Note: Ensure your purchase items table has crop relationship or crop_name)
        $cropShares = DB::table('purchase_items')
            ->join('crops', 'purchase_items.crop_id', '=', 'crops.id')
            ->select('crops.crop_name', DB::raw('SUM(purchase_items.quantity) as total_qty'))
            ->groupBy('crops.crop_name')
            ->get();

        $cropLabels = $cropShares->pluck('crop_name')->toArray();
        $cropData = $cropShares->pluck('total_qty')->toArray();

        // View ဆီသို့ အချက်အလက်များ ပို့ပေးခြင်း
        return view('dashboard.index', compact(
            'todayPurchaseTotal',
            'todayPurchaseCount',
            'todaySaleTotal',
            'todaySaleCount',
            'totalPendingBalance',
            'totalAdvanceDebt',
            'recentPurchases',
            'recentSales',
            'chartLabels',
            'chartPurchaseData',
            'chartSaleData',
            'cropLabels',
            'cropData'
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
