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

        // ဒီနေ့တွက်ပြီးသားဖြစ်ပါက ထပ်မတွက်ရန်
        if (
            DailyStockBalanceRun::where('balance_date', $today)->exists()
        ) {
            return redirect()
                ->route('Leave#list')
                ->with('error', 'ဒီနေ့အတွက် လက်ကျန်တွက်ချက်ပြီးပါပြီ။');
        }

        DB::transaction(function () use ($today) {

            // ဒီနေ့ Purchase / Sale အဝင်အထွက်ကို စုမယ်
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

            // လက်ရှိ Stock Balance အားလုံးကို ယူမယ်
            $stockBalances = DB::table('stock_balances')
                ->get()
                ->keyBy('crop_id');

            // သီးနှံအားလုံးကို ယူမယ်
            $crops = Crop::all();

            // တွက်ချက်မှုမှတ်တမ်း သိမ်းမယ်
            DailyStockBalanceRun::create([
                'balance_date'  => $today,
                'calculated_at' => now(),
            ]);

            foreach ($crops as $crop) {

                // ဒီနေ့ အဝင်အထွက်
                $movement = $movements->get($crop->id);

                $totalIn  = $movement->total_in ?? 0;
                $totalOut = $movement->total_out ?? 0;

                // ဒီနေ့ အသားတင်လက်ကျန်
                $dailyBalance = $totalIn - $totalOut;

                // Stock Balance မှ လက်ကျန်နှင့် ပျမ်းမျှဈေး ယူမယ်
                $stock = $stockBalances->get($crop->id);

                $totalBalance = $stock->quantity_on_hand ?? 0;
                $averageCost  = $stock->average_cost ?? 0;

                // နေ့စဉ်လက်ကျန်စာရင်း သိမ်းမယ်
                DailyStockBalance::create([
                    'crop_id'       => $crop->id,
                    'balance_date'  => $today,
                    'daily_balance' => $dailyBalance,
                    'total_balance' => $totalBalance,
                    'average_cost'  => $averageCost,
                ]);
            }
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


        return view('Leave.list', compact(
            'today',
            'todayRun',
            'balances',
            'crops'
        ));
    }

}
