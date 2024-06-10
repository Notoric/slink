<?php

use App\Http\Controllers\Link_interactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShortlinkController;

Route::get('/', function () {
    return redirect('/home');
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
    return redirect('home');
});

Route::get('/profile', function () {
    if (!auth()->check()) {
        return redirect('home');
    }
    return view('profile');
});

Route::get('/home', function () {
    if (!auth()->check()) {
        return view('home');
    }
    return view('home');
});

Route::post('/shorten', [ShortlinkController::class, 'create']);

Route::get('/l/{id}/details', [Link_interactionController::class, 'getCountryArray']);

Route::get('/l/{id}', [ShortlinkController::class, 'getDetails']);

Route::get('/{id}', [ShortlinkController::class, 'goto']);