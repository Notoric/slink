<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', function () {
    if (!auth()->check()) {
        return view('register');
    }
    return view('profile');
});

Route::post('/register', [RegisterController::class, 'create']);

Route::get('/login', function () {
    if (!auth()->check()) {
        return view('login');
    }
    return view('profile');
});

Route::post('/login', [LoginController::class, 'login']);

Route::get('/logout', function () {
    auth()->logout();
    return redirect('/home');
});

Route::get('/profile', function () {
    if (!auth()->check()) {
        return redirect('/home');
    }
    return view('profile');
});

Route::get('/home', function () {
    if (!auth()->check()) {
        return view('home');
    }
    return view('home');
});