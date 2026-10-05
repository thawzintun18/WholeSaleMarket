<?php
namespace App\Http\Controllers\WholeSaleMarket;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //dashboard
    public function dashboard()
    {
        return view('wholeSaleMarket.dashboard.dashboard');
    }
}
