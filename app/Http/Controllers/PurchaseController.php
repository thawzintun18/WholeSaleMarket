<?php

namespace App\Http\Controllers;


class PurchaseController extends Controller
{
    public function index()
    {
        return view('purchases.index');
    }

    public function create()
    {
        return view('purchases.create');
    }

    public function edit($id)
    {
        return view('purchases.edit');
    }
}
