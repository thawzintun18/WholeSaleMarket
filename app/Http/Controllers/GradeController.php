<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Crop;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of grades.
     */
    public function index(Request $request)
    {
        // $query = Grade::with('crop');

        // // Search
        // if ($request->filled('search')) {
        //     $search = $request->search;

        //     $query->where('grade_name', 'like', "%{$search}%");
        // }

        // // Crop Filter
        // if ($request->filled('crop_id')) {
        //     $query->where('crop_id', $request->crop_id);
        // }

        // $grades = $query
        //     ->latest()
        //     ->paginate(10)
        //     ->withQueryString();

        // $crops = Crop::orderBy('crop_name')->get();

        return view('grades.index');
    }


    /**
     * Store a newly created grade.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_id' => [
                'required',
                'exists:crops,id',
            ],

            'grade_name' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        // Prevent duplicate grade for same crop
        $exists = Grade::where('crop_id', $validated['crop_id'])
            ->where('grade_name', $validated['grade_name'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'grade_name' => 'This grade already exists for the selected crop.'
                ]);
        }

        Grade::create($validated);

        return redirect()
            ->route('grades.index')
            ->with('success', 'Grade created successfully.');
    }


    /**
     * Show the form for editing the specified grade.
     */
    public function edit(Grade $grade)
    {
        $crops = Crop::orderBy('crop_name')->get();

        return view('grades.edit', compact(
            'grade',
            'crops'
        ));
    }

    public function create()
    {
        // $crops = Crop::orderBy('crop_name')->get();

        return view('grades.create');
    }


    /**
     * Update the specified grade.
     */
    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'crop_id' => [
                'required',
                'exists:crops,id',
            ],

            'grade_name' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        // Prevent duplicate grade
        $exists = Grade::where('crop_id', $validated['crop_id'])
            ->where('grade_name', $validated['grade_name'])
            ->where('id', '!=', $grade->id)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors([
                    'grade_name' => 'This grade already exists for the selected crop.'
                ]);
        }

        $grade->update($validated);

        return redirect()
            ->route('grades.index')
            ->with('success', 'Grade updated successfully.');
    }


    /**
     * Remove the specified grade.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()
            ->route('grades.index')
            ->with('success', 'Grade deleted successfully.');
    }
}
