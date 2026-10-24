<?php
namespace App\Http\Controllers;

use App\Models\Crop;

class SaleController extends Controller
{
    //directPage
    public function directPage()
    {

        $crops = Crop::orderBy('crop_name')->get();

        return view('sales.create', compact('crops'));
    }

    //avgPrice
    public function avgPrice($crop_id)
    {

    }
}
