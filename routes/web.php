<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/kalender', function () {
    return view('kalender');
})->name('kalender');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/lijst', function () {
    return view('lijst');
})->name('lijst');

Route::post('/aanmelden', [RequestController::class, 'store'])->name('form.submit');

Route::get('/requestform', function () {
    return view('requestform');
})->name('requestform');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard')->middleware('role:beheerder');
