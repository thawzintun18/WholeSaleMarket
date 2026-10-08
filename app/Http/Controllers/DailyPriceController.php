<?php

namespace App\Http\Controllers;

class DailyPriceController extends Controller
{
    public function index()
    {
        return view('daily-prices.index');
    }

    public function create()
    {
        return view('daily-prices.create');
    }

    public function edit($id)
    {
        return view('daily-prices.edit');
    }
}
