<?php
namespace App\Http\Controllers\WholeSaleMarket;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CropController extends Controller
{
    //directPage
    public function directPage()
    {
        return view('wholeSaleMarket.crop.create');
    }

    //create
    // public function create(Request $request)
    // {

    //     $validated = $request->validate([

    //         'crop_name'           => [
    //             'required',
    //             'string',
    //             'max:255',
    //             'unique:crops,crop_name',
    //         ],

    //         'commission_amount'   => [
    //             'required',
    //             'integer',
    //             'min:0',
    //         ],

    //         'unit'                => [
    //             'required',
    //             'string',
    //             'in:viss,pound',
    //         ],

    //         'quantity_per_basket' => [
    //             'required',
    //             'integer',
    //             'min:1',
    //         ],
    //     ], [

    //         'crop_name.required'           =>
    //         'သီးနှံအမည်ထည့်သွင်းရန် လိုအပ်ပါသည်။',

    //         'crop_name.string'             =>
    //         'သီးနှံအမည်သည် စာသားဖြစ်ရပါမည်။',

    //         'commission_amount.required'   =>
    //         'ပွဲခထည့်သွင်းရန် လိုအပ်ပါသည်။',

    //         'commission_amount.integer'    =>
    //         'ပွဲခသည် ကိန်းပြည့်ဖြစ်ရပါမည်။',

    //         'commission_amount.min'        =>
    //         'ပွဲခသည် 0 ထက်ငယ်၍ မရပါ။',

    //         'unit.required'                =>
    //         'ယူနစ်ရွေးချယ်ရန် လိုအပ်ပါသည်။',

    //         'unit.in'                      =>
    //         'ရွေးချယ်ထားသော ယူနစ် မမှန်ကန်ပါ။',

    //         'quantity_per_basket.required' =>
    //         'တစ်တင်းပါ အရေအတွက်ထည့်ရန် လိုအပ်ပါသည်။',

    //         'quantity_per_basket.integer'  =>
    //         'တစ်တင်းပါ အရေအတွက်သည် ကိန်းပြည့်ဖြစ်ရပါမည်။',

    //         'quantity_per_basket.min'      =>
    //         'တစ်တင်းပါ အရေအတွက်သည် 1 ထက်ငယ်၍ မရပါ။',
    //     ]);

    //     Crop::create([

    //         'client_uuid'         =>
    //         $request->client_uuid ?? Str::uuid(),

    //         'crop_name'           =>
    //         $validated['crop_name'],

    //         'commission_amount'   =>
    //         $validated['commission_amount'],

    //         'unit'                =>
    //         $validated['unit'],

    //         'quantity_per_basket' =>
    //         $validated['quantity_per_basket'],
    //     ]);

    //     return redirect()
    //         ->back()
    //         ->with(
    //             'success',
    //             'သီးနှံအချက်အလက် ထည့်သွင်းပြီးပါပြီ။'
    //         );

    // }

    //v1

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

            'crop_name.unique'             =>
            'ဤသီးနှံအမည်သည် ရှိပြီးသားဖြစ်ပါသည်။',

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

        // ==========================================
        // Client UUID
        // ==========================================

        $clientUuid = $request->client_uuid ?? (string) Str::uuid();

        // ==========================================
        // Create Crop
        // ==========================================

        $crop = Crop::create([

            'client_uuid'         =>
            $clientUuid,

            'crop_name'           =>
            $validated['crop_name'],

            'commission_amount'   =>
            $validated['commission_amount'],

            'unit'                =>
            $validated['unit'],

            'quantity_per_basket' =>
            $validated['quantity_per_basket'],
        ]);

        // ==========================================
        // JSON Response
        // ==========================================

        return response()->json([

            'success' => true,

            'status'  => 'success',

            'message' =>
            'သီးနှံအချက်အလက် ထည့်သွင်းပြီးပါပြီ။',

            'data'    => $crop,

        ], 201);
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

        $crop = Crop::select('id', 'client_uuid', 'crop_name', 'commission_amount', 'unit', 'quantity_per_basket')
            ->where('id', $id)
            ->first();

        return view('wholeSaleMarket.crop.edit', compact('crop'));

    }

    //update
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'crop_name'           => 'required|string|max:255',

    //         'commission_amount'   => 'required|numeric|min:0',

    //         'unit'                => 'required|string|max:255',

    //         'quantity_per_basket' => 'required|numeric|min:0',
    //     ], [

    //         'crop_name.required'           =>
    //         'သီးနှံအမည် ထည့်ပေးပါ။',

    //         'crop_name.string'             =>
    //         'သီးနှံအမည်သည် စာသားဖြစ်ရပါမည်။',

    //         'commission_amount.required'   =>
    //         'ပွဲခ ထည့်ပေးပါ။',

    //         'commission_amount.numeric'    =>
    //         'ပွဲခသည် ဂဏန်းဖြစ်ရပါမည်။',

    //         'unit.required'                =>
    //         'ယူနစ် ထည့်ပေးပါ။',

    //         'quantity_per_basket.required' =>
    //         'တစ်တင်းတွင်ရှိသော ယူနစ်အရေအတွက် ထည့်ပေးပါ။',

    //         'quantity_per_basket.numeric'  =>
    //         'ယူနစ်အရေအတွက်သည် ဂဏန်းဖြစ်ရပါမည်။',

    //     ]);

    //     $crop = Crop::findOrFail($id);

    //     $crop->update([

    //         'crop_name'           =>
    //         $request->crop_name,

    //         'commission_amount'   =>
    //         $request->commission_amount,

    //         'unit'                =>
    //         $request->unit,

    //         'quantity_per_basket' =>
    //         $request->quantity_per_basket,

    //     ]);

    //     return response()->json([

    //         'success' => true,

    //         'message' =>
    //         'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

    //         'data'    => $crop,

    //     ]);
    // }

    //v1
    // public function update(Request $request, $id)
    // {
    //     $request->validate([

    //         'crop_name'           =>
    //         'required|string|max:255',

    //         'commission_amount'   =>
    //         'required|numeric|min:0',

    //         'unit'                =>
    //         'required|string|max:255',

    //         'quantity_per_basket' =>
    //         'required|numeric|min:0',

    //     ], [

    //         'crop_name.required'           =>
    //         'သီးနှံအမည် ထည့်ပေးပါ။',

    //         'crop_name.string'             =>
    //         'သီးနှံအမည်သည် စာသားဖြစ်ရပါမည်။',

    //         'crop_name.max'                =>
    //         'သီးနှံအမည်သည် အလွန်ရှည်နေပါသည်။',

    //         'commission_amount.required'   =>
    //         'ပွဲခ ထည့်ပေးပါ။',

    //         'commission_amount.numeric'    =>
    //         'ပွဲခသည် ဂဏန်းဖြစ်ရပါမည်။',

    //         'commission_amount.min'        =>
    //         'ပွဲခသည် 0 ထက်ငယ်၍ မရပါ။',

    //         'unit.required'                =>
    //         'ယူနစ် ထည့်ပေးပါ။',

    //         'unit.string'                  =>
    //         'ယူနစ်သည် စာသားဖြစ်ရပါမည်။',

    //         'quantity_per_basket.required' =>
    //         'တစ်တင်းတွင်ရှိသော ယူနစ်အရေအတွက် ထည့်ပေးပါ။',

    //         'quantity_per_basket.numeric'  =>
    //         'ယူနစ်အရေအတွက်သည် ဂဏန်းဖြစ်ရပါမည်။',

    //         'quantity_per_basket.min'      =>
    //         'ယူနစ်အရေအတွက်သည် 0 ထက်ငယ်၍ မရပါ။',

    //     ]);

    //     // ==========================================
    //     // Find Crop
    //     // ==========================================

    //     $crop = Crop::findOrFail($id);

    //     // ==========================================
    //     // Duplicate Crop Name
    //     // ==========================================

    //     $duplicateCrop = Crop::where(
    //         'crop_name',
    //         $request->crop_name
    //     )
    //         ->where(
    //             'id',
    //             '!=',
    //             $crop->id
    //         )
    //         ->first();

    //     if ($duplicateCrop) {

    //         return response()->json([

    //             'success' =>
    //             false,

    //             'status'  =>
    //             'duplicate',

    //             'message' =>
    //             'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

    //         ], 422);
    //     }

    //     // ==========================================
    //     // Update
    //     // ==========================================

    //     $crop->update([

    //         'crop_name'           =>
    //         $request->crop_name,

    //         'commission_amount'   =>
    //         $request->commission_amount,

    //         'unit'                =>
    //         $request->unit,

    //         'quantity_per_basket' =>
    //         $request->quantity_per_basket,

    //     ]);

    //     // ==========================================
    //     // Refresh
    //     // ==========================================

    //     $crop->refresh();

    //     // ==========================================
    //     // Response
    //     // ==========================================

    //     return response()->json([

    //         'success' =>
    //         true,

    //         'status'  =>
    //         'success',

    //         'message' =>
    //         'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

    //         'data'    =>
    //         $crop,

    //     ]);
    // }

    //v2

    // public function update(Request $request, $id)
    // {
    //     // =========================================================
    //     // Validation
    //     // =========================================================

    //     // $request->validate([

    //     //     'crop_name'           => [
    //     //         'required',
    //     //         'string',
    //     //         'max:255',

    //     //         Rule::unique('crops', 'crop_name')
    //     //             ->ignore($id),
    //     //     ],

    //     //     'commission_amount'   =>
    //     //     'required|numeric|min:0',

    //     //     'unit'                =>
    //     //     'required|string|max:255',

    //     //     'quantity_per_basket' =>
    //     //     'required|numeric|min:0',

    //     // ], [

    //     //     'crop_name.required'           =>
    //     //     'သီးနှံအမည် ထည့်ပေးပါ။',

    //     //     'crop_name.string'             =>
    //     //     'သီးနှံအမည်သည် စာသားဖြစ်ရပါမည်။',

    //     //     'crop_name.max'                =>
    //     //     'သီးနှံအမည်သည် အလွန်ရှည်နေပါသည်။',

    //     //     'crop_name.unique'             =>
    //     //     'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

    //     //     'commission_amount.required'   =>
    //     //     'ပွဲခ ထည့်ပေးပါ။',

    //     //     'commission_amount.numeric'    =>
    //     //     'ပွဲခသည် ဂဏန်းဖြစ်ရပါမည်။',

    //     //     'commission_amount.min'        =>
    //     //     'ပွဲခသည် 0 ထက်ငယ်၍ မရပါ။',

    //     //     'unit.required'                =>
    //     //     'ယူနစ် ထည့်ပေးပါ။',

    //     //     'unit.string'                  =>
    //     //     'ယူနစ်သည် စာသားဖြစ်ရပါမည်။',

    //     //     'unit.max'                     =>
    //     //     'ယူနစ်သည် အလွန်ရှည်နေပါသည်။',

    //     //     'quantity_per_basket.required' =>
    //     //     'တစ်တင်းတွင်ရှိသော ယူနစ်အရေအတွက် ထည့်ပေးပါ။',

    //     //     'quantity_per_basket.numeric'  =>
    //     //     'ယူနစ်အရေအတွက်သည် ဂဏန်းဖြစ်ရပါမည်။',

    //     //     'quantity_per_basket.min'      =>
    //     //     'ယူနစ်အရေအတွက်သည် 0 ထက်ငယ်၍ မရပါ။',

    //     // ]);

    //     // =========================================================
    //     // Find Crop
    //     // =========================================================

    //     $crop = Crop::find($id);

    //     dd([
    //         'request_id'        => $id,
    //         'crop_id'           => $crop->id,
    //         'crop_name'         => $crop->crop_name,
    //         'request_crop_name' => $request->input('crop_name'),
    //     ]);

    //     if (! $crop) {

    //         return response()->json([

    //             'success' => false,

    //             'status'  => 'not_found',

    //             'message' =>
    //             'သီးနှံအချက်အလက် မတွေ့ရှိပါ။',

    //         ], 404);
    //     }

    //     // =========================================================
    //     // Duplicate Crop Name
    //     // =========================================================

    //     // $duplicateCrop = Crop::where(
    //     //     'crop_name',
    //     //     $request->crop_name)
    //     //     ->where(
    //     //         'id',
    //     //         '!=',
    //     //         $crop->id
    //     //     )
    //     //     ->first();

    //     // if ($duplicateCrop) {

    //     //     return response()->json([

    //     //         'success' => false,

    //     //         'status'  => 'duplicate',

    //     //         'message' =>
    //     //         'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

    //     //     ], 422);
    //     // }

    //     // =========================================================
    //     // Update Field By Field
    //     // =========================================================

    //     $crop->crop_name =
    //     $request->input('crop_name');

    //     $crop->commission_amount =
    //     $request->input('commission_amount');

    //     $crop->unit =
    //     $request->input('unit');

    //     $crop->quantity_per_basket =
    //     $request->input('quantity_per_basket');

    //     // =========================================================
    //     // Save To Database
    //     // =========================================================

    //     $saved = $crop->save();

    //     // =========================================================
    //     // Save Failed
    //     // =========================================================

    //     if (! $saved) {

    //         return response()->json([

    //             'success' => false,

    //             'status'  => 'update_failed',

    //             'message' =>
    //             'သီးနှံအချက်အလက် သိမ်းဆည်း၍ မရပါ။',

    //         ], 500);
    //     }

    //     // =========================================================
    //     // Refresh From Database
    //     // =========================================================

    //     $crop->refresh();

    //     // =========================================================
    //     // Success Response
    //     // =========================================================

    //     return response()->json([

    //         'success' => true,

    //         'status'  => 'success',

    //         'message' =>
    //         'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

    //         'data'    => [

    //             'id'                  =>
    //             $crop->id,

    //             'crop_name'           =>
    //             $crop->crop_name,

    //             'commission_amount'   =>
    //             $crop->commission_amount,

    //             'unit'                =>
    //             $crop->unit,

    //             'quantity_per_basket' =>
    //             $crop->quantity_per_basket,

    //         ],

    //     ], 200);
    // }

    //v3

    // public function update(Request $request, $id)
    // {
    //     // =========================================================
    //     // Find Crop First
    //     // =========================================================

    //     $crop = Crop::find($id);

    //     if (! $crop) {

    //         return response()->json([

    //             'success' => false,

    //             'status'  => 'not_found',

    //             'message' =>
    //             'သီးနှံအချက်အလက် မတွေ့ရှိပါ။',

    //         ], 404);
    //     }

    //     // =========================================================
    //     // Validation
    //     // =========================================================

    //     $request->validate([

    //         'crop_name'           => [
    //             'required',
    //             'string',
    //             'max:255',

    //             // လက်ရှိ Crop ကို duplicate မစစ်စေရန်
    //             Rule::unique('crops', 'crop_name')
    //                 ->ignore($crop->id),
    //         ],

    //         'commission_amount'   =>
    //         'required|numeric|min:0',

    //         'unit'                =>
    //         'required|string|max:255',

    //         'quantity_per_basket' =>
    //         'required|numeric|min:0',

    //     ], [

    //         'crop_name.required'           =>
    //         'သီးနှံအမည် ထည့်ပေးပါ။',

    //         'crop_name.string'             =>
    //         'သီးနှံအမည်သည် စာသားဖြစ်ရပါမည်။',

    //         'crop_name.max'                =>
    //         'သီးနှံအမည်သည် အလွန်ရှည်နေပါသည်။',

    //         'crop_name.unique'             =>
    //         'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

    //         'commission_amount.required'   =>
    //         'ပွဲခ ထည့်ပေးပါ။',

    //         'commission_amount.numeric'    =>
    //         'ပွဲခသည် ဂဏန်းဖြစ်ရပါမည်။',

    //         'commission_amount.min'        =>
    //         'ပွဲခသည် 0 ထက်ငယ်၍ မရပါ။',

    //         'unit.required'                =>
    //         'ယူနစ် ထည့်ပေးပါ။',

    //         'unit.string'                  =>
    //         'ယူနစ်သည် စာသားဖြစ်ရပါမည်။',

    //         'unit.max'                     =>
    //         'ယူနစ်သည် အလွန်ရှည်နေပါသည်။',

    //         'quantity_per_basket.required' =>
    //         'တစ်တင်းတွင်ရှိသော ယူနစ်အရေအတွက် ထည့်ပေးပါ။',

    //         'quantity_per_basket.numeric'  =>
    //         'ယူနစ်အရေအတွက်သည် ဂဏန်းဖြစ်ရပါမည်။',

    //         'quantity_per_basket.min'      =>
    //         'ယူနစ်အရေအတွက်သည် 0 ထက်ငယ်၍ မရပါ။',

    //     ]);

    //     // =========================================================
    //     // Duplicate Crop Name
    //     // =========================================================
    //     // လက်ရှိ crop မဟုတ်တဲ့ တခြား crop မှာ
    //     // အမည်တူရှိမရှိ စစ်မယ်

    //     $duplicateCrop = Crop::where(
    //         'crop_name',
    //         $request->input('crop_name')
    //     )
    //         ->where('id', '!=', $crop->id)
    //         ->exists();

    //     if ($duplicateCrop) {

    //         return response()->json([

    //             'success' => false,

    //             'status'  => 'duplicate',

    //             'message' =>
    //             'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

    //         ], 422);
    //     }

    //     // =========================================================
    //     // Update Field By Field
    //     // =========================================================

    //     $crop->crop_name =
    //     $request->input('crop_name');

    //     $crop->commission_amount =
    //     $request->input('commission_amount');

    //     $crop->unit =
    //     $request->input('unit');

    //     $crop->quantity_per_basket =
    //     $request->input('quantity_per_basket');

    //     // =========================================================
    //     // Save
    //     // =========================================================

    //     if (! $crop->save()) {

    //         return response()->json([

    //             'success' => false,

    //             'status'  => 'update_failed',

    //             'message' =>
    //             'သီးနှံအချက်အလက် သိမ်းဆည်း၍ မရပါ။',

    //         ], 500);
    //     }

    //     // =========================================================
    //     // Refresh
    //     // =========================================================

    //     $crop->refresh();

    //     // =========================================================
    //     // Success Response
    //     // =========================================================

    //     return response()->json([

    //         'success' => true,

    //         'status'  => 'success',

    //         'message' =>
    //         'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

    //         'data'    => [

    //             'id'                  =>
    //             $crop->id,

    //             'crop_name'           =>
    //             $crop->crop_name,

    //             'commission_amount'   =>
    //             $crop->commission_amount,

    //             'unit'                =>
    //             $crop->unit,

    //             'quantity_per_basket' =>
    //             $crop->quantity_per_basket,

    //         ],

    //     ], 200);
    // }

    //v4

    // public function update(Request $request, $id)
    // {
    //     // =========================================================
    //     // 1. Find Crop
    //     // =========================================================

    //     $crop = Crop::find($id);

    //     if (! $crop) {

    //         return response()->json([

    //             'success' => false,

    //             'status'  => 'not_found',

    //             'message' => 'သီးနှံအချက်အလက် မတွေ့ရှိပါ။',

    //         ], 404);
    //     }

    //     // =========================================================
    //     // 2. Validation
    //     // =========================================================

    //     $request->validate([

    //         'crop_name'           => [
    //             'required',
    //             'string',
    //             'max:255',

    //             Rule::unique('crops', 'crop_name')
    //                 ->ignore($crop->id),
    //         ],

    //         'commission_amount'   =>
    //         'required|numeric|min:0',

    //         'unit'                =>
    //         'required|string|max:255',

    //         'quantity_per_basket' =>
    //         'required|numeric|min:0',

    //     ], [

    //         'crop_name.required'           =>
    //         'သီးနှံအမည် ထည့်ပေးပါ။',

    //         'crop_name.unique'             =>
    //         'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

    //         'commission_amount.required'   =>
    //         'ပွဲခ ထည့်ပေးပါ။',

    //         'commission_amount.numeric'    =>
    //         'ပွဲခသည် ဂဏန်းဖြစ်ရပါမည်။',

    //         'unit.required'                =>
    //         'ယူနစ် ထည့်ပေးပါ။',

    //         'quantity_per_basket.required' =>
    //         'တစ်တင်းတွင်ရှိသော ယူနစ်အရေအတွက် ထည့်ပေးပါ။',

    //     ]);

    //     // =========================================================
    //     // 3. Get New Values
    //     // =========================================================

    //     $cropName =
    //     $request->input('crop_name');

    //     $commissionAmount =
    //     $request->input('commission_amount');

    //     $unit =
    //     $request->input('unit');

    //     $quantityPerBasket =
    //     $request->input('quantity_per_basket');

    //     // =========================================================
    //     // 4. DEBUG
    //     // =========================================================

    //     Log::info('========== CROP UPDATE ==========');

    //     \Log::info('Request ID: ' . $id);

    //     \Log::info('Database Crop ID: ' . $crop->id);

    //     \Log::info('Old Crop Name: ' . $crop->crop_name);

    //     \Log::info('New Crop Name: ' . $cropName);

    //     \Log::info('Old Commission: ' . $crop->commission_amount);

    //     \Log::info('New Commission: ' . $commissionAmount);

    //     \Log::info('Old Unit: ' . $crop->unit);

    //     \Log::info('New Unit: ' . $unit);

    //     \Log::info(
    //         'Old Quantity: ' .
    //         $crop->quantity_per_basket);

    //     \Log::info(
    //         'New Quantity: ' .
    //         $quantityPerBasket);

    //     // =========================================================
    //     // 5. Update
    //     // =========================================================

    //     $crop->crop_name =
    //         $cropName;

    //     $crop->commission_amount =
    //         $commissionAmount;

    //     $crop->unit =
    //         $unit;

    //     $crop->quantity_per_basket =
    //         $quantityPerBasket;

    //     // =========================================================
    //     // 6. Check Dirty
    //     // =========================================================

    //     \Log::info(
    //         'Dirty Data:',
    //         $crop->getDirty()
    //     );

    //     // =========================================================
    //     // 7. Save
    //     // =========================================================

    //     $saved = $crop->save();

    //     \Log::info(
    //         'Save Result: ' .
    //         ($saved ? 'TRUE' : 'FALSE')
    //     );

    //     // =========================================================
    //     // 8. Refresh
    //     // =========================================================

    //     $crop->refresh();

    //     \Log::info(
    //         'After Save:',
    //         $crop->toArray()
    //     );

    //     \Log::info('=================================');

    //     // =========================================================
    //     // 9. Response
    //     // =========================================================

    //     return response()->json([

    //         'success' => true,

    //         'status'  => 'success',

    //         'message' =>
    //         'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

    //         'debug'   => [

    //             'request_id'  =>
    //             $id,

    //             'database_id' =>
    //             $crop->id,

    //             'saved'       =>
    //             $saved,

    //             'data'        => [

    //                 'crop_name'           =>
    //                 $crop->crop_name,

    //                 'commission_amount'   =>
    //                 $crop->commission_amount,

    //                 'unit'                =>
    //                 $crop->unit,

    //                 'quantity_per_basket' =>
    //                 $crop->quantity_per_basket,

    //             ],

    //         ],

    //     ], 200);
    // }

    //v5
    public function update(Request $request, $id)
    {
        $crop = Crop::find($id);

        if (! $crop) {

            return response()->json([
                'success' => false,
                'message' => 'သီးနှံအချက်အလက် မတွေ့ရှိပါ။',
            ], 404);
        }

        $validated = $request->validate([

            'crop_name'           => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'crops',
                    'crop_name'
                )->ignore($crop->id),
            ],

            'commission_amount'   =>
            'required|numeric|min:0',

            'unit'                =>
            'required|string|max:255',

            'quantity_per_basket' =>
            'required|numeric|min:0',

        ], [

            'crop_name.required'           =>
            'သီးနှံအမည် ထည့်ပေးပါ။',

            'crop_name.unique'             =>
            'ဤသီးနှံအမည်ကို အခြားသီးနှံတွင် အသုံးပြုထားပြီးဖြစ်ပါသည်။',

            'commission_amount.required'   =>
            'ပွဲခ ထည့်ပေးပါ။',

            'commission_amount.numeric'    =>
            'ပွဲခသည် ဂဏန်းဖြစ်ရပါမည်။',

            'unit.required'                =>
            'ယူနစ် ထည့်ပေးပါ။',

            'quantity_per_basket.required' =>
            'တစ်တင်းတွင်ရှိသော ယူနစ်အရေအတွက် ထည့်ပေးပါ။',

        ]);

        // ==========================================
        // Update
        // ==========================================

        $crop->update([

            'crop_name'           =>
            $validated['crop_name'],

            'commission_amount'   =>
            $validated['commission_amount'],

            'unit'                =>
            $validated['unit'],

            'quantity_per_basket' =>
            $validated['quantity_per_basket'],

        ]);

        // ==========================================
        // Refresh
        // ==========================================

        $crop->refresh();

        return response()->json([

            'success' => true,

            'status'  => 'success',

            'message' =>
            'သီးနှံအချက်အလက် ပြင်ဆင်ပြီးပါပြီ။',

            'data'    => [

                'id'                  =>
                $crop->id,

                'server_id'           =>
                $crop->id,

                'client_uuid'         =>
                $crop->client_uuid,

                'crop_name'           =>
                $crop->crop_name,

                'commission_amount'   =>
                $crop->commission_amount,

                'unit'                =>
                $crop->unit,

                'quantity_per_basket' =>
                $crop->quantity_per_basket,

                'updated_at'          =>
                $crop->updated_at,

            ],

        ], 200);
    }

}
