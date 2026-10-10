<?php
namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;
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
        return view('farmers.edit');
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
            'address'   => ['nullable', 'string', 'max:255'],
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

            // Address
            'address.string'    => ':attribute သည် စာသားဖြစ်ရပါမည်။',
            'address.max'       => ':attribute သည် အများဆုံး စာလုံး ၂၅၅ အထိသာ ဖြည့်သွင်းနိုင်ပါသည်။',

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
