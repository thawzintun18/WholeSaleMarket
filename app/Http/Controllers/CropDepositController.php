<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CropDepositController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('crop_deposits')
            ->join('farmers', 'crop_deposits.farmer_id', '=', 'farmers.id')
            ->select(
                'crop_deposits.*',
                'farmers.name as farmer_name',
                'farmers.phone as farmer_phone',
                'farmers.village as farmer_village'
            );

        // 1. Default (သို့) ရက်စွဲအလိုက် Filter လုပ်ခြင်း
        // အကယ်၍ user က date ရွေးမထားဘူးဆိုရင် Default အနေနဲ့ ဒီတစ်ပတ်စာ (လွန်ခဲ့သော ၇ ရက်မှ ယနေ့အထိ) ကို ပြပါမည်
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('crop_deposits.deposit_date', '>=', $request->input('start_date'))
                ->whereDate('crop_deposits.deposit_date', '<=', $request->input('end_date'));
        } elseif ($request->filled('date_filter')) {
            $filter = $request->input('date_filter');
            if ($filter === 'today') {
                $query->whereDate('crop_deposits.deposit_date', now()->toDateString());
            } elseif ($filter === 'this_week') {
                $query->whereBetween('crop_deposits.deposit_date', [
                    now()->subDays(6)->startOfDay(),
                    now()->endOfDay()
                ]);
            } elseif ($filter === 'this_month') {
                $query->whereMonth('crop_deposits.deposit_date', now()->month)
                    ->whereYear('crop_deposits.deposit_date', now()->year);
            }
        } else {
            // Default: ဒီတစ်ပတ်စာ (လွန်ခဲ့သော ၇ ရက်)
            $query->whereDate('crop_deposits.deposit_date', '>=', now()->subDays(30)->toDateString());
        }

        // 2. Search (ဘောင်ချာ၊ တောင်သူအမည်၊ ဖုန်း၊ ကားနံပါတ်)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('crop_deposits.deposit_no', 'like', "%{$search}%")
                ->orWhere('farmers.name', 'like', "%{$search}%")
                ->orWhere('farmers.phone', 'like', "%{$search}%")
                ->orWhere('crop_deposits.car_no', 'like', "%{$search}%");
            });
        }

        // 3. Status Filter
        if ($request->filled('status')) {
            $query->where('crop_deposits.status', $request->input('status'));
        }

        // 4. သီးနှံအလိုက် Filter (crop_deposit_items ထဲတွင် ရှိမရှိစစ်ရန်)
        if ($request->filled('crop_id')) {
            $cropId = $request->input('crop_id');
            $query->whereExists(function ($sub) use ($cropId) {
                $sub->select(DB::raw(1))
                    ->from('crop_deposit_items')
                    ->whereColumn('crop_deposit_items.crop_deposit_id', 'crop_deposits.id')
                    ->where('crop_deposit_items.crop_id', $cropId);
            });
        }

        $deposits = $query->orderBy('crop_deposits.id', 'desc')->paginate(10);

        // 5. Deposit တစ်ခုချင်းစီအတွက် သီးနှံအသေးစိတ်စာရင်းများကို ထည့်သွင်းပေးခြင်း (View တွင် အလွယ်တကူပြရန်)
        foreach ($deposits as $deposit) {
            $deposit->items = DB::table('crop_deposit_items')
                ->join('crops', 'crop_deposit_items.crop_id', '=', 'crops.id')
                ->join('grades', 'crop_deposit_items.grade_id', '=', 'grades.id')
                ->select('crop_deposit_items.*', 'crops.crop_name', 'crops.unit', 'grades.grade_name')
                ->where('crop_deposit_items.crop_deposit_id', $deposit->id)
                ->get();
        }

        // Filter အတွက် လိုအပ်သော သီးနှံစာရင်းများကို ပို့ပေးရန်
        $crops = DB::table('crops')->orderBy('crop_name')->get();

        return view('cropDeposits.index', compact('deposits', 'crops'));
    }

    public function storeDeposit(Request $request)
    {
        // dd($request->toArray());
        $data = $request->validate([
            'farmer_id'                        => ['required', 'integer', 'exists:farmers,id'],
            'car_no'                           => ['nullable', 'string', 'max:255'],
            'car_fee_amount'                   => ['nullable', 'numeric', 'min:0'],
            'deposit_items'                    => ['required', 'array', 'min:1'],
            'deposit_items.*.crop_id'          => ['required', 'integer', 'exists:crops,id'],
            'deposit_items.*.grade_id'         => ['required', 'integer', 'exists:grades,id'],
            'deposit_items.*.quantity'         => ['required', 'numeric', 'gt:0'],
            'deposit_items.*.bag_count'        => ['nullable', 'integer', 'min:0'],
            'deposit_items.*.reference_price'  => ['nullable', 'numeric', 'min:0'],
            'deposit_items.*.actual_buy_price' => ['required', 'numeric', 'min:0'],
            'deposit_items.*.note'             => ['nullable', 'string', 'max:255'],
        ], [
            'farmer_id.required'               => 'တောင်သူကို ရွေးချယ်ပါ။',
            'deposit_items.required'           => 'သီးနှံစာရင်း အနည်းဆုံးတစ်မျိုး ထည့်ပါ။',
            'deposit_items.*.quantity.gt'      => 'သီးနှံအရေအတွက်သည် သုညထက် ကြီးရပါမည်။',
            'deposit_items.*.actual_buy_price.required' => 'ဝယ်ယူမည့်ဈေး ဖြည့်သွင်းရန် လိုအပ်ပါသည်။',
        ]);

        DB::transaction(function () use ($data) {
            // 2. Deposit No အလိုအလျောက် ထုတ်ယူခြင်း (ဥပမာ - DEP-20261024-0001)
            $dateStr = now()->format('Ymd');
            $lastDeposit = DB::table('crop_deposits')
                ->whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->first();

            $runningNo = $lastDeposit ? ((int) substr($lastDeposit->deposit_no, -4)) + 1 : 1;
            $depositNo = 'DEP-' . $dateStr . '-' . str_pad($runningNo, 4, '0', STR_PAD_LEFT);

            // 3. crop_deposits ပင်မဇယားတွင် ဒေတာထည့်သွင်းခြင်း
            $depositId = DB::table('crop_deposits')->insertGetId([
                'deposit_no'     => $depositNo,
                'farmer_id'      => $data['farmer_id'],
                'deposit_date'   => now(),
                'status'         => 'Stored', // အပ်နှံထားဆဲ အနေအထား
                'car_no'         => $data['car_no'] ?? null,
                'car_fee_amount' => (float) ($data['car_fee_amount'] ?? 0),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 4. crop_deposit_items အသေးစိတ်ဇယားတွင် တစ်မျိုးချင်းစီ ထည့်သွင်းခြင်း
            foreach ($data['deposit_items'] as $row) {
                $crop = DB::table('crops')->where('id', $row['crop_id'])->first();
                $unit = strtolower($crop->unit ?? '');

                // ယူနစ်က pound ဖြစ်မှသာ bag_count ကို သိမ်းမည်၊ viss ဆိုလျှင် 0 ဟု သိမ်းမည်
                $bagCount = ($unit === 'pound') ? (int) ($row['bag_count'] ?? 0) : 0;

                DB::table('crop_deposit_items')->insert([
                    'crop_deposit_id'  => $depositId,
                    'crop_id'          => $row['crop_id'],
                    'grade_id'         => $row['grade_id'],
                    'quantity'         => (float) $row['quantity'],
                    'bag_count'        => $bagCount,
                    'reference_price'  => (float) ($row['reference_price'] ?? 0),
                    'actual_buy_price' => (float) $row['actual_buy_price'],
                    'note'             => $row['note'] ?? null,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        });

        return redirect()->route('crop#deposits#index')->with('success', 'သီးနှံအပ်နှံမှု စာရင်း အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။');
    }

    public function showConvertForm($id)
    {
        $deposit = DB::table('crop_deposits')
            ->join('farmers', 'crop_deposits.farmer_id', '=', 'farmers.id')
            ->select('crop_deposits.*', 'farmers.name as farmer_name', 'farmers.phone as farmer_phone', 'farmers.village as farmer_village')
            ->where('crop_deposits.id', $id)
            ->where('crop_deposits.status', 'Stored')
            ->first();

        if (! $deposit) {
            return redirect()->route('crop.deposits.index')->with('error', 'ဤစာရင်း မရှိတော့ပါ သို့မဟုတ် ပြောင်းပြီးသား ဖြစ်ပါသည်။');
        }

        $items = DB::table('crop_deposit_items')
            ->join('crops', 'crop_deposit_items.crop_id', '=', 'crops.id')
            ->join('grades', 'crop_deposit_items.grade_id', '=', 'grades.id')
            ->select(
                'crop_deposit_items.*',
                'crops.crop_name',
                'crops.unit',
                'crops.commission_amount',
                'crops.quantity_per_basket',
                'grades.grade_name'
            )
            ->where('crop_deposit_items.crop_deposit_id', $id)
            ->get();

        $farmerDebts = DB::table('farmer_debts')
            ->where('farmer_id', $deposit->farmer_id)
            ->whereIn('status', ['Open', 'Partial'])
            ->get();

        return view('cropDeposits.convert', compact('deposit', 'items', 'farmerDebts'));
    }

    public function convertToPurchase(Request $request, $depositId)
    {
        $validated = $request->validate([
            'items'                    => ['required', 'array', 'min:1'],
            'items.*.id'               => ['required', 'integer', 'exists:crop_deposit_items,id'],
            'items.*.actual_buy_price' => ['required', 'numeric', 'min:0'],
            'car_fee_amount'           => ['nullable', 'numeric', 'min:0'],
            'paid_amount'              => ['nullable', 'numeric', 'min:0'],
            'debts'                    => ['nullable', 'array'],
        ], [
            'items.required'           => 'ဝယ်ယူမည့် သီးနှံစာရင်း ထည့်သွင်းပါ။',
            'items.*.actual_buy_price.required' => 'ဝယ်ယူမည့်ဈေး ဖြည့်သွင်းရန် လိုအပ်ပါသည်။',
        ]);

        // ၁။ Deposit ရှိမရှိ နှင့် Status စစ်ဆေးခြင်း
        $deposit = DB::table('crop_deposits')->where('id', $depositId)->first();
        if (! $deposit) {
            return redirect()->back()->with('error', 'သီးနှံအပ်နှံမှု အချက်အလက် မတွေ့ရှိပါ။');
        }

        if ($deposit->status === 'Converted') {
            return redirect()->back()->with('error', 'ဤသီးနှံအပ်နှံမှုကို ဝယ်ယူမှုဘောင်ချာသို့ ပြောင်းပြီးသား ဖြစ်ပါသည်။');
        }

        // ၂။ Custom Validation (ငွေကြေးဆိုင်ရာ စစ်ဆေးချက်များ အားလုံး)
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), []);
        $validator->after(function ($validator) use ($request, $depositId, $deposit) {
            $requestItemIds = collect($request->input('items', []))->pluck('id');

            $depositItems = DB::table('crop_deposit_items')
                ->where('crop_deposit_id', $depositId)
                ->get();

            $validItemsCount = $depositItems->whereIn('id', $requestItemIds)->count();
            if ($validItemsCount !== count($requestItemIds)) {
                $validator->errors()->add('items', 'ရွေးချယ်ထားသော သီးနှံအချက်အလက်များသည် ဤအပ်နှံမှုစာရင်းနှင့် မကိုက်ညီပါ။');
                return;
            }

            // --- သီးနှံစုစုပေါင်းတန်ဖိုး (Total Gross Amount) ကို အကြမ်းတွက်ချက်ခြင်း ---
            $totalGrossAmount = 0;
            foreach ($depositItems as $item) {
                $inputPrice = collect($request->items)->firstWhere('id', $item->id);
                $actualPrice = $inputPrice ? (float) $inputPrice['actual_buy_price'] : (float) $item->actual_buy_price;

                $crop = DB::table('crops')->where('id', $item->crop_id)->first();
                $unit = strtolower($crop->unit ?? '');

                $quantity = (float) $item->quantity;
                $commission = (float) ($crop->commission_amount ?? 0);

                $calculationQty = $quantity;
                if ($unit === 'pound') {
                    $bagCount = (int) ($item->bag_count ?? 0);
                    $calculationQty = max(0, $quantity - ($bagCount * 1));
                }

                $quantityPerBasket = (float) ($crop->quantity_per_basket ?? 0);
                $basketCount = $quantityPerBasket > 0 ? $calculationQty / $quantityPerBasket : $calculationQty;

                $netPricePerUnit = max(0, $actualPrice - $commission);
                $rawAmount = round($basketCount * $netPricePerUnit, 2);
                $grossAmt = floor($rawAmount / 1000) * 1000;

                $totalGrossAmount += $grossAmt;
            }

            // ကားခ ပမာဏ ရယူခြင်း (Request ထဲကပါလာရင် ယူမယ်၊ မပါရင် Deposit ထဲက ယူမယ်)
            $carFeeAmount = (float) ($request->car_fee_amount ?? $deposit->car_fee_amount ?? 0);

            // --- စစ်ဆေးချက် ၁။ ကားခသည် သီးနှံစုစုပေါင်းတန်ဖိုးထက် မများရပါ ---
            if ($carFeeAmount > $totalGrossAmount) {
                $validator->errors()->add(
                    'car_fee_amount',
                    'ကားခ (Car Fee) သည် သီးနှံစုစုပေါင်းတန်ဖိုး (' . number_format($totalGrossAmount) . ' ကျပ်) ထက် ပို၍မရပါ။'
                );
            }

            // အကြွေးရှင်းငွေ စုစုပေါင်း တွက်ချက်ခြင်း
            $totalDebtSettled = 0;
            if ($request->has('debts') && is_array($request->debts)) {
                foreach ($request->debts as $debtId => $settleAmount) {
                    $settleAmount = (float) $settleAmount;
                    if ($settleAmount > 0) {
                        $debtRecord = DB::table('farmer_debts')->where('id', $debtId)->first();
                        if ($debtRecord) {
                            $currentBalance = (float) ($debtRecord->balance_amount ?? ($debtRecord->amount - ($debtRecord->settled_amount ?? 0)));
                            if ($settleAmount > $currentBalance) {
                                $settleAmount = $currentBalance;
                            }
                            $totalDebtSettled += $settleAmount;
                        }
                    }
                }
            }

            // စုစုပေါင်း ကျသင့်ငွေ (Net Amount)
            $netAmount = round($totalGrossAmount - $totalDebtSettled - $carFeeAmount, 2);
            if ($netAmount < 0) $netAmount = 0;

            $paidAmount = (float) ($request->paid_amount ?? 0);

            // --- စစ်ဆေးချက် ၂။ ပေးချေငွေသည် Net Amount ထက် မများရပါ ---
            if ($paidAmount > $netAmount) {
                $validator->errors()->add(
                    'paid_amount',
                    'ပေးချေငွေ (Paid Amount) သည် စုစုပေါင်းကျသင့်ငွေ (' . number_format($netAmount) . ' ကျပ်) ထက် ပို၍မရပါ။'
                );
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ၃။ Database Transaction ဖြင့် အချက်အလက်များ သိမ်းဆည်းခြင်း
        DB::transaction(function () use ($request, $depositId, $deposit) {
            $depositItems = DB::table('crop_deposit_items')->where('crop_deposit_id', $depositId)->get();

            $totalGrossAmount = 0;
            $purchaseItemsData = [];

            foreach ($depositItems as $item) {
                $inputPrice = collect($request->items)->firstWhere('id', $item->id);
                $actualPrice = $inputPrice ? (float) $inputPrice['actual_buy_price'] : (float) $item->actual_buy_price;

                $crop = DB::table('crops')->where('id', $item->crop_id)->first();
                $unit = strtolower($crop->unit ?? '');

                $quantity = (float) $item->quantity;
                $commission = (float) ($crop->commission_amount ?? 0);

                $calculationQty = $quantity;
                if ($unit === 'pound') {
                    $bagCount = (int) ($item->bag_count ?? 0);
                    $calculationQty = max(0, $quantity - ($bagCount * 1));
                }

                $quantityPerBasket = (float) ($crop->quantity_per_basket ?? 0);
                $basketCount = $quantityPerBasket > 0 ? $calculationQty / $quantityPerBasket : $calculationQty;

                $netPricePerUnit = max(0, $actualPrice - $commission);
                $rawAmount = round($basketCount * $netPricePerUnit, 2);
                $grossAmt = floor($rawAmount / 1000) * 1000;
                $remainderAmt = round($rawAmount - $grossAmt, 2);

                $totalGrossAmount += $grossAmt;

                $purchaseItemsData[] = [
                    'crop_id'           => $item->crop_id,
                    'grade_id'          => $item->grade_id,
                    'quantity'          => $quantity,
                    'bag_count'         => ($unit === 'pound') ? (int) $item->bag_count : 0,
                    'reference_price'   => $item->actual_buy_price,
                    'actual_buy_price'  => $actualPrice,
                    'commission_amount' => $commission,
                    'gross_amount'      => $grossAmt,
                    'remainder_amount'  => $remainderAmt,
                    'note'              => $item->note,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ];
            }

            // အကြွေးရှင်းငွေများ update လုပ်ခြင်း
            $totalDebtSettled = 0;
            $debtSettlementsData = [];

            if ($request->has('debts') && is_array($request->debts)) {
                foreach ($request->debts as $debtId => $settleAmount) {
                    $settleAmount = (float) $settleAmount;
                    if ($settleAmount > 0) {
                        $debtRecord = DB::table('farmer_debts')->where('id', $debtId)->first();
                        if ($debtRecord) {
                            $currentBalance = (float) ($debtRecord->balance_amount ?? ($debtRecord->amount - ($debtRecord->settled_amount ?? 0)));
                            if ($settleAmount > $currentBalance) {
                                $settleAmount = $currentBalance;
                            }

                            $totalDebtSettled += $settleAmount;

                            $debtSettlementsData[] = [
                                'farmer_debt_id'  => $debtId,
                                'amount'          => $settleAmount,
                                'settlement_date' => now(),
                                'created_at'      => now(),
                                'updated_at'      => now(),
                            ];

                            $newSettled = (float) ($debtRecord->settled_amount ?? 0) + $settleAmount;
                            $newBalance = (float) $debtRecord->amount - $newSettled;
                            $newStatus = ($newBalance <= 0) ? 'Close' : 'Partial';

                            DB::table('farmer_debts')->where('id', $debtId)->update([
                                'settled_amount' => $newSettled,
                                'balance_amount' => $newBalance,
                                'status'         => $newStatus,
                                'updated_at'     => now(),
                            ]);
                        }
                    }
                }
            }

            $carFeeAmount = (float) ($request->car_fee_amount ?? $deposit->car_fee_amount ?? 0);

            $netAmount = round($totalGrossAmount - $totalDebtSettled - $carFeeAmount, 2);
            if ($netAmount < 0) $netAmount = 0;

            $paidAmount = (float) ($request->paid_amount ?? 0);
            $balanceAmount = round($netAmount - $paidAmount, 2);
            if ($balanceAmount < 0) $balanceAmount = 0;

            $invoiceNo = $this->generateInvoiceNo();

            $purchaseId = DB::table('purchases')->insertGetId([
                'invoice_no'         => $invoiceNo,
                'farmer_id'          => $deposit->farmer_id,
                'purchase_date'      => now(),
                'status'             => 'Confirm',
                'total_gross_amount' => $totalGrossAmount,
                'advance_sub_amount' => $totalDebtSettled,
                'car_no'             => $deposit->car_no ?? $request->car_no ?? '',
                'car_fee_amount'     => $carFeeAmount,
                'net_amount'         => $netAmount,
                'total_paid'         => $paidAmount,
                'balance_amount'     => $balanceAmount,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            if ($paidAmount > 0) {
                DB::table('purchase_payments')->insert([
                    'purchase_id'  => $purchaseId,
                    'payment_date' => now(),
                    'amount'       => $paidAmount,
                    'payment_type' => 'initial',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            foreach ($debtSettlementsData as $settlement) {
                $settlement['purchase_id'] = $purchaseId;
                DB::table('purchase_debt_settlements')->insert($settlement);
            }

            foreach ($purchaseItemsData as $pItem) {
                $pItem['purchase_id'] = $purchaseId;
                DB::table('purchase_items')->insert($pItem);

                $cropId = $pItem['crop_id'];
                $qtyIn = (float) $pItem['quantity'];
                $cropInfo = DB::table('crops')->where('id', $cropId)->first();

                DB::table('stock_movements')->insert([
                    'crop_id'        => $cropId,
                    'movement_date'  => now(),
                    'quantity_in'    => $qtyIn,
                    'quantity_out'   => 0,
                    'unit'           => $cropInfo->unit ?? 'unit',
                    'unit_cost'      => (float) $pItem['actual_buy_price'],
                    'reference_type' => 'Purchase',
                    'reference_id'   => $purchaseId,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                $existingStock = DB::table('stock_balances')->where('crop_id', $cropId)->first();
                if ($existingStock) {
                    DB::table('stock_balances')
                        ->where('crop_id', $cropId)
                        ->update([
                            'quantity_on_hand' => (float) $existingStock->quantity_on_hand + $qtyIn,
                            'updated_at'       => now(),
                        ]);
                } else {
                    DB::table('stock_balances')->insert([
                        'crop_id'          => $cropId,
                        'quantity_on_hand' => $qtyIn,
                        'average_cost'     => (float) $pItem['actual_buy_price'],
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ]);
                }
            }

            // Deposit status ကို Converted သို့ပြောင်းမည်
            DB::table('crop_deposits')
                ->where('id', $depositId)
                ->update(['status' => 'Converted', 'updated_at' => now()]);
        });

        return redirect()->route('crop#deposits#index')->with('success', 'သီးနှံအပ်နှံမှုကို ဝယ်ယူမှုဘောင်ချာသို့ အောင်မြင်စွာ ပြောင်းလဲပြီးပါပြီ။');
    }

    public function createPage()
    {
        $farmers = DB::table('farmers')->where('is_active', 1)->orderBy('name', 'asc')->get();
        $crops   = DB::table('crops')->orderBy('crop_name', 'asc')->get();
        $grades  = DB::table('grades')->get();

        return view('cropDeposits.create', compact('farmers', 'crops', 'grades'));
    }

    public function delete($deposit_id)
    {
        try {
            DB::transaction(function () use ($deposit_id) {
                // Crop Deposit ကို Lock လုပ်ပြီး ရှာမယ်
                $deposit = DB::table('crop_deposits')
                    ->where('id', $deposit_id)
                    ->whereNull('deleted_at')
                    ->lockForUpdate()
                    ->first();

                if (! $deposit) {
                    throw new \Exception('သီးနှံအပ်နှံမှု စာရင်း မတွေ့ပါ။');
                }

                // အကယ်၍ ဝယ်ယူမှုဘောင်ချာသို့ ပြောင်းပြီးသား (Converted) ဖြစ်နေလျှင် မဖျက်ခိုင်းရန်
                if ($deposit->status === 'Converted') {
                    throw new \Exception('ဤအပ်နှံမှုကို ဝယ်ယူမှုဘောင်ချာသို့ ပြောင်းပြီးဖြစ်သောကြောင့် ဖျက်၍ မရပါ။');
                }

                // Deposit Items များကို ဖျက်ဆီးခြင်း
                DB::table('crop_deposit_items')
                    ->where('crop_deposit_id', $deposit_id)
                    ->delete();

                // ပင်မ Crop Deposits ဘောင်ချာကို ဖျက်ခြင်း (Soft Delete သို့မဟုတ် Hard Delete)
                DB::table('crop_deposits')
                    ->where('id', $deposit_id)
                    ->delete(); // (သို့) soft delete သုံးလျှင် ->update(['deleted_at' => now()]) သုံးနိုင်သည်
            });

            return back();

        } catch (\Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'သီးနှံအပ်နှံမှု ဘောင်ချာဖျက်ရာတွင် တစ်ခုခုမှားနေသည်။ ' . $e->getMessage()
            );
        }
    }

    private function generateInvoiceNo(): string
    {
        do {
            $invoiceNo = 'PUR-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));
            $exists    = Purchase::query()->where('invoice_no', $invoiceNo)->exists();
        } while ($exists);

        return $invoiceNo;
    }
}
