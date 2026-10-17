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
            'purchase_items.*.commission_amount'   => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.car_fee_amount'      => ['nullable', 'numeric', 'min:0'],
            'purchase_items.*.note'                => ['nullable', 'string', 'max:255'],
        ], [
            'farmer_id.required'           => 'တောင်သူကို ရွေးချယ်ပါ။',
            'purchase_items.required'      => 'သီးနှံစာရင်း အနည်းဆုံးတစ်မျိုး ထည့်ပါ။',
            'purchase_items.min'           => 'သီးနှံစာရင်း အနည်းဆုံးတစ်မျိုး ထည့်ပါ။',
            'purchase_items.*.quantity.gt' => 'သီးနှံအရေအတွက်သည် သုညထက် ကြီးရပါမည်။',
        ]);

        $advanceDeduction = (float) ($data['advance_deduction'] ?? 0);
        $otherDeduction   = (float) ($data['other_deduction'] ?? 0);

        $purchase = DB::transaction(function () use (
            $data,
            $advanceDeduction,
            $otherDeduction
        ) {
            $totalAmount = 0;
            $totalCarFee = 0;
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

                $quantity    = (float) $row['quantity'];
                $actualPrice = (float) $row['actual_buy_price'];
                $commission  = (float) ($row['commission_amount'] ?? 0);
                $carFee      = (float) ($row['car_fee_amount'] ?? 0);

                if ($commission > $actualPrice) {
                    throw ValidationException::withMessages([
                        "purchase_items.$index.commission_amount" =>
                        'ပွဲခသည် ဝယ်ဈေးထက် မများရပါ။',
                    ]);
                }

                // တင်း အရေအတွက် တွက်ချက်ခြင်း
                $quantityPerBasket = (float) ($crop->quantity_per_basket ?? 0);

                $basketCount = $quantityPerBasket > 0
                    ? $quantity / $quantityPerBasket
                    : $quantity;

                // သီးနှံကျသင့်ငွေ
                $grossAmount = round(
                    $basketCount * ($actualPrice - $commission),
                    2
                );

                if ($carFee > $grossAmount) {
                    throw ValidationException::withMessages([
                        "purchase_items.$index.car_fee_amount" =>
                        'ကားခသည် သီးနှံကျသင့်ငွေထက် မများရပါ။',
                    ]);
                }

                $items[] = [
                    'crop_id'           => $crop->id,
                    'grade_id'          => $grade->id,
                    'quantity'          => $quantity,
                    'reference_price'   => $row['reference_price'] ?? 0,
                    'actual_buy_price'  => $actualPrice,
                    'commission_amount' => $commission,
                    'car_fee_amount'    => $carFee,
                    'gross_amount'      => $grossAmount,
                    'note'              => $row['note'] ?? null,
                ];

                $totalAmount += $grossAmount;
                $totalCarFee += $carFee;
            }

            $totalAmount = round($totalAmount, 2);
            $totalCarFee = round($totalCarFee, 2);

            // 2. နုတ်ငွေ တွက်ချက်ခြင်း
            $manualDeduction = round(
                $advanceDeduction + $otherDeduction,
                2
            );

            if ($manualDeduction > $totalAmount - $totalCarFee) {
                throw ValidationException::withMessages([
                    'advance_deduction' =>
                    'နုတ်ငွေစုစုပေါင်းသည် ကားခနုတ်ပြီး ကျန်ငွေထက် မများရပါ။',
                ]);
            }

            $totalDeduction = round(
                $totalCarFee + $manualDeduction,
                2
            );

            $netAmount = round($totalAmount - $totalDeduction, 2);

            // 3. Purchase စာရင်း သိမ်းခြင်း
            $purchaseId = DB::table('purchases')->insertGetId([
                'invoice_no'      => $this->generateInvoiceNo(),
                'farmer_id'       => $data['farmer_id'],
                'purchase_date'   => now(),
                'status'          => $netAmount <= 0 ? 'Complete' : 'Confirm',
                'total_amount'    => $totalAmount,
                'total_deduction' => $totalDeduction,
                'net_amount'      => $netAmount,
                'total_paid'      => 0,
                'balance_amount'  => $netAmount,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 4. Purchase Item စာရင်းများ သိမ်းခြင်း
            foreach ($items as $item) {
                $item['purchase_id'] = $purchaseId;
                $item['created_at']  = now();
                $item['updated_at']  = now();

                DB::table('purchase_items')->insert($item);
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

        $totalCarFee = round((float) $items->sum('car_fee_amount'), 2);

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
            'totalCarFee',
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
