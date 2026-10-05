<?php
namespace App\Http\Controllers\WholeSaleMarket;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CropController extends Controller
{
    //directPage
    public function directPage()
    {
        return view('wholeSaleMarket.crop.create');
    }

    //create
    public function create(Request $request)
    {

        $validated = $request->validate([

            'crop_name'           => [
                'required',
                'string',
                'max:255',
                'unique:crops,crop_name',
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

        Crop::create([

            'client_uuid'         =>
            $request->client_uuid ?? Str::uuid(),

            'crop_name'           =>
            $validated['crop_name'],

            'commission_amount'   =>
            $validated['commission_amount'],

            'unit'                =>
            $validated['unit'],

            'quantity_per_basket' =>
            $validated['quantity_per_basket'],
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'သီးနှံအချက်အလက် ထည့်သွင်းပြီးပါပြီ။'
            );

    }

    //list
    public function list()
    {

        $crops = Crop::orderBy('created_at', 'desc')->get();

        return view('wholeSaleMarket.crop.list', compact('crops'));

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

        return view('wholeSaleMarket.crop.edit', compact('crop'));

    }

    //update
    public function update(Request $request, $id)
    {
        $request->validate([
            'crop_name'           => 'required|string|max:255',

            'commission_amount'   => 'required|numeric|min:0',

            'unit'                => 'required|string|max:255',

            'quantity_per_basket' => 'required|numeric|min:0',
        ], [

            'crop_name.required'           =>
            'သီးနှံအမည် ထည့်ပေးပါ။',

            'crop_name.string'             =>
            'သီးနှံအမည်သည် စာသားဖြစ်ရပါမည်။',

            'commission_amount.required'   =>
            'ပွဲခ ထည့်ပေးပါ။',

            'commission_amount.numeric'    =>
            'ပွဲခသည် ဂဏန်းဖြစ်ရပါမည်။',

            'unit.required'                =>
            'ယူနစ် ထည့်ပေးပါ။',

            'quantity_per_basket.required' =>
            'တစ်တင်းတွင်ရှိသော ယူနစ်အရေအတွက် ထည့်ပေးပါ။',

            'quantity_per_basket.numeric'  =>
            'ယူနစ်အရေအတွက်သည် ဂဏန်းဖြစ်ရပါမည်။',

        ]);

        $crop = Crop::findOrFail($id);

        $crop->update([

            'crop_name'           =>
            $request->crop_name,

            'commission_amount'   =>
            $request->commission_amount,

            'unit'                =>
            $request->unit,

            'quantity_per_basket' =>
            $request->quantity_per_basket,

        ]);

        return response()->json([

            'success' => true,

            'message' =>
            'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

            'data'    => $crop,

        ]);
    }

}
