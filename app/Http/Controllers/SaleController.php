<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Customer;
use App\Models\DailyAveragePrice;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\StockBalance;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class SaleController extends Controller
{
    //directPage
    public function directPage()
    {

        $crops = Crop::orderBy('crop_name')->get();

        $today = today()->toDateString();

        $isCalculated = DB::table('daily_average_price_runs')
            ->where('price_date', $today)
            ->exists();

        $averagePrices = DB::table('daily_average_prices')
            ->join('crops', 'daily_average_prices.crop_id', '=', 'crops.id')
            ->where('daily_average_prices.price_date', $today)
            ->select(
                'crops.crop_name',
                'crops.unit',
                'crops.quantity_per_basket',
                'daily_average_prices.total_gross_amount',
                'daily_average_prices.quantity_on_hand',
                'daily_average_prices.average_cost',
                'daily_average_prices.average_basket_price'
            )
            ->orderBy('crops.crop_name')
            ->get();

        return view('sales.create',
            compact('crops',
                'isCalculated',
                'averagePrices',
                'today'
            ));
    }

    //avgPrice
    public function avgPrice($crop_id)
    {

        $crop = Crop::findOrFail($crop_id);

        $today = today()->toDateString();

        $averagePrices = DailyAveragePrice::where('crop_id', $crop_id)->where('price_date', $today)->value('average_basket_price');

        return response()->json([
            'success'         => true,
            'crop_id'         => $crop->id,
            'unit'            => $crop->unit,
            'reference_price' => round($averagePrices, 2),
        ]);

    }

    public function calculateTodayAveragePrices()
    {
        $today = today()->toDateString();

        try {
            DB::transaction(function () use ($today) {

                // ဒီနေ့တွက်ပြီးသားဆိုရင် ထပ်မတွက်ရ
                $alreadyCalculated = DB::table('daily_average_price_runs')
                    ->where('price_date', $today)
                    ->lockForUpdate()
                    ->exists();

                if ($alreadyCalculated) {
                    throw new \RuntimeException(
                        'ဒီနေ့အတွက် Average Price တွက်ပြီးသားဖြစ်ပါတယ်။'
                    );
                }

                // ဒီနေ့ဝယ်ထားတဲ့ သီးနှံများ၏ gross_amount စုစုပေါင်း
                $todayPurchases = DB::table('purchase_items')
                    ->join(
                        'purchases',
                        'purchase_items.purchase_id',
                        '=',
                        'purchases.id'
                    )
                    ->whereDate('purchases.purchase_date', $today)
                    ->whereNull('purchase_items.deleted_at')
                    ->whereNull('purchases.deleted_at')
                    ->select(
                        'purchase_items.crop_id',
                        DB::raw('SUM(purchase_items.gross_amount) as total_gross_amount')
                    )
                    ->groupBy('purchase_items.crop_id')
                    ->get();

                if ($todayPurchases->isEmpty()) {
                    throw new \RuntimeException(
                        'ဒီနေ့အတွက် ဝယ်ယူထားသော သီးနှံစာရင်း မရှိသေးပါ။'
                    );
                }

                foreach ($todayPurchases as $item) {
                    $crop = Crop::find($item->crop_id);

                    if (! $crop) {
                        throw new \RuntimeException(
                            "Crop ID {$item->crop_id} ကို ရှာမတွေ့ပါ။"
                        );
                    }

                    $quantityPerBasket = $crop->quantity_per_basket ?? 0;

                    if ($quantityPerBasket <= 0) {
                        throw new \RuntimeException(
                            "{$crop->crop_name} အတွက် တစ်တင်းရှိ ပိဿာအရေအတွက် သတ်မှတ်ပေးပါ။"
                        );
                    }

                    $stock = StockBalance::where('crop_id', $crop->id)
                        ->lockForUpdate()
                        ->first();

                    if (! $stock || $stock->quantity_on_hand <= 0) {
                        throw new \RuntimeException(
                            "{$crop->crop_name} အတွက် Stock လက်ကျန် မရှိပါ။"
                        );
                    }

                    // သင်သတ်မှတ်ထားသော တွက်ချက်နည်း
                    $totalGrossAmount = (float) $item->total_gross_amount;
                    $quantityOnHand   = (float) $stock->quantity_on_hand;

                    // တစ်ပိဿာဈေး
                    $averageCost = $totalGrossAmount / $quantityOnHand;

                    // တစ်တင်းဈေး
                    $averageBasketPrice = $averageCost * $quantityPerBasket;

                    // လက်ရှိ Stock ထဲတွင် တစ်ပိဿာဈေး သိမ်းမည်
                    $stock->average_cost = floor($averageBasketPrice / 1000) * 1000;
                    $stock->save();

                    // ဒီနေ့တွက်ထားသောဈေးကို သီးခြားသိမ်းမည်
                    DB::table('daily_average_prices')->insert([
                        'price_date'           => $today,
                        'crop_id'              => $crop->id,
                        'total_gross_amount'   => $totalGrossAmount,
                        'quantity_on_hand'     => $quantityOnHand,
                        'average_cost'         => floor($averageCost / 1000) * 1000,
                        'average_basket_price' => floor($averageBasketPrice / 1000) * 1000,
                        'created_at'           => now(),
                        'updated_at'           => now(),
                    ]);
                }

                // အားလုံးအောင်မြင်ပြီးမှ တွက်ပြီးကြောင်း မှတ်တမ်းတင်မည်
                DB::table('daily_average_price_runs')->insert([
                    'price_date'    => $today,
                    'calculated_at' => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            });

            return redirect()
                ->back()
                ->with('success', 'ဒီနေ့အတွက် သီးနှံအားလုံး၏ Average Price တွက်ချက်ပြီးပါပြီ။');

        } catch (\RuntimeException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    //create
    public function create(Request $request)
    {
        // မြန်မာဂဏန်းနှင့် comma များကို ပြောင်းလဲရန်
        $convertDigits = function ($value) use (&$convertDigits) {
            if (is_array($value)) {
                foreach ($value as $key => $item) {
                    $value[$key] = $convertDigits($item);
                }

                return $value;
            }

            if (! is_string($value)) {
                return $value;
            }

            return str_replace(
                ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉', ','],
                ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ''],
                trim($value)
            );
        };

        $request->merge($convertDigits($request->all()));

        // Validation
        $validator = Validator::make($request->all(), [
            'customer_id'             => [
                'required',
                'integer',
                'exists:customers,id,deleted_at,NULL',
            ],

            'paid_amount'             => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'sale_items'              => [
                'required',
                'array',
                'min:1',
            ],

            'sale_items.*.crop_id'    => [
                'required',
                'integer',
                'distinct',
                'exists:crops,id,deleted_at,NULL',
            ],

            'sale_items.*.quantity'   => [
                'required',
                'numeric',
                'gt:0',
            ],

            'sale_items.*.bag_count'  => [
                'nullable',
                'integer',
                'min:0',
            ],

            'sale_items.*.avg_price'  => [
                'required',
                'numeric',
                'min:0',
            ],

            'sale_items.*.sale_price' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'sale_items.*.note'       => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            'customer_id.required'             => 'ဝယ်သူကို ရွေးချယ်ပေးပါ။',
            'customer_id.integer'              => 'ဝယ်သူအချက်အလက် မမှန်ကန်ပါ။',
            'customer_id.exists'               => 'ရွေးချယ်ထားသော ဝယ်သူကို ရှာမတွေ့ပါ။',

            'paid_amount.numeric'              => 'ပေးချေမည့်ငွေကို ဂဏန်းဖြင့် ဖြည့်ပါ။',
            'paid_amount.min'                  => 'ပေးချေမည့်ငွေသည် အနုတ်တန်ဖိုး မဖြစ်ရပါ။',

            'sale_items.required'              => 'ရောင်းချမည့် သီးနှံစာရင်း ထည့်ပေးပါ။',
            'sale_items.array'                 => 'သီးနှံစာရင်းပုံစံ မမှန်ကန်ပါ။',
            'sale_items.min'                   => 'ရောင်းချမည့် သီးနှံ အနည်းဆုံးတစ်မျိုး ထည့်ပေးပါ။',

            'sale_items.*.crop_id.required'    => 'သီးနှံကို ရွေးချယ်ပေးပါ။',
            'sale_items.*.crop_id.integer'     => 'သီးနှံအချက်အလက် မမှန်ကန်ပါ။',
            'sale_items.*.crop_id.distinct'    => 'သီးနှံတစ်မျိုးကို စာရင်းထဲတွင် တစ်ကြိမ်သာ ထည့်ပေးပါ။',
            'sale_items.*.crop_id.exists'      => 'ရွေးချယ်ထားသော သီးနှံကို ရှာမတွေ့ပါ။',

            'sale_items.*.quantity.required'   => 'သီးနှံအရေအတွက် ဖြည့်ပေးပါ။',
            'sale_items.*.quantity.numeric'    => 'သီးနှံအရေအတွက်ကို ဂဏန်းဖြင့် ဖြည့်ပါ။',
            'sale_items.*.quantity.gt'         => 'သီးနှံအရေအတွက်သည် သုညထက် ကြီးရပါမည်။',

            'sale_items.*.bag_count.integer'   => 'အိတ်အရေအတွက်ကို ကိန်းပြည့်ဖြင့် ဖြည့်ပါ။',
            'sale_items.*.bag_count.min'       => 'အိတ်အရေအတွက်သည် အနုတ်တန်ဖိုး မဖြစ်ရပါ။',

            'sale_items.*.avg_price.required'  => 'ပျမ်းမျှဈေးကို ဖြည့်ပေးပါ။',
            'sale_items.*.avg_price.numeric'   => 'ပျမ်းမျှဈေးကို ဂဏန်းဖြင့် ဖြည့်ပါ။',
            'sale_items.*.avg_price.min'       => 'ပျမ်းမျှဈေးသည် အနုတ်တန်ဖိုး မဖြစ်ရပါ။',

            'sale_items.*.sale_price.required' => 'ရောင်းဈေးကို ဖြည့်ပေးပါ။',
            'sale_items.*.sale_price.numeric'  => 'ရောင်းဈေးကို ဂဏန်းဖြင့် ဖြည့်ပါ။',
            'sale_items.*.sale_price.gt'       => 'ရောင်းဈေးသည် သုညထက် ကြီးရပါမည်။',

            'sale_items.*.note.string'         => 'မှတ်ချက်ပုံစံ မမှန်ကန်ပါ။',
            'sale_items.*.note.max'            => 'မှတ်ချက်သည် စာလုံး ၂၅၅ ထက် မပိုရပါ။',
        ]);

        // Crop တစ်ခုချင်းအတွက် သတ်မှတ်ချက်များ စစ်ရန်
        $validator->after(function ($validator) use ($request) {
            foreach ($request->input('sale_items', []) as $index => $item) {
                $crop = Crop::query()->find($item['crop_id'] ?? null);

                if (! $crop) {
                    continue;
                }

                if (
                    ! is_numeric($crop->quantity_per_basket) ||
                    $crop->quantity_per_basket <= 0
                ) {
                    $validator->errors()->add(
                        "sale_items.$index.quantity",
                        'ဤသီးနှံအတွက် တင်းတစ်တင်း၏ အရေအတွက်ကို မှန်ကန်စွာ သတ်မှတ်ထားခြင်း မရှိပါ။'
                    );
                }

                if (
                    strtolower((string) $crop->unit) === 'pound' &&
                    (int) ($item['bag_count'] ?? 0) < 1
                ) {
                    $validator->errors()->add(
                        "sale_items.$index.bag_count",
                        'မြေပဲအတွက် အိတ်အရေအတွက်ကို ဖြည့်ပေးပါ။'
                    );
                }
            }
        });

        $validated = $validator->validate();

        $sale = DB::transaction(function () use ($validated) {
            $paidAmount = (float) ($validated['paid_amount'] ?? 0);

            $items       = [];
            $totalAmount = 0;

            // ရောင်းဖိုးကို Server ဘက်တွင် ပြန်တွက်ရန်
            foreach ($validated['sale_items'] as $index => $item) {
                $crop = Crop::query()->findOrFail($item['crop_id']);

                $quantity          = (float) $item['quantity'];
                $bagCount          = (int) ($item['bag_count'] ?? 0);
                $salePrice         = (float) $item['sale_price'];
                $avgPrice          = (float) $item['avg_price'];
                $quantityPerBasket = (float) $crop->quantity_per_basket;

                /*
             * Form ရဲ့ လက်ရှိတွက်ချက်ပုံအတိုင်း
             * pound သီးနှံတွင် quantity - bag_count သုံးထားသည်။
             */
                $calculationQuantity = $quantity;

                if (strtolower((string) $crop->unit) === 'pound') {
                    $calculationQuantity = $quantity - $bagCount;
                }

                if ($calculationQuantity <= 0) {
                    throw ValidationException::withMessages([
                        "sale_items.$index.quantity" =>
                        'အိတ်အရေအတွက် နုတ်ပြီးနောက် သီးနှံအရေအတွက်သည် သုညထက် ကြီးရပါမည်။',
                    ]);
                }

                $rawAmount = (
                    $calculationQuantity / $quantityPerBasket
                ) * $salePrice;

                $grossAmount = floor($rawAmount / 1000) * 1000;

                $remainderAmount = round(
                    $rawAmount - $grossAmount,
                    2
                );

                $totalAmount += $grossAmount;

                $items[] = [
                    'crop'             => $crop,
                    'crop_id'          => $crop->id,
                    'quantity'         => $quantity,
                    'bag_count'        => $bagCount,
                    'avg_price'        => $avgPrice,
                    'sale_price'       => $salePrice,
                    'gross_amount'     => $grossAmount,
                    'remainder_amount' => $remainderAmount,
                    'note'             => $item['note'] ?? null,
                ];
            }

            $totalAmount = round($totalAmount, 2);

            if ($paidAmount > $totalAmount) {
                Alert::error(
                    'ငွေပေးချေမှု မအောင်မြင်ပါ',
                    'ပေးချေမည့်ငွေသည် စုစုပေါင်းပေးချေရမည့်ငွေထက် မများရပါ။'
                );

                return back()->withInput();
            }

            $balanceAmount = round($totalAmount - $paidAmount, 2);

            // Sales သိမ်းရန်
            $sale = Sale::create([
                'invoice_no'      => $this->generateInvoiceNo(),
                'customer_id'     => $validated['customer_id'],
                'sale_date'       => now(),
                'total_amount'    => $totalAmount,
                'total_deduction' => 0,
                'net_amount'      => $totalAmount,
                'total_paid'      => $paidAmount,
                'balance_amount'  => $balanceAmount,
                'status'          => $paidAmount == $totalAmount ? 'Complete' : 'Confirm',
            ]);

            foreach ($items as $item) {
                // Lock ဖြင့် Stock လက်ကျန်ကို ဖတ်ရန်
                $stock = StockBalance::query()
                    ->where('crop_id', $item['crop_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $stock) {
                    throw ValidationException::withMessages([
                        'sale_items' =>
                        $item['crop']->crop_name .
                        ' အတွက် Stock လက်ကျန်စာရင်း မရှိပါ။',
                    ]);
                }

                if ($stock->quantity_on_hand < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'sale_items' =>
                        $item['crop']->crop_name .
                        ' လက်ကျန်မလုံလောက်ပါ။ လက်ကျန် ' .
                        $stock->quantity_on_hand .
                        '၊ ရောင်းချမည့်အရေအတွက် ' .
                        $item['quantity'] . ' ဖြစ်ပါသည်။',
                    ]);
                }

                // Sale Item သိမ်းရန်
                $saleItem = SaleItem::create([
                    'sale_id'          => $sale->id,
                    'crop_id'          => $item['crop_id'],
                    'quantity'         => $item['quantity'],
                    'bag_count'        => $item['bag_count'],
                    'avg_price'        => $item['avg_price'],
                    'sale_price'       => $item['sale_price'],
                    'gross_amount'     => $item['gross_amount'],
                    'remainder_amount' => $item['remainder_amount'],
                    'note'             => $item['note'],
                ]);

                // Stock Movement မှတ်တမ်း
                StockMovement::create([
                    'crop_id'        => $item['crop_id'],
                    'movement_date'  => now(),
                    'quantity_in'    => 0,
                    'quantity_out'   => $item['quantity'],
                    'unit'           => $item['crop']->unit ?? '',
                    'unit_cost'      => $item['sale_price'],
                    'reference_type' => 'Sale',
                    'reference_id'   => $saleItem->id,
                ]);

                // Stock လက်ကျန်မှ ရောင်းလိုက်သောအရေအတွက် နုတ်ရန်
                $stock->quantity_on_hand = round(
                    $stock->quantity_on_hand - $item['quantity'],
                    2
                );

                $stock->save();
            }

            // စတင်ပေးချေငွေရှိမှသာ Initial Payment သိမ်းရန်
            if ($paidAmount > 0) {
                SalePayment::create([
                    'sale_id'      => $sale->id,
                    'payment_date' => now(),
                    'amount'       => $paidAmount,
                    'payment_type' => 'initial',
                    'note'         => null,
                ]);
            }

            return $sale;
        });

        return redirect()
            ->route('Sale#voucher', $sale->id)
            ->with('success', 'အရောင်းစာရင်း သိမ်းဆည်းပြီးပါပြီ။');

    }

    private function generateInvoiceNo(): string
    {
        do {
            $invoiceNo = 'PUR-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));
            $exists    = Purchase::query()->where('invoice_no', $invoiceNo)->exists();
        } while ($exists);

        return $invoiceNo;
    }

    //voucher
    public function voucher($sale_id)
    {

        $sale     = Sale::findOrFail($sale_id);
        $customer = Customer::findOrFail($sale->customer_id);

        $items = DB::table('sale_items')
            ->join('crops', 'sale_items.crop_id', '=', 'crops.id')
            ->where('sale_items.sale_id', $sale_id)
            ->select('sale_items.*', 'crops.quantity_per_basket', 'crops.crop_name', 'crops.unit')
            ->get();

        return view('sales.voucher', compact('sale', 'customer', 'items'));

    }

    //smallVoucher
    public function smallVoucher($sale_id)
    {

        $sale     = Sale::findOrFail($sale_id);
        $customer = Customer::findOrFail($sale->customer_id);

        $items = DB::table('sale_items')
            ->join('crops', 'sale_items.crop_id', '=', 'crops.id')
            ->where('sale_items.sale_id', $sale_id)
            ->select('sale_items.*', 'crops.quantity_per_basket', 'crops.crop_name', 'crops.unit')
            ->get();

        return view('sales.smallVoucher', compact('sale', 'customer', 'items'));

    }

    //list
    public function list(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $cropId = $request->input('crop_id');

        // Quick Date Filter (today, this_week, this_month) သို့မဟုတ် Custom From/To Date
        $quickFilter = $request->input('quick_filter');
        $fromDate    = $request->input('from_date');
        $toDate      = $request->input('to_date');
        if ($quickFilter === 'today') {
            $fromDate = now()->toDateString();
            $toDate   = now()->toDateString();
        } elseif ($quickFilter === 'this_week') {
            $fromDate = now()->subDays(6)->toDateString();
            $toDate   = now()->toDateString();
        } elseif ($quickFilter === 'this_month') {
            $fromDate = now()->startOfMonth()->toDateString();
            $toDate   = now()->endOfMonth()->toDateString();
        } else {
            // Default အနေဖြင့် မပါလာပါက ယနေ့ (သို့မဟုတ် လိုသလိုသတ်မှတ်နိုင်သည်)
            $fromDate = $fromDate ?: now()->toDateString();
            $toDate   = $toDate ?: now()->toDateString();
        }

        // 1. Purchases Query Builder
        $query = DB::table('sales')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->select(
                'sales.*',
                'customers.name as customer_name',
                'customers.phone as customer_phone',
                'customers.address as customer_address'
            )
            ->orderBy('sales.sale_date', 'desc')
            ->orderBy('sales.id', 'desc');

        if ($cropId) {
            $query->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
                ->where('sale_items.crop_id', $cropId)
                ->distinct();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('sales.invoice_no', 'like', "%{$search}%")
                    ->orWhere('customers.name', 'like', "%{$search}%")
                    ->orWhere('customers.phone', 'like', "%{$search}%")
                    ->orWhere('customers.address', 'like', "%{$search}%");
            });
        }

        if ($status === 'pending') {
            $query->where('sales.balance_amount', '>', 0);
        } elseif ($status === 'complete') {
            $query->where('sales.balance_amount', '<=', 0);
        }

        if ($fromDate && $toDate) {
            $query->whereBetween('sales.sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        $sales = $query->paginate(15)->withQueryString();

        // 2. Purchase Items များကို တွဲယူခြင်း
        foreach ($sales as $sale) {
            $sale->items = DB::table('sale_items')
                ->join('crops', 'sale_items.crop_id', '=', 'crops.id')
                ->select(
                    'sale_items.*',
                    'crops.crop_name',
                    'crops.unit',
                )
                ->where('sale_items.sale_id', $sale->id)
                ->get();
        }

        $crops = DB::table('crops')->select('id', 'crop_name')->get();

        // 3. ရွေးချယ်ထားသော ရက်စွဲ Range အလိုက် Summary (KPI) တွက်ချက်ခြင်း
        $summaryQuery = DB::table('sales');
        if ($fromDate && $toDate) {
            $summaryQuery->whereBetween('sale_date', [$fromDate . ' 00:00:00', $toDate . ' 23:59:59']);
        }

        // စုစုပေါင်း ပေးရန်ကျန်ငွေ (Pending Balance) - Note: Pending ကတော့ လက်ရှိ အချိန်အထိ ကျန်နေသမျှကို ပြသရန် ပိုသင့်တော်သည်
        $totalPendingBalance = DB::table('sales')->where('balance_amount', '>', 0)->sum('balance_amount');

        $filteredGrossTotal   = (clone $summaryQuery)->sum('total_amount');
        $filteredVoucherCount = (clone $summaryQuery)->count();

        $summary = (object) [
            'total_pending_balance'  => $totalPendingBalance,
            'filtered_gross_total'   => $filteredGrossTotal,
            'filtered_voucher_count' => $filteredVoucherCount,
        ];

        return view('sales.index', compact('sales', 'crops', 'summary', 'fromDate', 'toDate', 'quickFilter'));
    }

    //payBalance
    public function payBalance(Request $request, $saleId)
    {
        // dd($request->toArray());
        $request->validate([
            'pay_amount' => ['required', 'numeric', 'min:0.01'],
        ], [
            'pay_amount.required' => 'ပေးချေမည့်ငွေ ပမာဏကို ထည့်သွင်းပါ။',
            'pay_amount.numeric'  => 'ပေးချေမည့်ငွေသည် ဂဏန်းဖြစ်ရပါမည်။',
            'pay_amount.min'      => 'ပေးချေမည့်ငွေသည် သုညထက် ကြီးရပါမည်။',
        ]);

        $payAmount = (float) $request->input('pay_amount');

        DB::transaction(function () use ($saleId, $payAmount) {
            $sale = DB::table('sales')->where('id', $saleId)->first();

            if ($sale && $sale->balance_amount > 0) {
                // ကျန်ငွေထက် ပိုပေးမိပါက ကျန်ငွေ ပမာဏအတိုင်းသာ ယူမည်
                $actualPaid = min($payAmount, (float) $sale->balance_amount);

                $newTotalPaid = (float) $sale->total_paid + $actualPaid;
                $newBalance   = (float) $sale->balance_amount - $actualPaid;
                $newStatus    = $newBalance <= 0 ? 'Complete' : 'Confirm';

                //1. sales ဇယားကို Update လုပ်ခြင်း
                DB::table('sales')
                    ->where('id', $saleId)
                    ->update([
                        'total_paid'     => $newTotalPaid,
                        'balance_amount' => $newBalance,
                        'status'         => $newStatus,
                        'updated_at'     => now(),
                    ]);

                // 2. purchase_payments ဇယားတွင် ငွေပေးချေမှု မှတ်တမ်းအသစ် ထည့်သွင်းခြင်း
                DB::table('sale_payments')->insert([
                    'sale_id'      => $saleId,
                    'payment_date' => now(),
                    'amount'       => $actualPaid,
                    'payment_type' => 'additional', // ဘောင်ချာထုတ်ပြီးမှ ထပ်ပေးသောကြောင့် additional ဟု မှတ်မည်
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        });

        return redirect()
            ->back()
            ->with('success', 'ကျန်ငွေ ပေးချေမှု အောင်မြင်ပါသည်။');
    }

}
