<?php
namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Grade;
use App\Models\GradeHistory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CropQualityController extends Controller
{
    //directPage
    public function directPage()
    {

        $crops = Crop::select('id', 'crop_name')->get();

        return view('grades.create', compact('crops'));
    }

    //create
    public function create(Request $request)
    {
        $validated = $request->validate([
            'grades'              => ['required', 'array', 'min:1'],

            'grades.*.crop_id'    => [
                'required',
                'integer',
                'exists:crops,id',
            ],

            'grades.*.grade_name' => [
                'required',
                'string',
                'max:50',
            ],
        ], [
            'grades.required'              => 'အနည်းဆုံး သီးနှံအဆင့်တစ်ခု ထည့်ပေးပါ။',
            'grades.*.crop_id.required'    => 'သီးနှံအမည်ကို ရွေးချယ်ပေးပါ။',
            'grades.*.crop_id.exists'      => 'ရွေးချယ်ထားသော သီးနှံ မရှိပါ။',
            'grades.*.grade_name.required' => 'အရည်အသွေးအမည်ကို ထည့်ပေးပါ။',
            'grades.*.grade_name.max'      => 'အရည်အသွေးအမည်သည် စာလုံး ၅၀ ထက် မကျော်ရပါ။',
        ]);

        // Form ထဲတွင် သီးနှံတစ်မျိုးအတွက် Grade အမည် ထပ်မရရန်
        $seen = [];

        foreach ($validated['grades'] as $index => $grade) {

            $cropId    = $grade['crop_id'];
            $gradeName = trim($grade['grade_name']);

            // စာလုံးအကြီးအသေး မခွဲဘဲ စစ်ဆေးခြင်း
            $key = $cropId . '|' . mb_strtolower($gradeName);

            if (isset($seen[$key])) {
                Alert::error(
                    'ထပ်နေသော အချက်အလက်',
                    'ဤသီးနှံအတွက် အဆင့်အမည်တူကို ထပ်ထည့်၍ မရပါ။'
                );

                return back()
                    ->withErrors([
                        "grades.$index.grade_name" =>
                        'ဤသီးနှံအတွက် အဆင့်အမည်တူကို ထပ်ထည့်၍ မရပါ။',
                    ])
                    ->withInput();

            }

            $seen[$key] = true;

            // Database ထဲရှိ Grade အမည်ကို စစ်ဆေးခြင်း
            $exists = Grade::where('crop_id', $cropId)
                ->whereRaw('LOWER(grade_name) = ?', [
                    mb_strtolower($gradeName),
                ])
                ->exists();

            if ($exists) {
                Alert::error(
                    'စာရင်းသွင်း၍ မရပါ',
                    'ဤသီးနှံအတွက် အဆင့်အမည် ရှိပြီးသားဖြစ်ပါသည်။'
                );

                return back()
                    ->withErrors([
                        "grades.$index.grade_name" =>
                        'ဤသီးနှံအတွက် အဆင့်အမည် ရှိပြီးသားဖြစ်ပါသည်။',
                    ])
                    ->withInput();

            }
        }

        // Database ထဲသို့ တစ်ခါတည်း သိမ်းဆည်းခြင်း
        DB::transaction(function () use ($validated) {

            foreach ($validated['grades'] as $grade) {

                Grade::create([
                    'crop_id'    => $grade['crop_id'],
                    'grade_name' => trim($grade['grade_name']),
                ]);
            }
        });

        Alert::success(
            'အောင်မြင်ပါသည်',
            'သီးနှံအရည်အသွေးအဆင့်များကို အောင်မြင်စွာ စာရင်းသွင်းပြီးပါပြီ။'
        );

        return to_route('Quality#list');
    }

    //list
    public function list(Request $request)
    {
        $crops = Crop::withTrashed()
            ->join('grades', 'crops.id', '=', 'grades.crop_id')
            ->select('crops.id', 'crops.crop_name')
            ->distinct()
            ->orderBy('crops.crop_name')
            ->get();

        $grades = Grade::leftJoin('crops', 'grades.crop_id', '=', 'crops.id')
            ->select(
                'grades.*',
                'crops.crop_name',
                'crops.id as crop_id'
            )
            ->when($request->filled('crop_id'), function ($query) use ($request) {
                $query->where('grades.crop_id', $request->crop_id);
            })
            ->when(request('searchData'), function ($query) {
                $query->whereAny([
                    'crops.crop_name',
                    'grades.grade_name',
                ], 'like', '%' . request('searchData') . '%');
            })
            ->orderBy('grades.created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('grades.index', compact('grades', 'crops'));
    }

    //delete
    public function delete($id)
    {

        $crop = Grade::findOrFail($id);

        $crop->delete();

        return back();

    }

    //edit
    public function edit($id)
    {

        $grade = Grade::leftJoin('crops', 'grades.crop_id', 'crops.id')
            ->select('grades.id', 'grades.grade_name', 'crops.crop_name', 'crops.id as crop_id')
            ->where('grades.id', $id)
            ->first();

        return view('grades.edit', compact('grade'));

    }

    //update

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'grade_name' => ['required', 'string', 'max:50'],
        ], [
            'grade_name.required' => 'အရည်အသွေးအမည်ကို ထည့်ပေးပါ။',
            'grade_name.max'      => 'အရည်အသွေးအမည်သည် စာလုံး ၅၀ ထက် မကျော်ရပါ။',
        ]);

        $grade     = Grade::findOrFail($id);
        $gradeName = trim($validated['grade_name']);

        // Active Grade ထဲတွင် အမည်တူ ရှိ၊ မရှိ စစ်ဆေးမည်
        $exists = Grade::where('crop_id', $grade->crop_id)
            ->where('id', '!=', $grade->id)
            ->whereRaw('LOWER(grade_name) = ?', [
                mb_strtolower($gradeName),
            ])
            ->exists();

        if ($exists) {
            Alert::error(
                'ပြင်ဆင်၍ မရပါ',
                'ဤသီးနှံအတွက် အရည်အသွေးအမည် ရှိပြီးသားဖြစ်ပါသည်။'
            );

            return back()->withInput();
        }

        // Soft Delete လုပ်ထားသော Grade များနှင့်ပါ စစ်ဆေးမည်
        $trashedExists = Grade::withTrashed()
            ->where('crop_id', $grade->crop_id)
            ->where('id', '!=', $grade->id)
            ->whereRaw('LOWER(grade_name) = ?', [
                mb_strtolower($gradeName),
            ])
            ->exists();

        if ($trashedExists) {
            Alert::error(
                'ပြင်ဆင်၍ မရပါ',
                'ဤအရည်အသွေးအမည်ကို ယခင်က သတ်မှတ်ထားပြီး ဖျက်ထားသော မှတ်တမ်းတွင် ရှိနေပါသည်။ အခြားအမည်တစ်ခုကို အသုံးပြုပါ။'
            );

            return back()->withInput();
        }

        try {
            DB::transaction(function () use ($grade, $gradeName) {

                // အမည်အမှန်တကယ် ပြောင်းလဲမှသာ History သိမ်းမည်
                $oldGradeName = $grade->grade_name;

                if ($oldGradeName === $gradeName) {
                    return;
                }

                // History မှတ်တမ်းကို အရင်သိမ်းမည်
                GradeHistory::create([
                    'grade_id'       => $grade->id,
                    'crop_id'        => $grade->crop_id,
                    'old_grade_name' => $oldGradeName,
                    'new_grade_name' => $gradeName,
                ]);

                // Grade အမည်ကို Update လုပ်မည်
                $grade->update([
                    'grade_name' => $gradeName,
                ]);
            });
        } catch (QueryException $e) {

            // Database Unique Constraint Duplicate Error
            if (($e->errorInfo[1] ?? null) == 1062) {
                Alert::error(
                    'ပြင်ဆင်၍ မရပါ',
                    'ဤသီးနှံအတွက် အရည်အသွေးအမည် တူညီသော မှတ်တမ်းရှိပြီးသားဖြစ်ပါသည်။'
                );

                return back()->withInput();
            }

            throw $e;
        }

        Alert::success(
            'အောင်မြင်ပါသည်',
            'သီးနှံအရည်အသွေးကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။'
        );

        return to_route('Quality#list');
    }

    //trashList
    public function trashList(Request $request)
    {

        $crop_name = Grade::onlyTrashed()
            ->leftJoin('crops', 'grades.crop_id', '=', 'crops.id')
            ->select(
                'crops.crop_name'
            )
            ->distinct()
            ->get();

        $grades = Grade::onlyTrashed()
            ->leftJoin('crops', 'grades.crop_id', '=', 'crops.id')
            ->select(
                'grades.id',
                'grades.grade_name',
                'grades.deleted_at',
                'crops.crop_name'
            )
        // သီးနှံအမည်ဖြင့် ရှာဖွေခြင်း
            ->when($request->filled('crop_name'), function ($query) use ($request) {
                $query->where('crops.crop_name', $request->crop_name);
            })

        // ဖျက်ခဲ့သည့်ရက်စွဲဖြင့် ရှာဖွေခြင်း
            ->when($request->filled('changed_date'), function ($query) use ($request) {
                $query->whereDate('grades.deleted_at', '>=', $request->changed_date);
            })

            ->orderByDesc('grades.deleted_at')
            ->paginate(10);

        return view('grades.trashlist', compact('crop_name', 'grades'));
    }

    //restore
    public function restore($id)
    {

        $grade = Grade::onlyTrashed()->findOrFail($id);

        $grade->restore();

        Alert::success(
            'အောင်မြင်ပါသည်',
            'ဖျက်ထားသော သီးနှံအရည်အသွေးကို ပြန်လည်အသုံးပြုနိုင်ပါပြီ။'
        );

        return to_route('Quality#list');

    }

    //history
    public function history(Request $request)
    {
        $crop_name = DB::table('grade_histories')
            ->join('crops', 'grade_histories.crop_id', '=', 'crops.id')
            ->select(
                'grade_histories.crop_id',
                'crops.crop_name'
            )
            ->distinct()
            ->orderBy('crops.crop_name')
            ->get();

        $histories = DB::table('grade_histories')
            ->leftJoin('crops', 'grade_histories.crop_id', 'crops.id')
            ->select('grade_histories.*', 'crops.id', 'crops.crop_name')
            ->when($request->filled('crop_id'), function ($query) use ($request) {$query->where(function ($q) use ($request) {$q->where('crops.id', $request->crop_id);});})
            ->when($request->filled('changed_date'), function ($query) use ($request) {$query->whereDate('grade_histories.changed_at', '>=', $request->changed_date);})
            ->orderByDesc('grade_histories.changed_at')->paginate(10);

        return view('grades.history', compact('histories', 'crop_name'));
    }

}
