<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller {
    public function create(Request $request) {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:28|unique:users',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:8|confirmed'
            ]);

            $user = User::create($data);

            auth()->login($user);

            return redirect('/home');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->errors());
        }
    }
}
