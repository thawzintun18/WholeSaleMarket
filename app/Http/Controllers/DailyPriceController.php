<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\DailyPrice;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DailyPriceController extends Controller
{

    //directPage
    public function directPage()
    {

        $crops = Crop::select('id', 'crop_name')->get();

        return view('daily-prices.create', compact('crops'));
    }

    public function getGrades($id)
    {
        $grades = Grade::where('crop_id', $id)
            ->whereNull('deleted_at')
            ->select('id', 'crop_id', 'grade_name')
            ->orderBy('grade_name')
            ->get();

        return response()->json($grades);

    }

    //create

    public function create(Request $request)
    {
        $validated = $request->validate([
            'price_date'                   => ['required', 'date_format:Y-m-d'],
            'grades'                       => ['required', 'array', 'min:1'],
            'grades.*.crop_id'             => ['required', 'integer', 'exists:crops,id'],
            'grades.*.grade_id'            => ['required', 'integer', 'exists:grades,id'],
            'grades.*.reference_buy_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999999.99',
            ],
        ], [
            'price_date.required'                   => 'ဈေးနှုန်းသတ်မှတ်သည့်နေ့ကို ရွေးချယ်ပါ။',
            'grades.required'                       => 'ဈေးနှုန်းအချက်အလက် အနည်းဆုံးတစ်ခု ထည့်ပါ။',
            'grades.*.crop_id.required'             => 'သီးနှံအမည်ကို ရွေးချယ်ပါ။',
            'grades.*.grade_id.required'            => 'သီးနှံအရည်အသွေးကို ရွေးချယ်ပါ။',
            'grades.*.reference_buy_price.required' => 'ဝယ်ဈေးကို ထည့်သွင်းပါ။',
            'grades.*.reference_buy_price.numeric'  => 'ဝယ်ဈေးသည် ကိန်းဂဏန်းဖြစ်ရပါမည်။',
            'grades.*.reference_buy_price.min'      => 'ဝယ်ဈေးသည် သုညထက် မငယ်ရပါ။',
        ]);

        $priceDate = $validated['price_date'];
        $grades    = $validated['grades'];

        $usedCombinations = [];

        foreach ($grades as $index => $row) {

            $cropId  = $row['crop_id'];
            $gradeId = $row['grade_id'];

            // Crop နှင့် Grade ကိုက်ညီမှု စစ်ဆေးခြင်း
            $validGrade = Grade::where('id', $gradeId)
                ->where('crop_id', $cropId)
                ->exists();

            if (! $validGrade) {
                Alert::error(
                    'အမှားဖြစ်နေပါသည်',
                    'ရွေးချယ်ထားသော သီးနှံနှင့် အရည်အသွေး မကိုက်ညီပါ။'
                );

                return redirect()->back()->withInput();
            }

            // Form ထဲတွင် Crop + Grade ထပ်နေခြင်း စစ်ဆေးခြင်း
            $combination = $cropId . '-' . $gradeId;

            if (isset($usedCombinations[$combination])) {
                Alert::error(
                    'အမှားဖြစ်နေပါသည်',
                    'သီးနှံနှင့် အရည်အသွေး တူညီသော Row ရှိပြီးသားဖြစ်သည်။'
                );

                return redirect()->back()->withInput();
            }

            $usedCombinations[$combination] = true;

            // ထိုနေ့အတွက် ဈေးနှုန်း ရှိပြီးသားလား စစ်ဆေးခြင်း
            $exists = DailyPrice::withTrashed()
                ->where('price_date', $priceDate)
                ->where('crop_id', $cropId)
                ->where('grade_id', $gradeId)
                ->exists();

            if ($exists) {
                Alert::error(
                    'အမှားဖြစ်နေပါသည်',
                    'ဤနေ့အတွက် သီးနှံနှင့် အရည်အသွေး၏ ဈေးနှုန်း မှတ်တမ်းရှိပြီးသားဖြစ်သည်။'
                );

                return redirect()->back()->withInput();
            }
        }

        // အားလုံးစစ်ဆေးပြီးမှ Database ထဲသို့ သိမ်းဆည်းမည်
        try {
            DB::transaction(function () use ($grades, $priceDate) {

                foreach ($grades as $row) {
                    DailyPrice::create([
                        'price_date'          => $priceDate,
                        'crop_id'             => $row['crop_id'],
                        'grade_id'            => $row['grade_id'],
                        'reference_buy_price' => $row['reference_buy_price'],
                    ]);
                }
            });

        } catch (\Throwable $e) {

            report($e);

            Alert::error(
                'အမှားဖြစ်နေပါသည်',
                'ဈေးနှုန်းများကို သိမ်းဆည်းရာတွင် အမှားဖြစ်နေပါသည်။ ထပ်မံကြိုးစားပါ။'
            );

            return redirect()->back()->withInput();
        }

        Alert::success(
            'အောင်မြင်ပါသည်',
            'နေ့စဉ်သီးနှံဈေးနှုန်းများကို အောင်မြင်စွာ စာရင်းသွင်းပြီးပါပြီ။'
        );

        return redirect()->route('DailyPrice#list');
    }

    //list
    public function list()
    {
        return view('daily-prices.index');
    }

}
