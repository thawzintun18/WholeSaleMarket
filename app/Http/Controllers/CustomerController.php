<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

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
}
