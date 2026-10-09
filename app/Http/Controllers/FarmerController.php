<?php
namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;

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
        $data = $this->validateFarmer($request);
        $data['is_active'] = $request->boolean('is_active', true);
        if ($this->checkDuplicateFarmer($data)) {
            return back()
                ->withInput()
                ->with('warning', 'ဒီအမည် သို့မဟုတ် ဖုန်းနံပါတ်နဲ့ တောင်သူစာရင်း ရှိပြီးသား ဖြစ်နိုင်ပါတယ်။');
        }
        $farmer = Farmer::create($data);
        $farmer->farmer_code = 'F-' . str_pad(
            (string) $farmer->id,
            6,
            '0',
            STR_PAD_LEFT
        );
        $farmer->save();
        return redirect()
            ->route('farmers#index')
            ->with('success', 'တောင်သူစာရင်း သိမ်းဆည်းပြီးပါပြီ။');
    }

    // public function update(Request $request, Farmer $farmer)
    // {
    //     $data = $this->validateFarmer($request, $farmer->id);

    //     $data['is_active'] = $request->boolean('is_active');

    //     $farmer->update($data);

    //     return redirect()
    //         ->route('farmers.index')
    //         ->with('success', 'တောင်သူအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။');
    // }

    public function edit($id)
    {
        return view('farmers.edit');
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
        ], [], [
            'name'    => 'တောင်သူအမည်',
            'phone'   => 'ဖုန်းနံပါတ်',
            'village' => 'ရွာအမည်',
            'address' => 'နေရပ်လိပ်စာ',
            'notes'   => 'မှတ်ချက်',
        ]);
    }

    private function checkDuplicateFarmer($data)
    {
        return Farmer::query()
            ->where(function ($query) use ($data) {
                $query->whereRaw(
                    'LOWER(name) = ?',
                    [mb_strtolower(trim($data['name']))]
                );

                if (!empty($data['phone'])) {
                    $query->orWhere('phone', trim($data['phone']));
                }
            })
            ->exists();
    }
}
