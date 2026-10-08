<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;

class CropController extends Controller
{
    public function index(Request $request)
    {
        $query = Crop::query();

        if ($request->filled('search')) {
            $query->where(
                'crop_name',
                'like',
                '%' . $request->search . '%'
            );
        }

        $crops = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('crops.index', compact('crops'));
    }


    public function create()
    {
        return view('crops.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_name' => [
                'required',
                'string',
                'max:255',
                'unique:crops,crop_name',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'quantity_per_basket' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);


        Crop::create($validated);

        return redirect()
            ->route('crops.index')
            ->with('success', 'Crop created successfully.');
    }


    public function edit(Crop $crop)
    {
        return view('crops.edit', compact('crop'));
    }


    public function update(Request $request, Crop $crop)
    {
        $validated = $request->validate([
            'crop_name' => [
                'required',
                'string',
                'max:255',
                'unique:crops,crop_name,' . $crop->id,
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'quantity_per_basket' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ]);


        $crop->update($validated);

        return redirect()
            ->route('crops.index')
            ->with('success', 'Crop updated successfully.');
    }


    public function destroy(Crop $crop)
    {
        $crop->delete();

        return redirect()
            ->route('crops.index')
            ->with('success', 'Crop deleted successfully.');
    }
}
