<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CropController extends Controller
{
    //directPage
    public function directPage()
    {
        return view('crops.create');
    }

    //create
    public function create(Request $request)
    {

        $this->CheckData($request);

        $data = $this->GetData($request);

        Crop::create($data);

        Alert::success('အောင်မြင်ပါသည်', 'သီးနှံ အချက်အလက်များကို အောင်မြင်စွာ စာရင်းသွင်းပြီးပါပြီ။');

        return back();
    }

    //update
    public function update(Request $request, $id)
    {
        $request['id'] = $id;

        $this->CheckData($request);

        $data = $this->GetData($request);

        Crop::where('id', $id)->update($data);

        Alert::success('အောင်မြင်ပါသည်', 'သီးနှံအချက်အလက်များကို အောင်မြင်စွာပြင်ဆင်ပြီးပါပြီ။');

        return to_route('Crop#list');

    }

    //CheckData
    private function CheckData($request)
    {
        $validated = $request->validate([

            'crop_name'           => [
                'required',
                'string',
                'max:255',
                'unique:crops,crop_name,' . $request->id,
                'regex:/.*[\p{L}].*/u',
            ],

            'commission_amount'   => [
                'required',
                'integer',
                'min:0',
            ],

            'unit'                => [
                'required',
                'string',
                'in:viss,pound',
            ],

            'quantity_per_basket' => [
                'required',
                'integer',
                'min:1',
            ],

        ], [

            'crop_name.required'           =>
            'သီးနှံအမည်ထည့်သွင်းရန် လိုအပ်ပါသည်။',

            'crop_name.string'             =>
            'သီးနှံအမည်သည် စာသားဖြစ်ရပါမည်။',

            'crop_name.unique'             =>
            'ဤသီးနှံအမည်သည် ရှိပြီးသားဖြစ်ပါသည်။',

            'crop_name.regex'              =>
            'သီးနှံအမည်တွင် စာလုံး အနည်းဆုံးတစ်လုံး ပါဝင်ရပါမည်။',

            'commission_amount.required'   =>
            'ပွဲခထည့်သွင်းရန် လိုအပ်ပါသည်။',

            'commission_amount.integer'    =>
            'ပွဲခသည် ကိန်းပြည့်ဖြစ်ရပါမည်။',

            'commission_amount.min'        =>
            'ပွဲခသည် 0 ထက်ငယ်၍ မရပါ။',

            'unit.required'                =>
            'ယူနစ်ရွေးချယ်ရန် လိုအပ်ပါသည်။',

            'unit.in'                      =>
            'ရွေးချယ်ထားသော ယူနစ် မမှန်ကန်ပါ။',

            'quantity_per_basket.required' =>
            'တစ်တင်းပါ အရေအတွက်ထည့်ရန် လိုအပ်ပါသည်။',

            'quantity_per_basket.integer'  =>
            'တစ်တင်းပါ အရေအတွက်သည် ကိန်းပြည့်ဖြစ်ရပါမည်။',

            'quantity_per_basket.min'      =>
            'တစ်တင်းပါ အရေအတွက်သည် 1 ထက်ငယ်၍ မရပါ။',
        ]);

    }

    //GetData
    private function GetData($request)
    {
        return [
            'crop_name'           => $request->crop_name,
            'commission_amount'   => $request->commission_amount,
            'unit'                => $request->unit,
            'quantity_per_basket' => $request->quantity_per_basket,
        ];
    }

    //list
    public function list()
    {

        $crops = Crop::when(request('searchData'), function ($query) {
            $query->whereAny([
                'crop_name',
                'commission_amount',
                'unit',
                'quantity_per_basket',
            ], 'like', '%' . request('searchData') . '%');
        })
            ->orderBy('created_at', 'desc')->paginate(5);

        return view('crops.index', compact('crops'));
    }

    //delete
    public function delete($id)
    {
        $crop = Crop::findOrFail($id);

        $crop->delete();

        return back();

    }

    //edit
    public function edit($id)
    {
        $crop = Crop::select('id', 'crop_name', 'commission_amount', 'unit', 'quantity_per_basket')
            ->where('id', $id)
            ->first();

        return view('crops.edit', compact('crop'));
    }

}
