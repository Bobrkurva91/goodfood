<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('courier.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('courier')->attempt($credentials)) {
            return redirect()->route('courier.dashboard');
        }

        return back()->withErrors(['email' => 'Неверные учетные данные']);
    }

    public function logout()
    {
        Auth::guard('courier')->logout();
        return redirect('/');
    }
}
