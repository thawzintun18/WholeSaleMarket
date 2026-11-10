<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\DailyStockBalance;
use App\Models\DailyStockBalanceRun;
use App\Models\StockBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class LeaveCountController extends Controller
{
    //create
    public function create(Request $request)
    {
        $today = now()->toDateString();

        DB::transaction(function () use ($today) {

            // 1. ဒီနေ့ Purchase / Sale အဝင်အထွက် စုမယ်
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

            // 2. ဒီနေ့ Purchase Bag Count စုမယ်
            $todayPurchaseBags = DB::table('purchase_items')
                ->join(
                    'purchases',
                    'purchase_items.purchase_id',
                    '=',
                    'purchases.id'
                )
                ->join(
                    'crops',
                    'purchase_items.crop_id',
                    '=',
                    'crops.id'
                )
                ->select(
                    'purchase_items.crop_id',
                    DB::raw('SUM(COALESCE(purchase_items.bag_count, 0)) AS total_bag_count')
                )
                ->whereDate('purchases.purchase_date', $today)
                ->whereIn(DB::raw('LOWER(crops.unit)'), ['pound'])
                ->whereNull('purchases.deleted_at')
                ->whereNull('purchase_items.deleted_at')
                ->groupBy('purchase_items.crop_id')
                ->get()
                ->keyBy('crop_id');

            // 3. အရင်နေ့မှ ဒီနေ့အထိ Purchase Bag Count စုမယ်
            $totalPurchaseBags = DB::table('purchase_items')
                ->join(
                    'purchases',
                    'purchase_items.purchase_id',
                    '=',
                    'purchases.id'
                )
                ->join(
                    'crops',
                    'purchase_items.crop_id',
                    '=',
                    'crops.id'
                )
                ->select(
                    'purchase_items.crop_id',
                    DB::raw('SUM(COALESCE(purchase_items.bag_count, 0)) AS total_bag_count')
                )
                ->whereDate('purchases.purchase_date', '<=', $today)
                ->whereIn(DB::raw('LOWER(crops.unit)'), ['pound'])
                ->whereNull('purchases.deleted_at')
                ->whereNull('purchase_items.deleted_at')
                ->groupBy('purchase_items.crop_id')
                ->get()
                ->keyBy('crop_id');

            // 4. ဒီနေ့ Sale Bag Count စုမယ်
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

            // 5. လက်ရှိ Stock Balance
            $stockBalances = DB::table('stock_balances')
                ->get()
                ->keyBy('crop_id');

            // 6. သီးနှံအားလုံး
            $crops = Crop::all();

            foreach ($crops as $crop) {

                $movement = $movements->get($crop->id);

                $totalIn  = (float) ($movement->total_in ?? 0);
                $totalOut = (float) ($movement->total_out ?? 0);

                // Pound ဖြစ်မဖြစ် စစ်မယ်
                $unit = strtolower(trim($crop->unit ?? ''));

                // ဒီနေ့ Purchase Bag Count
                $purchaseBagCount = (float) (
                    $todayPurchaseBags->get($crop->id)->total_bag_count ?? 0
                );

                // ဒီနေ့ Sale Bag Count
                $saleBagCount = (float) (
                    $saleBags->get($crop->id)->total_bag_count ?? 0
                );

                // Pound ဖြစ်မှ Bag Count နုတ်မယ်
                if (in_array($unit, ['pound', 'ပေါင်'])) {

                    // ဒီနေ့ အဝယ် Quantity ထဲမှ Bag Count နုတ်မယ်
                    $totalIn -= $purchaseBagCount;

                    // ဒီနေ့ အရောင်း Quantity ထဲမှ Bag Count နုတ်မယ်
                    $totalOut -= $saleBagCount;
                }

                // 7. ဒီနေ့ အသားတင်လက်ကျန်
                $dailyBalance  = $totalIn - $totalOut;

                // 8. လက်ရှိ Stock Balance
                $stock = $stockBalances->get($crop->id);

                $totalBalance = (float) ($stock->quantity_on_hand ?? 0);

                $averageCost = (float) ($stock->average_cost ?? 0);

                // 10. Daily Stock Balance သိမ်းမယ်
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

            // 11. ဒီနေ့တွက်ချက်မှုမှတ်တမ်း သိမ်းမယ်
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

        $hasStockBalance    = DailyStockBalance::exists();
        $hasStockBalanceRun = DailyStockBalanceRun::exists();

        return view('Leave.list', compact(
            'today',
            'todayRun',
            'balances',
            'crops',
            'canCalculateStock',
            'hasStockBalance',
            'hasStockBalanceRun'
        ));
    }

    //directPage
    public function directPage()
    {

        $crops = Crop::select('id', 'crop_name')
            ->orderBy('crop_name')
            ->get();

        return view('Leave.create', compact('crops'));
    }

    //existCreate
    public function existCreate(Request $request)
    {

        $validated = $request->validate([

            'balance_date'             => 'required|date',

            'balances'                 => 'required|array|min:1',

            'balances.*.crop_id'       => [
                'required',
                'distinct',
                'exists:crops,id',
            ],

            'balances.*.daily_balance' => 'required|numeric|min:0',

            'balances.*.total_balance' => 'required|numeric|min:0',

            'balances.*.average_cost'  => 'required|numeric|min:0',

        ], [

            'balance_date.required'             => 'စာရင်းသွင်းမည့်နေ့ကို ရွေးချယ်ပေးပါ။',
            'balance_date.date'                 => 'ရက်စွဲမှန်ကန်မှုမရှိပါ။',

            'balances.required'                 => 'အနည်းဆုံး သီးနှံတစ်မျိုး ထည့်သွင်းပေးပါ။',
            'balances.array'                    => 'သီးနှံစာရင်းပုံစံ မှန်ကန်မှုမရှိပါ။',
            'balances.min'                      => 'အနည်းဆုံး သီးနှံတစ်မျိုး ထည့်သွင်းပေးပါ။',

            'balances.*.crop_id.required'       => 'သီးနှံအမည်ကို ရွေးချယ်ပေးပါ။',
            'balances.*.crop_id.distinct'       => 'သီးနှံတစ်မျိုးကို တစ်ကြိမ်သာ ထည့်သွင်းခွင့်ရှိပါသည်။',
            'balances.*.crop_id.exists'         => 'ရွေးချယ်ထားသော သီးနှံမရှိတော့ပါ။',

            'balances.*.daily_balance.required' => 'နေ့စဉ်လက်ကျန်ကို ထည့်သွင်းပေးပါ။',
            'balances.*.daily_balance.numeric'  => 'နေ့စဉ်လက်ကျန်ကို ကိန်းဂဏန်းဖြင့် ထည့်သွင်းပေးပါ။',
            'balances.*.daily_balance.min'      => 'နေ့စဉ်လက်ကျန်သည် အနုတ်တန်ဖိုး မဖြစ်ရပါ။',

            'balances.*.total_balance.required' => 'စုစုပေါင်းလက်ကျန်ကို ထည့်သွင်းပေးပါ။',
            'balances.*.total_balance.numeric'  => 'စုစုပေါင်းလက်ကျန်ကို ကိန်းဂဏန်းဖြင့် ထည့်သွင်းပေးပါ။',
            'balances.*.total_balance.min'      => 'စုစုပေါင်းလက်ကျန်သည် အနုတ်တန်ဖိုး မဖြစ်ရပါ။',

            'balances.*.average_cost.required'  => 'ပျမ်းမျှတန်ဖိုးကို ထည့်သွင်းပေးပါ။',
            'balances.*.average_cost.numeric'   => 'ပျမ်းမျှတန်ဖိုးကို ကိန်းဂဏန်းဖြင့် ထည့်သွင်းပေးပါ။',
            'balances.*.average_cost.min'       => 'ပျမ်းမျှတန်ဖိုးသည် အနုတ်တန်ဖိုး မဖြစ်ရပါ။',

        ]);

        try {

            DB::transaction(function () use ($validated) {

                $balanceDate = $validated['balance_date'];

                // အဲဒီနေ့အတွက် စာရင်းရှိပြီးသားလား စစ်မယ်
                $exists = DailyStockBalanceRun::where(
                    'balance_date',
                    $balanceDate
                )->lockForUpdate()->exists();

                if ($exists) {
                    throw ValidationException::withMessages([
                        'balance_date' => 'ဤနေ့အတွက် လက်ကျန်စာရင်း ထည့်သွင်းပြီးဖြစ်ပါသည်။',
                    ]);
                }

                // Daily Run Create
                DailyStockBalanceRun::create([
                    'balance_date'  => $balanceDate,
                    'calculated_at' => now(),
                ]);

                foreach ($validated['balances'] as $item) {

                    // Daily Stock Balance Create
                    DailyStockBalance::create([
                        'crop_id'       => $item['crop_id'],
                        'balance_date'  => $balanceDate,
                        'daily_balance' => $item['daily_balance'],
                        'total_balance' => $item['total_balance'],
                        'average_cost'  => $item['average_cost'],
                    ]);

                    // Current Stock Balance Update or Create
                    StockBalance::updateOrCreate(
                        [
                            'crop_id' => $item['crop_id'],
                        ],
                        [
                            'quantity_on_hand' => $item['total_balance'],
                            'average_cost'     => $item['average_cost'],
                        ]
                    );
                }
            });

            return redirect()->back()->with(
                'success',
                'သီးနှံလက်ကျန်စာရင်း အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။'
            );

        } catch (ValidationException $e) {

            throw $e;

        } catch (\Exception $e) {

            return back()->withInput()->with(
                'error',
                'သီးနှံလက်ကျန်စာရင်း ထည့်သွင်းရာတွင် အမှားဖြစ်နေပါသည်။'
            );
        }

    }

    //checkCreate
    public function checkCreate(Request $request)
    {
        $request->validate([
            'balance_date' => 'required|date',
        ], [
            'balance_date.required' => 'စာရင်းသွင်းမည့်နေ့ကို ရွေးချယ်ပေးပါ။',
            'balance_date.date'     => 'ရက်စွဲမှန်ကန်မှုမရှိပါ။',
        ]);

        $exists = DailyStockBalanceRun::where(
            'balance_date',
            $request->balance_date
        )->exists();

        return response()->json([
            'exists'  => $exists,
            'message' => $exists
                ? 'ဤနေ့အတွက် လက်ကျန်စာရင်း ထည့်သွင်းပြီးဖြစ်ပါသည်။'
                : 'စာရင်းသွင်းနိုင်ပါသည်။',
        ]);

    }

}
