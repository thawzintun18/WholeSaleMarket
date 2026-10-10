<?php
namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $farmers = Farmer::query();

        if ($request->search) {
            $farmers->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('farmer_code', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%');
        }

        if ($request->village) {
            $farmers->where('village', 'like', '%' . $request->village . '%');
        }

        if ($request->status == 'active') {
            $farmers->where('is_active', 1);
        } elseif ($request->status == 'inactive') {
            $farmers->where('is_active', 0);
        }

        $farmers = $farmers->orderBy('id', 'desc')->paginate(10);

        return view('farmers.index', compact('farmers'));
    }

    public function createPage()
    {
        return view('farmers.create');
    }

    public function create(Request $request)
    {
        $data              = $this->validateFarmer($request);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($this->checkDuplicateFarmer($data)) {
            return back()
                ->withInput()
                ->with('warning', 'ဒီအမည် သို့မဟုတ် ဖုန်းနံပါတ်နဲ့ တောင်သူစာရင်း ရှိပြီးသား ဖြစ်နိုင်ပါတယ်။');
        }
        $farmer              = Farmer::create($data);
        $farmer->farmer_code = 'F-' . str_pad(
            (string) $farmer->id,
            6,
            '0',
            STR_PAD_LEFT
        );
        $farmer->save();
        Alert::success('အောင်မြင်ပါသည်', 'ောင်သူစာရင်း သိမ်းဆည်းပြီးပါပြီ။');
        return redirect()
            ->route('farmers#index');
    }

    public function edit($id)
    {
        $farmer = Farmer::select('id', 'farmer_code', 'name', 'phone', 'village', 'notes', 'is_active')
                        ->where('id', $id)
                        ->first();

        return view('farmers.edit', compact('farmer'));
    }


    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        // Validation လုပ်ပြီး Data ကို တစ်ခါတည်း ယူမယ်
        $data = $this->validateFarmer($request, $id);

        DB::transaction(function () use ($id, $data) {

            $farmer = Farmer::findOrFail($id);

            // Update မလုပ်ခင် Data အဟောင်း
            $oldData = [
                'old_name'    => $farmer->name,
                'old_phone'   => $farmer->phone,
                'old_village' => $farmer->village,
                'old_notes'   => $farmer->notes,
            ];

            // Data အသစ်
            $newData = [
                'new_name'    => $data['name'],
                'new_phone'   => $data['phone'] ?? null,
                'new_village' => $data['village'] ?? null,
                'new_notes'   => $data['notes'] ?? null,
            ];

            // Data ပြောင်းလဲမှု ရှိ/မရှိ စစ်မယ်
            $hasChanged =
                $oldData['old_name'] !== $newData['new_name'] ||
                $oldData['old_phone'] !== $newData['new_phone'] ||
                $oldData['old_village'] !== $newData['new_village'] ||
                $oldData['old_notes'] !== $newData['new_notes'];

            // Data ပြောင်းလဲမှသာ History သိမ်းမယ်
            if ($hasChanged) {
                DB::table('farmer_histories')->insert([
                    'farmer_code' => $farmer->farmer_code,
                    ...$oldData,
                    ...$newData,
                    'changed_at'  => now(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            // Farmer Data Update လုပ်မယ်
            $farmer->update($data);
        });

        Alert::success(
            'အောင်မြင်ပါသည်',
            'တောင်သူအချက်အလက်များကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။'
        );

        return to_route('farmers#index');
    }

    public function history(Request $request)
    {
        $farmers = Farmer::select('farmer_code', 'name')
            ->orderBy('name')
            ->get();

        if ($request->filled('farmer_code') && $request->filled('changed_date')) {

            $histories = DB::table('farmer_histories')
                ->where('farmer_code', $request->farmer_code)
                ->whereDate('changed_at', $request->changed_date)
                ->orderByDesc('changed_at')
                ->paginate(10);

        } elseif ($request->filled('farmer_code')) {

            $histories = DB::table('farmer_histories')
                ->where('farmer_code', $request->farmer_code)
                ->orderByDesc('changed_at')
                ->paginate(10);

        } elseif ($request->filled('changed_date')) {

            $histories = DB::table('farmer_histories')
                ->whereDate('changed_at', $request->changed_date)
                ->orderByDesc('changed_at')
                ->paginate(10);

        } else {

            $histories = DB::table('farmer_histories')
                ->orderByDesc('changed_at')
                ->paginate(10);
        }

        $histories->withQueryString();

        return view('farmers.history', compact('farmers', 'histories'));
    }

    public function trashList(Request $request)
    {
        $farmer_name = Farmer::onlyTrashed()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        if ($request->filled('farmer_id') && $request->filled('deleted_date')) {

            $farmers = Farmer::onlyTrashed()
                ->where('id', $request->farmer_id)
                ->whereDate('deleted_at', $request->deleted_date)
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();

        } elseif ($request->filled('farmer_id')) {

            $farmers = Farmer::onlyTrashed()
                ->where('id', $request->farmer_id)
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();

        } elseif ($request->filled('deleted_date')) {

            $farmers = Farmer::onlyTrashed()
                ->whereDate('deleted_at', $request->deleted_date)
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();

        } else {

            $farmers = Farmer::onlyTrashed()
                ->orderByDesc('deleted_at')
                ->paginate(10)
                ->withQueryString();
        }

        return view('farmers.trashList', compact('farmers', 'farmer_name'));
    }

    public function restore($id)
    {
        $farmer = Farmer::onlyTrashed()->findOrFail($id);

        $farmer->restore();

        Alert::success(
            'အောင်မြင်ပါသည်',
            'ဖျက်ထားသော တောင်သူအချက်အလက်ကို ပြန်လည်အသုံးပြုနိုင်ပါပြီ။'
        );

        return to_route('farmers#index');
    }

    //delete
    public function delete($id)
    {
        $crop = Farmer::findOrFail($id);

        $crop->delete();

        return back();
    }

    private function validateFarmer($request, $farmerId = null)
    {
        return $request->validate([
            'name'      => ['required', 'string', 'max:150'],
            'phone'     => ['nullable', 'string', 'max:30'],
            'village'   => ['nullable', 'string', 'max:150'],
            'notes'     => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ],
        [
            // Farmer Name
            'name.required'     => ':attribute ကို မဖြစ်မနေ ဖြည့်သွင်းပေးပါ။',
            'name.string'       => ':attribute သည် စာသားဖြစ်ရပါမည်။',
            'name.max'          => ':attribute သည် အများဆုံး စာလုံး ၁၅၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

            // Phone
            'phone.string'      => ':attribute သည် စာသားဖြစ်ရပါမည်။',
            'phone.max'         => ':attribute သည် အများဆုံး စာလုံး ၃၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

            // Village
            'village.string'    => ':attribute သည် စာသားဖြစ်ရပါမည်။',
            'village.max'       => ':attribute သည် အများဆုံး စာလုံး ၁၅၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

            // Notes
            'notes.string'      => ':attribute သည် စာသားဖြစ်ရပါမည်။',
            'notes.max'         => ':attribute သည် အများဆုံး စာလုံး ၂၀၀၀ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

            // Status
            'is_active.boolean' => ':attribute သည် မှန်ကန်သော အခြေအနေတန်ဖိုး ဖြစ်ရပါမည်။',
        ]
        , [
            'name'    => 'တောင်သူအမည်',
            'phone'   => 'ဖုန်းနံပါတ်',
            'village' => 'ရွာအမည်',
            'address' => 'နေရပ်လိပ်စာ',
            'notes'   => 'မှတ်ချက်',
        ]);
    }
}
