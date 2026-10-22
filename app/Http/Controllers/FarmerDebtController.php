<?php
namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerDebt;
use App\Models\PurchaseDebtSettlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class FarmerDebtController extends Controller
{
    //directPage
    public function directPage()
    {
        return view('farmerDebt.create');
    }

    //create
    public function create(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name'      => [
                'required',
                'string',
                'max:150',
                'regex:/\A[\p{Latin}\p{Myanmar}\p{M}\s]+\z/u',
            ],
            'phone'     => 'required|string|max:30|regex:/^[0-9၀-၉+\-\s()]+$/u',
            'village'   => 'required|string|max:150|regex:/\A[\p{Myanmar}\p{M}\s]+\z/u',
            'amount'    => 'required|numeric|min:0.01',
            'debt_type' => 'required|in:PurchaseAdvance,CashLoan',
            'notes'     => 'nullable|string',
        ], [
            'name.required'      => 'တောင်သူအမည်ကို ထည့်သွင်းပေးပါ။',
            'name.max'           => 'တောင်သူအမည်သည် စာလုံးရေ ၁၅၀ ထက် မကျော်ရပါ။',
            'name.regex'         => 'တောင်သူအမည်တွင် English နှင့် မြန်မာအက္ခရာများကိုသာ ထည့်သွင်းပေးပါ။',

            'phone.required'     => 'ဖုန်းနံပါတ်ကို ထည့်သွင်းပေးပါ။',
            'phone.string'       => 'ဖုန်းနံပါတ်ကို မှန်ကန်စွာ ထည့်သွင်းပေးပါ။',
            'phone.max'          => 'ဖုန်းနံပါတ်သည် စာလုံးရေ ၃၀ ထက် မကျော်ရပါ။',
            'phone.regex'        => 'ဖုန်းနံပါတ်ပုံစံ မှန်ကန်စွာ ဖြည့်ပေးပါ။',

            'village.required'   => 'ကျေးရွာအမည်ကို ထည့်သွင်းပေးပါ။',
            'village.string'     => 'ကျေးရွာအမည်ကို မှန်ကန်စွာ ထည့်သွင်းပေးပါ။',
            'village.max'        => 'ကျေးရွာအမည်သည် စာလုံးရေ ၁၅၀ ထက် မကျော်ရပါ။',
            'village.regex'      => 'ကျေးရွာအမည်ကို မြန်မာစာလုံးများဖြင့်သာ ထည့်သွင်းပေးပါ။',

            'amount.required'    => 'ကြိုသုံးငွေပမာဏကို ထည့်သွင်းပေးပါ။',
            'amount.numeric'     => 'ကြိုသုံးငွေပမာဏသည် ကိန်းဂဏန်းဖြစ်ရပါမည်။',
            'amount.min'         => 'ကြိုသုံးငွေပမာဏသည် သုညထက် ကြီးရပါမည်။',

            'debt_type.required' => 'အကြွေးအမျိုးအစားကို ရွေးချယ်ပေးပါ။',
            'debt_type.in'       => 'ရွေးချယ်ထားသော အကြွေးအမျိုးအစား မမှန်ကန်ပါ။',

            'notes.string'       => 'မှတ်ချက်ကို မှန်ကန်စွာ ထည့်သွင်းပေးပါ။',
        ]);

// Farmer နှင့် FarmerDebt ကို တစ်ပြိုင်နက် သိမ်းဆည်းရန်
        DB::transaction(function () use ($validated) {

            // 1. farmers table ထဲသို့ သိမ်းမည်
            $farmer = Farmer::create([
                'name'      => $validated['name'],
                'phone'     => $validated['phone'],
                'village'   => $validated['village'],
                'is_active' => true,
                'notes'     => $validated['notes'] ?? null,
            ]);

            $farmer->farmer_code = 'F-' . str_pad(
                (string) $farmer->id,
                6,
                '0',
                STR_PAD_LEFT
            );
            $farmer->save();

            // 2. farmer_debts table ထဲသို့ သိမ်းမည်
            FarmerDebt::create([
                'farmer_id'      => $farmer->id,
                'debt_type'      => $validated['debt_type'],
                'amount'         => $validated['amount'],
                'settled_amount' => 0,
                'balance_amount' => $validated['amount'],
                'status'         => 'Open',
            ]);

        });

        Alert::success('အောင်မြင်ပါသည်', 'ကြိုသုံးငွေယူမည့် တောင်သူကို အောင်မြင်စွာစာရင်းသွင်းပြီးပါပြီ။');

        return to_route('FarmerDebt#list');

    }

    //list
    public function list()
    {

        $farmerDebt = FarmerDebt::leftJoin(
            'farmers',
            'farmer_debts.farmer_id',
            'farmers.id'
        )->select(
            'farmer_debts.*',
            'farmers.farmer_code',
            'farmers.name'
        )->orderBy(
            'farmer_debts.created_at',
            'desc'
        )->paginate(10);

        return view('farmerDebt.index', compact('farmerDebt'));
    }

    //settlement
    public function settlement($id)
    {

        $farmerDebt = FarmerDebt::leftJoin(
            'farmers',
            'farmer_debts.farmer_id',
            'farmers.id'
        )->select(
            'farmer_debts.id',
            'farmer_debts.balance_amount',
            'farmers.name'
        )->where(
            'farmer_debts.id',
            $id
        )->first();

        return view('farmerDebt.settlement', compact('farmerDebt'));
    }

    //settlementCreate
    public function settlementCreate(Request $request, $id)
    {

        // Validation
        $validated = $request->validate([
            'balance_amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ], [
            'balance_amount.required' => 'ဆပ်မည့်ငွေပမာဏကို ထည့်သွင်းပေးပါ။',
            'balance_amount.numeric'  => 'ငွေပမာဏကို ကိန်းဂဏန်းဖြင့် ထည့်သွင်းပေးပါ။',
            'balance_amount.gt'       => 'ဆပ်မည့်ငွေပမာဏသည် သုညထက် ကြီးရပါမည်။',
        ]);

        $settlementAmount = (float) $validated['balance_amount'];

        $result = DB::transaction(function () use ($id, $settlementAmount) {
            $farmerDebt = FarmerDebt::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            $currentBalance = (float) $farmerDebt->balance_amount;

            if ($settlementAmount > $currentBalance) {
                return [
                    'success' => false,
                    'balance' => $currentBalance,
                ];
            }

            $settledAmount = (float) ($farmerDebt->settled_amount ?? 0)
                 + $settlementAmount;

            $balanceAmount = round($currentBalance - $settlementAmount, 2);

            $farmerDebt->update([
                'settled_amount' => $settledAmount,
                'balance_amount' => $balanceAmount,
                'status'         => $balanceAmount <= 0 ? 'Close' : 'Partial',
            ]);

            PurchaseDebtSettlement::create([
                'farmer_debt_id'  => $farmerDebt->id,
                'amount'          => $settlementAmount,
                'settlement_date' => now(),
            ]);

            return [
                'success' => true,
            ];
        });

        if (! $result['success']) {
            Alert::error(
                'ငွေပမာဏ မမှန်ပါ',
                'ဆပ်မည့်ငွေပမာဏသည် ကျန်ရှိသောအကြွေး '
                . number_format($result['balance'], 2)
                . ' ကျပ်ထက် မပိုရပါ။'
            );

            return redirect()->back()->withInput();
        }

        Alert::success(
            'အောင်မြင်ပါသည်',
            'အကြွေးပြန်ဆပ်ငွေကို အောင်မြင်စွာ စာရင်းသွင်းပြီးပါပြီ။'
        );

        return to_route('FarmerDebt#list');

    }

    //settlementList
    public function settlementList($id)
    {

        $farmerDebt = FarmerDebt::leftJoin(
            'farmers',
            'farmer_debts.farmer_id',
            'farmers.id'
        )->select(
            'farmer_debts.id',
            'farmer_debts.amount',
            'farmer_debts.settled_amount',
            'farmer_debts.balance_amount',
            'farmer_debts.debt_date',
            'farmer_debts.debt_type',
            'farmer_debts.status',
            'farmers.name',
            'farmers.phone',
            'farmers.village'
        )->where(
            'farmer_debts.id',
            $id
        )->first();

        $settlement = PurchaseDebtSettlement::leftJoin(
            'farmer_debts',
            'purchase_debt_settlements.farmer_debt_id',
            'farmer_debts.id'
        )
            ->leftJoin(
                'farmers',
                'farmer_debts.farmer_id',
                'farmers.id'
            )
            ->select(
                'purchase_debt_settlements.*',
                'farmer_debts.balance_amount',
                'farmer_debts.status',
                'farmers.name'
            )
            ->where(
                'purchase_debt_settlements.farmer_debt_id',
                $id
            )->orderBy(
            'purchase_debt_settlements.created_at',
            'desc'
        )->paginate(10);

        return view('farmerDebt.settlementList', compact('farmerDebt', 'settlement'));

    }
}
