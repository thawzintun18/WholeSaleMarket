<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
// use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('authentication.home.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        // ဤနေရာတွင် တခြား Device များကို Logout ဖြစ်စေရန် ထည့်ပါ
        // User ရဲ့ လက်ရှိ password ကို ယူပြီး ကျန်တဲ့ Device တွေကနေ ဖြုတ်ချသည်
        Auth::logoutOtherDevices($request->getPassword());

        // Remember Me နှိပ်ထားလျှင် Session lifetime ကို တိုးပေးခြင်း
        if ($request->boolean('remember')) {
            // ရက် ၃၀ (မိနစ်အားဖြင့် 30 * 24 * 60 = 43200 မိနစ်)
            config(['session.lifetime' => 43200]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
