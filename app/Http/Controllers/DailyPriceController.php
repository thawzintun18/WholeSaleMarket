<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\DailyPrice;
use App\Models\DailyPriceHistory;
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
    public function list(Request $request)
    {
        $today = now()->toDateString();

        // Default ရက်စွဲများ
        $firstDate = $request->input(
            'first_date',
            now()->subDay()->toDateString()
        );

        $secondDate = $request->input(
            'second_date',
            $today
        );

        $query = DailyPrice::leftJoin(
            'crops',
            'daily_prices.crop_id',
            '=',
            'crops.id'
        )->leftJoin(
            'grades',
            'daily_prices.grade_id',
            '=',
            'grades.id'
        )->select(
            'daily_prices.id',
            'daily_prices.crop_id',
            'daily_prices.grade_id',
            'daily_prices.reference_buy_price',
            'daily_prices.price_date',
            'crops.crop_name',
            'grades.grade_name'
        );

        // Search Form ကို Submit လုပ်ထားခြင်း ရှိ/မရှိ စစ်မည်
        $isSearching = $request->has('first_date')
        || $request->has('second_date')
        || $request->has('crop_id')
        || $request->has('grade_name');

        if ($isSearching) {

            // ရက်စွဲအစီအစဉ် မှန်ကန်မှု စစ်မည်
            if ($firstDate > $secondDate) {
                return redirect()
                    ->route('DailyPrice#list')
                    ->with('error', 'ပထမရက်စွဲသည် ဒုတိယရက်စွဲထက် မကျော်လွန်ရပါ။');
            }

            // ရွေးထားသော ရက်စွဲနှစ်ခုကြား ရှာမည်
            $query->whereBetween('daily_prices.price_date', [
                $firstDate,
                $secondDate,
            ]);

        } else {

            // Search မလုပ်ရသေးလျှင် ဒီနေ့ Data များသာ ပြမည်
            $query->whereDate('daily_prices.price_date', $today);
        }

        // သီးနှံ Filter
        if ($request->filled('crop_id')) {
            $query->where(
                'daily_prices.crop_id',
                $request->crop_id
            );
        }

        // Grade Filter
        if ($request->filled('grade_name')) {
            $query->where(
                'grades.grade_name',
                $request->grade_name
            );
        }

        $dailyPrice = $query
            ->orderBy('daily_prices.price_date', 'desc')
            ->orderBy('daily_prices.id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // မနေ့ကနှင့် ဒီနေ့ ရက်စွဲများ
        $yesterday = now()->subDay()->toDateString();
        $today     = now()->toDateString();

        // မနေ့ကနှင့် ဒီနေ့ ဈေးနှုန်းများကို ရယူမည်
        $comparisonPrices = DB::table('daily_prices')
            ->whereIn('price_date', [$yesterday, $today])
            ->select(
                'crop_id',
                'grade_id',
                'price_date',
                'reference_buy_price'
            )
            ->get()
            ->groupBy(function ($item) {
                return $item->crop_id . '_' . $item->grade_id;
            });

        // လက်ရှိစာရင်းထဲက သီးနှံနှင့် Grade အလိုက် နှိုင်းယှဉ်မည်
        $dailyPrice->getCollection()->transform(function ($item) use (
            $comparisonPrices,
            $yesterday,
            $today
        ) {
            $key = $item->crop_id . '_' . $item->grade_id;

            $prices = $comparisonPrices->get($key, collect());

            $yesterdayPrice = $prices
                ->firstWhere('price_date', $yesterday);

            $todayPrice = $prices
                ->firstWhere('price_date', $today);

            $oldPrice = $yesterdayPrice?->reference_buy_price;
            $newPrice = $todayPrice?->reference_buy_price;

            $item->yesterday_price = $oldPrice;
            $item->today_price     = $newPrice;

            if ($oldPrice !== null && $newPrice !== null) {
                $item->price_difference = (float) $newPrice - (float) $oldPrice;

                if ($item->price_difference > 0) {
                    $item->price_status = 'တက်';
                } elseif ($item->price_difference < 0) {
                    $item->price_status = 'ကျ';
                } else {
                    $item->price_status = 'မပြောင်း';
                }
            } else {
                $item->price_difference = null;
                $item->price_status     = 'နှိုင်းယှဉ်ရန် ဈေးမရှိပါ';
            }

            return $item;
        });

        $crop_names = DB::table('crops')
            ->select('id', 'crop_name')
            ->orderBy('crop_name')
            ->get();

        $grade_names = DB::table('daily_prices')
            ->join(
                'grades',
                'daily_prices.grade_id',
                '=',
                'grades.id'
            )
            ->select('grades.grade_name')
            ->distinct()
            ->orderBy('grades.grade_name')
            ->get();

        return view(
            'daily-prices.index',
            compact(
                'dailyPrice',
                'crop_names',
                'grade_names',
                'firstDate',
                'secondDate'
            )
        );
    }

    //delete
    public function delete($id)
    {
        $crop = DailyPrice::findOrFail($id);

        $crop->delete();

        return back();

    }

    //trashList
    public function trashList(Request $request)
    {
        $crop_name = DailyPrice::onlyTrashed()
            ->leftJoin('crops', 'daily_prices.crop_id', '=', 'crops.id')
            ->select(
                'crops.crop_name'
            )
            ->distinct()
            ->get();

        $grade_name = DailyPrice::onlyTrashed()
            ->leftJoin('grades', 'daily_prices.grade_id', '=', 'grades.id')
            ->select(
                'grades.grade_name'
            )
            ->distinct()
            ->get();

        $dailyPrice = DailyPrice::onlyTrashed()
            ->leftJoin(
                'crops',
                'daily_prices.crop_id',
                'crops.id'
            )
            ->leftJoin(
                'grades',
                'daily_prices.grade_id',
                'grades.id'
            )
            ->select(
                'daily_prices.id',
                'daily_prices.crop_id',
                'daily_prices.grade_id',
                'daily_prices.reference_buy_price',
                'daily_prices.price_date',
                'crops.crop_name',
                'grades.grade_name'
            )
            ->when($request->filled('crop_name'), function ($query) use ($request) {
                $query->where('crops.crop_name', $request->crop_name);
            })
            ->when($request->filled('grade_name'), function ($query) use ($request) {
                $query->where('grades.grade_name', $request->grade_name);
            })
            ->when($request->filled('changed_date'), function ($query) use ($request) {
                $query->whereDate('daily_prices.deleted_at', '>=', $request->changed_date);
            })
            ->orderBy(
                'daily_prices.deleted_at',
                'desc'
            )
            ->paginate(10);

        return view('daily-prices.trashList', compact('crop_name', 'grade_name', 'dailyPrice'));

    }

    //restore
    public function restore($id)
    {

        $grade = DailyPrice::onlyTrashed()->findOrFail($id);

        $grade->restore();

        Alert::success(
            'အောင်မြင်ပါသည်',
            'ဖျက်ထားသော နေ့စဉ်သီးနှံဈေးနှုန်းကို ပြန်လည်အသုံးပြုနိုင်ပါပြီ။'
        );

        return to_route('DailyPrice#list');

    }

    //edit
    public function edit($id)
    {

        $dailyPrice = DailyPrice::leftJoin(
            'crops',
            'daily_prices.crop_id',
            'crops.id'
        )
            ->leftJoin(
                'grades',
                'daily_prices.grade_id',
                'grades.id'
            )
            ->select(
                'daily_prices.id',
                'daily_prices.crop_id',
                'daily_prices.grade_id',
                'daily_prices.reference_buy_price',
                'daily_prices.price_date',
                'crops.crop_name',
                'grades.grade_name'
            )
            ->where(
                'daily_prices.id',
                $id
            )
            ->first();

        return view('daily-prices.edit', compact('dailyPrice'));
    }

    //update
    public function update(Request $request, $id)
    {
        // Validate
        $request->validate([
            'reference_buy_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999999.99',
            ],
        ], [
            'reference_buy_price.required' => 'ဝယ်ဈေးနှုန်း ဖြည့်ပေးပါ။',
            'reference_buy_price.numeric'  => 'ဝယ်ဈေးနှုန်းသည် ဂဏန်းဖြစ်ရပါမည်။',
            'reference_buy_price.min'      => 'ဝယ်ဈေးနှုန်းသည် သုညထက် မငယ်ရပါ။',
        ]);

        DB::transaction(function () use ($request, $id) {

            // မူရင်း Daily Price ကို ရယူခြင်း
            $dailyPrice = DailyPrice::findOrFail($id);

            // အရင်ဈေးနှုန်း
            $oldPrice = $dailyPrice->reference_buy_price;

            // ပြင်ဆင်မည့်ဈေးနှုန်း
            $newPrice = $request->reference_buy_price;

            // ဈေးနှုန်းပြောင်းလဲမှသာ History သိမ်းမည်
            if ((float) $oldPrice !== (float) $newPrice) {

                DailyPriceHistory::create([
                    'daily_price_id'          => $dailyPrice->id,
                    'crop_id'                 => $dailyPrice->crop_id,
                    'grade_id'                => $dailyPrice->grade_id,
                    'price_date'              => $dailyPrice->price_date,
                    'old_reference_buy_price' => $oldPrice,
                    'new_reference_buy_price' => $newPrice,
                    'changed_at'              => now(),
                ]);

                // Daily Price ကို Update လုပ်ခြင်း
                $dailyPrice->reference_buy_price = $newPrice;
                $dailyPrice->save();
            }
        });

        Alert::success(
            'အောင်မြင်ပါသည်',
            'သီးနှံဈေးနှုန်းကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။'
        );

        return to_route('DailyPrice#list');

    }

    //history
    public function history(Request $request)
    {

        $dailyPrice = DailyPriceHistory::leftJoin(
            'crops',
            'daily_price_histories.crop_id',
            'crops.id'
        )
            ->leftJoin(
                'grades',
                'daily_price_histories.grade_id',
                'grades.id'
            )
            ->select(
                'daily_price_histories.*',
                'crops.crop_name',
                'grades.grade_name',
            )
            ->when($request->filled('crop_name'), function ($query) use ($request) {
                $query->where('crops.crop_name', $request->crop_name);
            })
            ->when($request->filled('grade_name'), function ($query) use ($request) {
                $query->where('grades.grade_name', $request->grade_name);
            })
            ->when($request->filled('changed_date'), function ($query) use ($request) {
                $query->whereDate('daily_price_histories.changed_at', '>=', $request->changed_date);
            })
            ->orderByDesc(
                'daily_price_histories.changed_at'
            )->paginate(10);

        $crop_name = DailyPriceHistory::leftJoin('crops', 'daily_price_histories.crop_id', 'crops.id')
            ->select('daily_price_histories.crop_id', 'crops.crop_name')
            ->distinct()
            ->orderBy('crops.crop_name')
            ->get();

        $grade_name = DailyPriceHistory::leftJoin('grades', 'daily_price_histories.grade_id', 'grades.id')
            ->select('grades.grade_name')
            ->distinct()
            ->orderBy('grades.grade_name')
            ->get();

        return view('daily-prices.history', compact('dailyPrice', 'crop_name', 'grade_name'));

    }

}
