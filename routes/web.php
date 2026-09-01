<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RideController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/profiel', function () {
    return view('profiel');
})->name('profiel')->middleware('auth');

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
/**
 * Routes voor de rides.
 */
Route::get('/ritten', [RideController::class, 'index'])->name('ritten.index');
Route::get('/ritten/melden', [RideController::class, 'create'])->name('ritten.create');
Route::get('/ritten/{id}', [RideController::class, 'show'])->name('ritten.show');
Route::post('/ritten', [RideController::class, 'store'])->name('ritten.store');
Route::post('/ritten/joinRide/{id}', [RideController::class, 'joinRide'])->name('ritten.join');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// De overzichtspagina waar je naartoe wordt gestuurd na het succesvol aanmelden
// Route::get('/ritten', function () {
//     return "Hier komt straks het overzicht van alle ritten!";
// })->name('rides.index');
