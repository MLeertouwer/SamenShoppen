<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\SetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

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

Route::get('/dashboard', [AdminDashboardController::class, 'showApprovedUsers'])
    ->name('dashboard')
    ->middleware('role:beheerder');

Route::get('/aanvragen', [AdminDashboardController::class, 'showRequests'])
    ->name('aanvragen')
    ->middleware('role:beheerder');
/**
 * Routes voor de rides.
 */
Route::get('/ritten', [RideController::class, 'index'])->name('ritten.index');
Route::get('/ritten/melden', [RideController::class, 'create'])->name('ritten.create');
Route::get('/ritten/{id}', [RideController::class, 'show'])->name('ritten.show');
Route::post('/ritten', [RideController::class, 'store'])->name('ritten.store');
Route::post('/ritten/joinRide/{id}', [RideController::class, 'joinRide'])->name('ritten.join');

Route::patch('/rides/{ride}/passengers/{membership}', [RideController::class, 'updatePassengerStatus'])
    ->name('rides.passengers.update');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/admin/gebruikers/{user}/goedkeuren', [AdminDashboardController::class, 'approveUser'])->name('user.approve');
Route::post('/admin/gebruikers/{user}/afkeuren', [AdminDashboardController::class, 'rejectUser'])->name('user.reject');
// De overzichtspagina waar je naartoe wordt gestuurd na het succesvol aanmelden
// Route::get('/ritten', function () {
//     return "Hier komt straks het overzicht van alle ritten!";
// })->name('rides.index');
Route::get('/reset-password/{token}', [SetPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [SetPasswordController::class, 'store'])->name('password.update');
Route::get('/home', function () {
    if (! auth()->check()) {
        return redirect()->route('welcome');
    }

    return view('home');
})->name('home');
