<?php
namespace App\Http\Controllers;

use App\Models\CarFeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CarFeeController extends Controller
{
    //directPage
    public function directPage()
    {
        return view('carFee.create');
    }

    //create
    public function create(Request $request)
    {
        $validated = $request->validate([
            'car_no'      => 'required|string|max:255',
            'driver_name' => 'required|string|max:150',
        ], [
            'car_no.required'      => 'ကားနံပါတ်ကို ရွေးချယ်ပေးပါ။',
            'car_no.max'           => 'ကားနံပါတ်သည် စာလုံး ၂၅၅ လုံးထက် မကျော်ရပါ။',
            'driver_name.required' => 'ကားသမားအမည်ကို ဖြည့်ပေးပါ။',
            'driver_name.string'   => 'ကားသမားအမည် မှန်ကန်စွာ ဖြည့်ပေးပါ။',
            'driver_name.max'      => 'ကားသမားအမည်သည် စာလုံး ၁၅၀ ထက် မကျော်ရပါ။',
        ]);

        $today = now()->toDateString();
        $carNo = trim($validated['car_no']);

        $payment = DB::transaction(function () use ($today, $carNo, $validated) {

            // ယနေ့အတွက် ကားနံပါတ်တူပြီး အတည်ပြုထားသော
            // မပေးချေရသေးသည့် Purchase များကို ရှာမည်
            $purchases = DB::table('purchases')
                ->whereDate('purchase_date', $today)
                ->where('car_no', $carNo)
                ->whereIn('status', ['Confirm', 'Complete'])
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('car_fee_payment_items')
                        ->whereColumn(
                            'car_fee_payment_items.purchase_id',
                            'purchases.id'
                        );
                })
                ->orderBy('id')
                ->lockForUpdate()
                ->get(['id', 'car_fee_amount']);

            if ($purchases->isEmpty()) {
                throw ValidationException::withMessages([
                    'car_no' => 'ဒီနေ့အတွက် ဒီကားနံပါတ်နဲ့ ကားခတွက်ရန် ကျန်ရှိသော အဝယ်စာရင်း မရှိပါ။',
                ]);
            }

            // မပေးချေရသေးသော Purchase များ၏ ကားခပေါင်း
            $totalAmount = $purchases->sum('car_fee_amount');

            // ကားခပေးချေမှု အသစ်ဖန်တီးမည်
            $payment = CarFeePayment::create([
                'car_no'       => $carNo,
                'driver_name'  => trim($validated['driver_name']),
                'total_amount' => $totalAmount,
                'payment_date' => $today,
            ]);

            // ယခုအကြိမ်တွင် ပါဝင်သော Purchase များကို မှတ်တမ်းတင်မည်
            foreach ($purchases as $purchase) {
                DB::table('car_fee_payment_items')->insert([
                    'car_fee_payment_id' => $payment->id,
                    'purchase_id'        => $purchase->id,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            return $payment;
        });

        return redirect()
            ->route('CarFee#voucher', $payment->id)
            ->with('success', 'ကားခစာရင်း သိမ်းဆည်းပြီးပါပြီ။');
    }

    //list
    public function list(Request $request)
    {
        $today = now()->toDateString();

        $payments = DB::table('car_fee_payments')
            ->whereNull('deleted_at');

        // ကားသမားအမည် ရှာဖွေခြင်း
        if ($request->filled('searchData')) {
            $payments->where('driver_name', 'like', '%' . $request->searchData . '%');
        }

        // From Date
        if ($request->filled('from_date')) {
            $payments->whereDate('payment_date', '>=', $request->from_date);
        }

        // To Date
        if ($request->filled('to_date')) {
            $payments->whereDate('payment_date', '<=', $request->to_date);
        }

        $payments = $payments
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Payment တစ်ခုစီနဲ့ ချိတ်ထားတဲ့ Purchase ID များ
        foreach ($payments as $payment) {
            $payment->purchase_ids = DB::table('car_fee_payment_items')
                ->where('car_fee_payment_id', $payment->id)
                ->pluck('purchase_id')
                ->implode(', ');
        }

        return view('carFee.list', compact(
            'today',
            'payments'
        ));
    }

    // ကားနံပါတ် ရှာဖွေခြင်းနှင့် ကားခပေါင်းခြင်း
    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'car_no' => 'nullable|string|max:255',
        ], [
            'search.max' => 'ရှာဖွေမည့် ကားနံပါတ်သည် စာလုံး ၂၅၅ လုံးထက် မကျော်ရပါ။',
            'car_no.max' => 'ကားနံပါတ်သည် စာလုံး ၂၅၅ လုံးထက် မကျော်ရပါ။',
        ]);

        $today = now()->toDateString();

        // ကားနံပါတ်များ ရှာဖွေခြင်း
        if (! $request->filled('car_no')) {

            $cars = DB::table('purchases')
                ->whereDate('purchase_date', $today)
                ->whereIn('status', ['Confirm', 'Complete'])
                ->whereNotNull('car_no')
                ->where('car_no', 'like', '%' . $request->search . '%')
                ->distinct()
                ->orderBy('car_no')
                ->pluck('car_no');

            return response()->json([
                'cars' => $cars,
            ]);
        }

        // ကားနံပါတ် ရွေးချယ်ပြီးပါက
        $carNo = trim($request->car_no);

        // ကားခမပေးရသေးသော Purchase များကိုသာ ရှာမည်
        $purchases = DB::table('purchases')
            ->whereDate('purchase_date', $today)
            ->where('car_no', $carNo)
            ->whereIn('status', ['Confirm', 'Complete'])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('car_fee_payment_items')
                    ->whereColumn(
                        'car_fee_payment_items.purchase_id',
                        'purchases.id'
                    );
            })
            ->orderBy('id')
            ->get(['id', 'car_fee_amount']);

        // ကားခတွက်ရန် Purchase မရှိတော့ပါက
        if ($purchases->isEmpty()) {
            return response()->json([
                'already_paid' => true,
                'message'      => 'ဒီကားနံပါတ်အတွက် ကားခပေးရန် ကျန်ရှိသော အဝယ်စာရင်း မရှိပါ။',
            ], 404);
        }

        // ကားခမပေးရသေးသော Purchase များ၏ စုစုပေါင်း
        return response()->json([
            'already_paid' => false,
            'car_no'       => $carNo,
            'total_amount' => $purchases->sum('car_fee_amount'),
            'purchase_ids' => $purchases->pluck('id'),
        ]);
    }

    //voucher
    public function voucher($id)
    {

        $carfee = CarFeePayment::where('id', $id)->get();

        return view('carFee.voucher', compact('carfee'));

    }

    //detail
    public function detail($id)
    {
        $payment = DB::table('car_fee_payments')
            ->where('id', $id)
            ->whereNull('deleted_at')
            ->first();

        if (! $payment) {
            return response()->json([
                'message' => 'ကားဘောင်ချာစာရင်း မတွေ့ပါ။',
            ], 404);
        }

        // ဒီ Voucher မှာ ပါဝင်တဲ့ Purchase များကိုသာ ယူမည်
        $purchases = DB::table('car_fee_payment_items')
            ->join(
                'purchases',
                'car_fee_payment_items.purchase_id',
                '=',
                'purchases.id'
            )
            ->join(
                'farmers',
                'purchases.farmer_id',
                '=',
                'farmers.id'
            )
            ->where(
                'car_fee_payment_items.car_fee_payment_id',
                $payment->id
            )
            ->whereNull('purchases.deleted_at')
            ->whereNull('farmers.deleted_at')
            ->select(
                'purchases.id',
                'purchases.invoice_no',
                'farmers.name as farmer_name',
                'purchases.car_fee_amount',
                'purchases.purchase_date'
            )
            ->orderBy('purchases.id')
            ->get();

        return response()->json([
            'car_no'       => $payment->car_no,
            'driver_name'  => $payment->driver_name,
            'payment_date' => $payment->payment_date,
            'purchases'    => $purchases,
        ]);
    }

}
