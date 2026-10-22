<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\DailyPrice;
use App\Models\Farmer;
use App\Models\Grade;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            'reference_price' => $price ?? 0,
        ]);
    }

    /**
     * Save the complete purchase and its item rows, then open its printable voucher.
     * Deductions are not payment transactions; this version does not write
     * farmer_payments because the current form has no "paid now" amount.
     */
    public function store(Request $request)
    {
        // dd($request->toArray());

        $data = $request->validate([
            'farmer_id'                            => ['required', 'integer', 'exists:farmers,id'],
            'car_no'                               => ['nullable', 'string', 'max:255'],
            'car_fee_amount'                       => ['nullable', 'numeric', 'min:0'],
            'debts'                                => ['nullable', 'array'],
            'debts.*'                              => ['nullable', 'numeric', 'min:0'],
            'advance_deduction'                    => ['nullable', 'numeric', 'min:0'],
            'other_deduction'                      => ['nullable', 'numeric', 'min:0'],
            'purchase_items'                       => ['required', 'array', 'min:1'],
            'purchase_items.*.crop_id'             => ['required', 'integer', 'exists:crops,id'],
            'purchase_items.*.grade_id'            => ['required', 'integer', 'exists:grades,id'],
            'purchase_items.*.quantity'            => ['required', 'numeric', 'gt:0'],
            'purchase_items.*.quantity_per_basket' => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.unit'                => ['required', 'string', 'max:30'],
            'purchase_items.*.reference_price'     => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.actual_buy_price'    => ['required', 'numeric', 'min:0'],
            'purchase_items.*.commission_amount'   => ['required', 'numeric', 'min:0'],
            'purchase_items.*.car_fee_amount'      => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.note'                => ['nullable', 'string', 'max:255'],
        ], [
            'farmer_id.required'           => 'တောင်သူကို ရွေးချယ်ပါ။',
            'purchase_items.required'      => 'သီးနှံစာရင်း အနည်းဆုံးတစ်မျိုး ထည့်ပါ။',
            'purchase_items.min'           => 'သီးနှံစာရင်း အနည်းဆုံးတစ်မျိုး ထည့်ပါ။',
            'purchase_items.*.quantity.gt' => 'သီးနှံအရေအတွက်သည် သုညထက် ကြီးရပါမည်။',
        ]);

        $carNo =$data['car_no'] ?? '';
        $carFee = (float) ($data['car_fee_amount'] ?? 0);
        $inputDebts = $data['debts'] ?? []; // Frontend က ပို့လိုက်သော ဖြတ်မည့်အကြွေးများ [debt_id => amount]

        $purchase = DB::transaction(function () use (
            $data,$carNo,
            $carFee,$inputDebts
        ) {
            $totalGrossAmount = 0;
            $items       = [];

            // 1. သီးနှံတစ်မျိုးချင်းစီ တွက်ချက်ခြင်း
            foreach ($data['purchase_items'] as $index => $row) {
                $crop = DB::table('crops')
                    ->where('id', $row['crop_id'])
                    ->first();
                $grade = DB::table('grades')
                    ->where('id', $row['grade_id'])
                    ->where('crop_id', $row['crop_id'])
                    ->first();
                if (! $crop || ! $grade) {
                    throw ValidationException::withMessages([
                        "purchase_items.$index.grade_id" =>
                        'ရွေးထားသော သီးနှံနှင့် အမျိုးအစား မကိုက်ညီပါ။',
                    ]);
                }
                // dd($row);
                $quantity     = (float) $row['quantity'];
                $actualPrice  = (float) $row['actual_buy_price'];
                $commission   = (float) ($row['commission_amount'] ?? 0);

                $unit = strtolower($crop->unit ?? '');
                $bagCount = (int) ($row['bag_count'] ?? 0);
                if ($unit === 'pound' && $bagCount <= 0) {
                    throw ValidationException::withMessages([
                        "purchase_items.$index.bag_count" =>
                        'ကျေးဇူးပြု၍ အိတ်အရေအတွက်ကို မှန်ကန်စွာ ဖြည့်ပါ။',
                    ]);
                }
                if ($commission > $actualPrice) {
                    throw ValidationException::withMessages([
                        "purchase_items.$index.commission_amount" =>
                        'ပွဲခသည် ဝယ်ဈေးထက် မများရပါ။',
                    ]);
                }

                $calculationQty = $quantity;
                if ($unit === 'pound') {
                    $deductionPerBag = 1; // လိုအပ်သလို ချိန်ညှိနိုင်သည်
                    $calculationQty = $quantity - ($bagCount * $deductionPerBag);
                    if ($calculationQty < 0) $calculationQty = 0;
                }
                $quantityPerBasket = (float) ($crop->quantity_per_basket ?? 0);
                $basketCount = $quantityPerBasket > 0
                    ? $calculationQty / $quantityPerBasket
                    : $calculationQty;
                $rawAmount = round($basketCount * ($actualPrice - $commission), 2);
                // dd($actualPrice);
                $grossAmt = floor($rawAmount / 1000) * 1000;
                $remainderAmt = round($rawAmount - $grossAmt, 2);

                $items[] = [
                    'crop_id'           => $crop->id,
                    'grade_id'          => $grade->id,
                    'quantity'          => $quantity,
                    'bag_count'         => ($unit === 'pound') ? $bagCount : 0,
                    'reference_price'   => $row['reference_price'] ?? 0,
                    'actual_buy_price'  => $actualPrice,
                    'commission_amount' => $commission,
                    'gross_amount'      => $grossAmt,
                    'remainder_amount'  => $remainderAmt,
                    'note'              => $row['note'] ?? null,
                ];

                $totalGrossAmount += $grossAmt;
            }

            $totalGrossAmount = round($totalGrossAmount, 2);
            // dd($totalGrossAmount);

            // 2. တောင်သူ၏ အကြွေးများကို စစ်ဆေးပြီး နှုတ်ငွေတွက်ချက်ခြင်း
            $totalDebtSettlement = 0;
            $settlementsData = [];

            if (!empty($inputDebts)) {
                foreach ($inputDebts as $debtId => $settleAmount) {
                    $settleAmount = (float)$settleAmount;
                    if ($settleAmount <= 0) continue;

                    $farmerDebt = DB::table('farmer_debts')
                        ->where('id', $debtId)
                        ->where('farmer_id', $data['farmer_id'])
                        ->whereIn('status', ['Open', 'Partial'])
                        ->first();

                    if ($farmerDebt) {
                        $balance = (float)$farmerDebt->balance_amount;
                        if ($settleAmount > $balance) {
                            $settleAmount = $balance; // လက်ကျန်ထက် ပိုမဖြတ်ရန်
                        }

                        $totalDebtSettlement += $settleAmount;
                        $settlementsData[] = [
                            'farmer_debt_id' => $farmerDebt->id,
                            'settle_amount' => $settleAmount,
                            'farmer_debt' => $farmerDebt,
                        ];
                    }
                }
            }

            $totalDebtSettlement = round($totalDebtSettlement, 2);
            $carFee = round($carFee, 2);
            // စုစုပေါင်း နုတ်ငွေ (အကြွေး + ကားခ)
            $totalDeduction = round($totalDebtSettlement + $carFee, 2);

            $advancSubAmount = round($totalGrossAmount - $totalDebtSettlement, 2);
            // 1. သင့်ငွေ (Net Amount) = သီးနှံတန်ဖိုး - စုစုပေါင်းနုတ်ငွေ
            $netAmount = round($totalGrossAmount - $totalDeduction, 2);
            if ($netAmount < 0) {
                throw ValidationException::withMessages([
                    'car_fee_amount' => 'နုတ်ငွေစုစုပေါင်းသည် သီးနှံတန်ဖိုးထက် မများရပါ။',
                ]);
            }
            // Frontend က ပို့လိုက်သော ပေးချေငွေ (Paid Amount) ကို ယူမည် (မပါလာပါက Net Amount အပြည့်ပေးသည်ဟု ယူဆမည်)
            $totalPaid = (float) ($data['paid_amount'] ?? $netAmount);
            if ($totalPaid < 0) {
                $totalPaid = 0;
            }
            // အကယ်၍ ပေးချေငွေက သင့်ငွေထက် များနေပါက သင့်ငွေအပြည့်သာ ယူမည်
            if ($totalPaid > $netAmount) {
                $totalPaid = $netAmount;
            }
            $totalPaid = round($totalPaid, 2);
            // နောက်ဆုံး ပေးရန်ကျန်ငွေ (Balance Amount) = သင့်ငွေ (Net Amount) - ပေးချေငွေ (Paid Amount)
            $balanceAmount = round($netAmount - $totalPaid, 2);

            // dd($totalPaid);

            // 3. Purchase စာရင်း သိမ်းခြင်း
            $purchaseId = DB::table('purchases')->insertGetId([
                'invoice_no'           => $this->generateInvoiceNo(),
                'farmer_id'            => $data['farmer_id'],
                'purchase_date'        => now(),
                'status'               => $balanceAmount <= 0 ? 'Complete' : 'Confirm',
                'total_gross_amount'   => $totalGrossAmount,
                'advance_sub_amount'   => $advancSubAmount, // အကြွေးနှုတ်လိုက်သော စုစုပေါင်းသီးနှံတန်ဖိုး
                'car_no'               => $carNo,
                'car_fee_amount'       => $carFee,
                'net_amount'           => $netAmount,
                'total_paid'           => $totalPaid,
                'balance_amount'       => $balanceAmount,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);

            // 4. Purchase Item စာရင်းများ သိမ်းခြင်း
            foreach ($items as$item) {
                $item['purchase_id'] =$purchaseId;
                $item['created_at']  = now();
                $item['updated_at']  = now();
                DB::table('purchase_items')->insert($item);
            }

            // 5. အကြွေးဆပ်မှုများကို `purchase_debt_settlements` တွင် သိမ်းပြီး `farmer_debts` ကို Update လုပ်ခြင်း
            foreach ($settlementsData as $st) {
                DB::table('purchase_debt_settlements')->insert([
                    'purchase_id'       => $purchaseId, // တကယ်လို့ purchase_id column ပါရင်ထည့်ပါ (သို့မဟုတ် settlement_date တို့ထည့်ရန်)
                    'farmer_debt_id'    => $st['farmer_debt_id'],
                    'amount'            => $st['settle_amount'],
                    'settlement_date'   => now(),
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                $debt =$st['farmer_debt'];
                $newSettledAmount = (float) $debt->settled_amount + $st['settle_amount'];
                $newBalanceAmount = (float) $debt->amount -$newSettledAmount;
                $newStatus =$newBalanceAmount <= 0 ? 'Close' : 'Partial';

                DB::table('farmer_debts')
                    ->where('id', $debt->id)
                    ->update([
                        'settled_amount' => $newSettledAmount,
                        'balance_amount' => $newBalanceAmount,
                        'status'         => $newStatus,
                        'updated_at'     => now(),
                    ]);
            }


            return DB::table('purchases')
                ->where('id', $purchaseId)
                ->first();
        });

        return redirect()
            ->route('purchases#voucher', $purchase->id)
            ->with('success', 'ဝယ်ယူမှုစာရင်း သိမ်းဆည်းပြီးပါပြီ။');
    }

    /** Display a printable purchase voucher. */
    public function voucher($purchaseId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $farmer   = Farmer::findOrFail($purchase->farmer_id);

        $items = DB::table('purchase_items')
            ->join('crops', 'purchase_items.crop_id', '=', 'crops.id')
            ->where('purchase_items.purchase_id', $purchaseId)
            ->select('purchase_items.*', 'crops.quantity_per_basket', 'crops.crop_name', 'crops.unit')
            ->get();

        $gradeNames = DB::table('grades')
            ->whereIn('id', $items->pluck('grade_id')->unique())
            ->pluck('grade_name', 'id');

        // ဤနေရာတွင် ဤဘောင်ချာအတွက် ဖြတ်တောက်ခဲ့သော အကြွေးစာရင်းများကို ယူမည်
        $debtSettlements = DB::table('purchase_debt_settlements')
            ->join('farmer_debts', 'purchase_debt_settlements.farmer_debt_id', '=', 'farmer_debts.id')
            ->where('purchase_debt_settlements.purchase_id', $purchaseId)
            ->select('purchase_debt_settlements.*', 'farmer_debts.debt_type', 'farmer_debts.debt_date', 'farmer_debts.amount as original_amount')
            ->get();

        // dd($items->toArray());
        // dd($purchase->toArray());
        // dd($farmer->toArray());
        // dd($cropNames->toArray());
        // dd($gradeNames->toArray());

        return view('purchases.voucher', compact(
            'purchase',
            'farmer',
            'items',
            'gradeNames',
            'debtSettlements'
        ));
    }

    /** Create a readable unique invoice number for the voucher. */
    private function generateInvoiceNo(): string
    {
        do {
            $invoiceNo = 'PUR-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4));
            $exists    = Purchase::query()->where('invoice_no', $invoiceNo)->exists();
        } while ($exists);

        return $invoiceNo;
    }

    public function edit($id)
    {
        return view('purchases.edit');
    }
}
