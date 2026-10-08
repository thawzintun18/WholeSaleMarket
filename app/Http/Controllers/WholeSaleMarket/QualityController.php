<?php
namespace App\Http\Controllers\WholeSaleMarket;

use App\Http\Controllers\Controller;
use App\Models\Crop;

class QualityController extends Controller
{
    //directPage
    public function directPage($id)
    {

        $crop_name = Crop::where('id', $id)
            ->value('crop_name');

        return view('wholeSaleMarket.cropQuality.create', compact('crop_name'));
    }

    //list
    public function list()
    {
        return view('wholeSaleMarket.cropQuality.list');
    }
}
