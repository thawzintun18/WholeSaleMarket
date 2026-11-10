<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all(); // လိုအပ်သလို paginate သုံးနိုင်ပါတယ်
        return view('users.index', compact('users'));
    }

    public function createPage()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        // Validation စစ်ဆေးခြင်း
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:staff,admin', // သတ်မှတ်ထားသော Role များသာ
        ]);

        // Database ထဲသို့ ထည့်ခြင်း
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password ကို Hash လုပ်ရန်
            'role' => $request->role, // Staff, Admin, (သို့) Superadmin
        ]);

        Alert::success(
            'အောင်မြင်ပါသည်',
            'User အောင်မြင်စွာ စာရင်းသွင်းပြီးပါပြီ။'
        );

        return redirect()->route('users#index');
    }

    public function delete($id)
    {
        User::find($id)->delete();
        return back();
    }
}
