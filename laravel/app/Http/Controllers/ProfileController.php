<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ProfileController extends Controller {
    public function update(Request $request) {
        $user = Auth::user();
        try {
            $request->validate([
                'name' => [
                    'required',
                    'string',
                    'max:28',
                    Rule::unique('users')->ignore($user->id), // Ignore current user
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    Rule::unique('users')->ignore($user->id), // Ignore current user
                ],
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withInput($request->input())->withErrors($e->errors());
        }
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        return redirect('profile')->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request) {
        $user = Auth::user();
        $request->validate([
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user->password = bcrypt($request->input('password'));
        $user->save();
        return redirect('profile')->with('success', 'Password updated successfully');
    }
}