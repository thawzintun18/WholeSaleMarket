<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\DailyStockBalance;
use App\Models\DailyStockBalanceRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LeaveCountController extends Controller
{
    //create
    public function create(Request $request)
    {
        $today = now()->toDateString();

        DB::transaction(function () use ($today) {

            // ဒီနေ့ Purchase / Sale အဝင်အထွက် စုမယ်
            $movements = DB::table('stock_movements')
                ->select(
                    'crop_id',
                    DB::raw("
                    SUM(
                        CASE
                            WHEN reference_type = 'Purchase'
                            THEN quantity_in
                            ELSE 0
                        END
                    ) AS total_in
                "),
                    DB::raw("
                    SUM(
                        CASE
                            WHEN reference_type = 'Sale'
                            THEN quantity_out
                            ELSE 0
                        END
                    ) AS total_out
                ")
                )
                ->whereDate('movement_date', $today)
                ->whereNull('deleted_at')
                ->whereIn('reference_type', ['Purchase', 'Sale'])
                ->groupBy('crop_id')
                ->get()
                ->keyBy('crop_id');

            // ဒီနေ့ဝယ်ထားသော Pound သီးနှံများ၏ Bag Count
            $purchaseBags = DB::table('purchase_items')
                ->join(
                    'purchases',
                    'purchase_items.purchase_id',
                    '=',
                    'purchases.id'
                )
                ->select(
                    'purchase_items.crop_id',
                    DB::raw('SUM(COALESCE(purchase_items.bag_count, 0)) AS total_bag_count')
                )
                ->whereDate('purchases.purchase_date', $today)
                ->whereNull('purchases.deleted_at')
                ->whereNull('purchase_items.deleted_at')
                ->groupBy('purchase_items.crop_id')
                ->get()
                ->keyBy('crop_id');

            // ဒီနေ့ရောင်းထားသော Pound သီးနှံများ၏ Bag Count
            $saleBags = DB::table('sale_items')
                ->join(
                    'sales',
                    'sale_items.sale_id',
                    '=',
                    'sales.id'
                )
                ->select(
                    'sale_items.crop_id',
                    DB::raw('SUM(COALESCE(sale_items.bag_count, 0)) AS total_bag_count')
                )
                ->whereDate('sales.sale_date', $today)
                ->whereNull('sales.deleted_at')
                ->whereNull('sale_items.deleted_at')
                ->groupBy('sale_items.crop_id')
                ->get()
                ->keyBy('crop_id');

            // လက်ရှိ Stock Balance
            $stockBalances = DB::table('stock_balances')
                ->get()
                ->keyBy('crop_id');

            // သီးနှံအားလုံး
            $crops = Crop::all();

            foreach ($crops as $crop) {

                $movement = $movements->get($crop->id);

                $totalIn  = (float) ($movement->total_in ?? 0);
                $totalOut = (float) ($movement->total_out ?? 0);

                // Pound ဖြစ်မှ Bag Count နုတ်မယ်
                $unit = strtolower(trim($crop->unit ?? ''));

                if (in_array($unit, ['pound', 'ပေါင်'])) {

                    $purchaseBagCount = (float) (
                        $purchaseBags->get($crop->id)->total_bag_count ?? 0
                    );

                    $saleBagCount = (float) (
                        $saleBags->get($crop->id)->total_bag_count ?? 0
                    );

                    // အဝယ် Quantity ထဲမှ Bag Count နုတ်မယ်
                    $totalIn -= $purchaseBagCount;

                    // အရောင်း Quantity ထဲမှ Bag Count နုတ်မယ်
                    $totalOut -= $saleBagCount;
                }

                // ဒီနေ့ အသားတင်လက်ကျန်
                $dailyBalance = $totalIn - $totalOut;

                // လက်ရှိ Stock Balance
                $stock  = $stockBalances->get($crop->id);

                $totalBalance  = $stock->quantity_on_hand ?? 0;
                $averageCost   = $stock->average_cost ?? 0;

                // ရှိပြီးသားစာရင်းရှိရင် Update လုပ်မယ်
                // မရှိသေးရင် Create လုပ်မယ်
                DailyStockBalance::updateOrCreate(
                    [
                        'crop_id'      => $crop->id,
                        'balance_date' => $today,
                    ],
                    [
                        'daily_balance' => $dailyBalance,
                        'total_balance' => $totalBalance,
                        'average_cost'  => $averageCost,
                    ]
                );
            }

            // ဒီနေ့တွက်ချက်မှုမှတ်တမ်းကို Update လုပ်မယ်
            // မရှိသေးရင် Create လုပ်မယ်
            DailyStockBalanceRun::updateOrCreate(
                [
                    'balance_date' => $today,
                ],
                [
                    'calculated_at' => now(),
                ]
            );
        });

        Alert::success(
            'အောင်မြင်ပါသည်',
            'ဒီနေ့အတွက် သီးနှံလက်ကျန်နှင့် ပျမ်းမျှဈေးနှုန်းများ တွက်ချက်ပြီးပါပြီ။'
        );

        return back();
    }

    //list

    public function list(Request $request)
    {
        $request->validate([
            'crop_id'   => 'nullable|exists:crops,id',
            'from_date' => 'nullable|date',
            'to_date'   => 'nullable|date|after_or_equal:from_date',
        ]);

        $today = now()->toDateString();

        // ယနေ့ Stock တွက်ချက်မှုအခြေအနေ
        $todayRun = DailyStockBalanceRun::where(
            'balance_date',
            $today
        )->first();

        // Daily Stock Balance Search
        $query = DB::table('daily_stock_balances')
            ->join(
                'crops',
                'daily_stock_balances.crop_id',
                '=',
                'crops.id'
            )
            ->whereNull('crops.deleted_at')
            ->select(
                'daily_stock_balances.*',
                'crops.crop_name',
                'crops.unit',
                'crops.quantity_per_basket'
            );

        // သီးနှံအမျိုးအစား
        if ($request->filled('crop_id')) {
            $query->where(
                'daily_stock_balances.crop_id',
                $request->crop_id
            );
        }

        // From Date
        if ($request->filled('from_date')) {
            $query->whereDate(
                'daily_stock_balances.balance_date',
                '>=',
                $request->from_date
            );
        }

        // To Date
        if ($request->filled('to_date')) {
            $query->whereDate(
                'daily_stock_balances.balance_date',
                '<=',
                $request->to_date
            );
        }

        // ရက်စွဲမရွေးထားရင် ယနေ့စာရင်းကို ပြမယ်
        if (
            ! $request->filled('from_date') &&
            ! $request->filled('to_date')
        ) {
            $query->where(
                'daily_stock_balances.balance_date',
                $today
            );
        }

        $balances = $query
            ->orderByDesc('daily_stock_balances.balance_date')
            ->orderBy('daily_stock_balances.crop_id')
            ->get();
        // dd($balances->toArray());

        $crops = Crop::get();

        $today = today()->toDateString();

        $lastRun = DailyStockBalanceRun::where(
            'balance_date',
            $today
        )->first();

        $canCalculateStock = ! $lastRun ||
        DB::table('stock_movements')
            ->whereDate('movement_date', $today)
            ->whereNull('deleted_at')
            ->whereIn('reference_type', ['Purchase', 'Sale'])
            ->where(function ($query) use ($lastRun) {
                $query->where('created_at', '>', $lastRun->calculated_at)
                    ->orWhere('updated_at', '>', $lastRun->calculated_at);
            })
            ->exists();

        return view('Leave.list', compact(
            'today',
            'todayRun',
            'balances',
            'crops',
            'canCalculateStock'
        ));
    }

}
