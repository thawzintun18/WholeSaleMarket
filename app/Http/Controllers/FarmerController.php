<?php

namespace App\Http\Controllers;

class FarmerController extends Controller
{
    public function index()
    {
        return view('farmers.index');
    }

    public function create()
    {
        return view('farmers.create');
    }

    public function edit($id)
    {
        return view('farmers.edit');
    }
}
