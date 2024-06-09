<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $remember = false)) {
            $request->session()->regenerate();
            return redirect('/profile');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
