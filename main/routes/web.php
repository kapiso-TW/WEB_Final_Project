<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('personalinfo');
    }
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 自動去資料庫比對 email 與加密後的 password
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended(route('personalinfo'));
    }

    return back()->withErrors([
        'email' => '帳號或密碼錯誤，請重新輸入。',
    ])->onlyInput('email');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/personalinfo', function () {
        return view('personalinfo');
    })->name('personalinfo');

    Route::get('/matchrec', function () {
        return view('matchrec');
    })->name('matchrec');

    Route::get('/tacticalboard', function () {
        return view('tacticalboard');
    })->name('tacticalboard');
});