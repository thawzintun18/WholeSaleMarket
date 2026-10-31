<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    //create
    public function create(Request $request)
    {

        $validated = $request->validate([
            'name'    => [
                'required',
                'string',
                'max:150',
                'regex:/\A[\p{Latin}\p{Myanmar}\p{M}\s]+\z/u',
            ],
            'phone'   => 'required|string|max:30|regex:/^[0-9၀-၉+\-\s()]+$/u',
            'address' => 'required|string|max:150|regex:/\A[\p{Myanmar}\p{M}\s]+\z/u',
            'notes'   => 'nullable|string',
        ], [
            'name.required'    => '၀ယ်သူအမည်ကို ထည့်သွင်းပေးပါ။',
            'name.max'         => '၀ယ်သူအမည်သည် စာလုံးရေ ၁၅၀ ထက် မကျော်ရပါ။',
            'name.regex'       => '၀ယ်သူအမည်တွင် English နှင့် မြန်မာအက္ခရာများကိုသာ ထည့်သွင်းပေးပါ။',

            'phone.required'   => 'ဖုန်းနံပါတ်ကို ထည့်သွင်းပေးပါ။',
            'phone.string'     => 'ဖုန်းနံပါတ်ကို မှန်ကန်စွာ ထည့်သွင်းပေးပါ။',
            'phone.max'        => 'ဖုန်းနံပါတ်သည် စာလုံးရေ ၃၀ ထက် မကျော်ရပါ။',
            'phone.regex'      => 'ဖုန်းနံပါတ်ပုံစံ မှန်ကန်စွာ ဖြည့်ပေးပါ။',

            'address.required' => 'လိပ်စာအမည်ကို ထည့်သွင်းပေးပါ။',
            'address.string'   => 'လိပ်စာအမည်ကို မှန်ကန်စွာ ထည့်သွင်းပေးပါ။',
            'address.max'      => 'လိပ်စာအမည်သည် စာလုံးရေ ၁၅၀ ထက် မကျော်ရပါ။',
            'address.regex'    => 'လိပ်စာအမည်ကို မြန်မာစာလုံးများဖြင့်သာ ထည့်သွင်းပေးပါ။',

            'notes.string'     => 'မှတ်ချက်ကို မှန်ကန်စွာ ထည့်သွင်းပေးပါ။',
        ]);

        $customer = Customer::create([
            'name'      => $validated['name'],
            'phone'     => $validated['phone'] ?? null,
            'address'   => $validated['address'] ?? null,
            'notes'     => $validated['notes'] ?? null,
            'is_active' => true,
        ]);

        $customer->customer_code = 'C-' . str_pad(
            (string) $customer->id,
            6,
            '0',
            STR_PAD_LEFT
        );

        $customer->save();

        return response()->json([
            'message'  => '၀ယ်သူအသစ် စာရင်းသွင်းပြီးပါပြီ။',
            'customer' => [
                'id'            => $customer->id,
                'customer_code' => $customer->customer_code,
                'name'          => $customer->name,
                'phone'         => $customer->phone,
                'address'       => $customer->address,
            ],
        ], 201);

    }

    //search
    public function search(Request $request)
    {

        $search = trim($request->input('search', ''));

        if ($search === '') {
            return response()->json([]);
        }

        $customers = Customer::query()
            ->where('is_active', true)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('customer_code', 'like', "%{$search}%");
            })
            ->select('id', 'customer_code', 'name', 'phone', 'address')
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($customers);

    }

    //list
    public function list(Request $request)
    {

        $customers = Customer::query();

        if ($request->search) {
            $customers->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('customer_code', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        if ($request->village) {
            $customers->where('address', 'like', '%' . $request->address . '%');
        }

        if ($request->status == 'active') {
            $customers->where('is_active', 1);
        } elseif ($request->status == 'inactive') {
            $customers->where('is_active', 0);
        }

        $customers = $customers->orderBy('id', 'desc')->paginate(10);

        // dd($customers->toArray());

        return view('customer.list', compact('customers'));
    }

    //delete
    public function delete($id)
    {
        $crop = Customer::findOrFail($id);

        $crop->delete();

        return back();

    }

    //edit
    public function edit($id)
    {
        $customer = Customer::select('id', 'customer_code', 'name', 'phone', 'address', 'notes', 'is_active')
            ->where('id', $id)
            ->first();

        return view('customer.edit', compact('customer'));
    }

    //update
    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        // Validation လုပ်ပြီး Data ကို တစ်ခါတည်း ယူမယ်
        $data = $this->validateCustomer($request, $id);

        DB::transaction(function () use ($id, $data) {

            $customer = Customer::findOrFail($id);

            // Update မလုပ်ခင် Data အဟောင်း
            $oldData = [
                'old_name'    => $customer->name,
                'old_phone'   => $customer->phone,
                'old_address' => $customer->address,
                'old_notes'   => $customer->notes,
            ];

            // Data အသစ်
            $newData = [
                'new_name'    => $data['name'],
                'new_phone'   => $data['phone'] ?? null,
                'new_address' => $data['address'] ?? null,
                'new_notes'   => $data['notes'] ?? null,
            ];

            // Data ပြောင်းလဲမှု ရှိ/မရှိ စစ်မယ်
            $hasChanged =
                $oldData['old_name'] !== $newData['new_name'] ||
                $oldData['old_phone'] !== $newData['new_phone'] ||
                $oldData['old_address'] !== $newData['new_address'] ||
                $oldData['old_notes'] !== $newData['new_notes'];

            // Data ပြောင်းလဲမှသာ History သိမ်းမယ်
            if ($hasChanged) {
                DB::table('customer_histories')->insert([
                    'customer_code' => $customer->customer_code,
                    ...$oldData,
                    ...$newData,
                    'changed_at'    => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            // Farmer Data Update လုပ်မယ်
            $customer->update($data);
        });

        Alert::success(
            'အောင်မြင်ပါသည်',
            '၀ယ်သူအချက်အလက်များကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။'
        );

        return to_route('Customer#list');
    }

    private function validateCustomer($request, $customerId = null)
    {
        return $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'address'   => ['nullable', 'string', 'max:150'],
            'notes'     => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ],
            [
                // Customer Name
                'name.required'     => ':attribute ကို မဖြစ်မနေ ဖြည့်သွင်းပေးပါ။',
                'name.string'       => ':attribute သည် စာသားဖြစ်ရပါမည်။',
                'name.max'          => ':attribute သည် အများဆုံး စာလုံး ၁၅၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

                // Phone
                'phone.string'      => ':attribute သည် စာသားဖြစ်ရပါမည်။',
                'phone.max'         => ':attribute သည် အများဆုံး စာလုံး ၃၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

                // address
                'address.string'    => ':attribute သည် စာသားဖြစ်ရပါမည်။',
                'address.max'       => ':attribute သည် အများဆုံး စာလုံး ၁၅၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

                // Notes
                'notes.string'      => ':attribute သည် စာသားဖြစ်ရပါမည်။',
                'notes.max'         => ':attribute သည် အများဆုံး စာလုံး ၂၀၀၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

                // Status
                'is_active.boolean' => ':attribute သည် မှန်ကန်သော အခြေအနေတန်ဖိုး ဖြစ်ရပါမည်။',
            ]
            , [
                'name'    => 'တောင်သူအမည်',
                'phone'   => 'ဖုန်းနံပါတ်',
                'address' => 'နေရပ်လိပ်စာ',
                'notes'   => 'မှတ်ချက်',
            ]);
    }

    //history
    public function history(Request $request)
    {
        $customers = Customer::select('farmer_code', 'name')
            ->orderBy('name')
            ->get();

        if ($request->filled('customer_code') && $request->filled('changed_date')) {

            $histories = DB::table('customer_histories')
                ->where('customer_code', $request->customer_code)
                ->whereDate('changed_at', $request->changed_date)
                ->orderByDesc('changed_at')
                ->paginate(10);

        } elseif ($request->filled('customer_code')) {

            $histories = DB::table('customer_histories')
                ->where('customer_code', $request->customer_code)
                ->orderByDesc('changed_at')
                ->paginate(10);

        } elseif ($request->filled('changed_date')) {

            $histories = DB::table('customer_histories')
                ->whereDate('changed_at', $request->changed_date)
                ->orderByDesc('changed_at')
                ->paginate(10);

        } else {

            $histories = DB::table('customer_histories')
                ->orderByDesc('changed_at')
                ->paginate(10);
        }

        $histories->withQueryString();

        return view('customer.history', compact('customers', 'histories'));
    }

    //trashList
    public function trashList(Request $request)
    {
        $customer_name = Customer::onlyTrashed()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        if ($request->filled('customer_id') && $request->filled('deleted_date')) {

            $customers = Customer::onlyTrashed()
                ->where('id', $request->customer_id)
                ->whereDate('deleted_at', $request->deleted_date)
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();

        } elseif ($request->filled('customer_id')) {

            $customers = Customer::onlyTrashed()
                ->where('id', $request->customer_id)
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();

        } elseif ($request->filled('deleted_date')) {

            $customers = Customer::onlyTrashed()
                ->whereDate('deleted_at', $request->deleted_date)
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();

        } else {

            $customers = Customer::onlyTrashed()
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();
        }

        return view('customer.trashList', compact('customers', 'customer_name'));
    }

    //restore
    public function restore($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);

        $customer->restore();

        Alert::success(
            'အောင်မြင်ပါသည်',
            'ဖျက်ထားသော ၀ယ်သူအချက်အလက်ကို ပြန်လည်အသုံးပြုနိုင်ပါပြီ။'
        );

        return to_route('Customer#list');
    }

}
