<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Middleware\CheckRideChatAccess;
use App\Livewire\RideChat;
use App\Http\Middleware\EnsureHasApprovedMembership;
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
Route::get('/ritten', [RideController::class, 'index'])->name('ritten.index')->middleware(['auth', EnsureHasApprovedMembership::class]);
Route::get('/ritten/melden', [RideController::class, 'create'])->name('ritten.create')->middleware(['auth', EnsureHasApprovedMembership::class]);
Route::get('/ritten/{id}', [RideController::class, 'show'])->name('ritten.show')->middleware(['auth', EnsureHasApprovedMembership::class]);
Route::post('/ritten', [RideController::class, 'store'])->name('ritten.store')->middleware(['auth', EnsureHasApprovedMembership::class]);
Route::post('/ritten/joinRide/{id}', [RideController::class, 'joinRide'])->name('ritten.join')->middleware(['auth', EnsureHasApprovedMembership::class]);

Route::patch('/rides/{ride}/passengers/{membership}', [RideController::class, 'updatePassengerStatus'])
    ->name('rides.passengers.update')->middleware(['auth', EnsureHasApprovedMembership::class]);

Route::get('/ritten/{ride}/chat', RideChat::class)
    ->name('ritten.chat')
    ->middleware(['auth', CheckRideChatAccess::class]);


Route::get('/ritten/{ride}/boodschappenlijst/{shoppingList}', [RideController::class, 'showShoppingList'])
    ->name('ritten.shopping-lists.show');

/**
 * END - Routes voor de rides.
 */




Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/admin/gebruikers/{user}/goedkeuren', [AdminDashboardController::class, 'approveUser'])->name('user.approve');
Route::post('/admin/gebruikers/{user}/afkeuren', [AdminDashboardController::class, 'rejectUser'])->name('user.reject');
Route::get('/reset-password/{token}', [SetPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [SetPasswordController::class, 'store'])->name('password.update');
Route::get('/home', function () {
    if (! auth()->check()) {
        return redirect()->route('welcome');
    }

    return view('home');
})->name('home');

Route::get('/ledenbeheren', [AdminDashboardController::class, 'showMembers'])
    ->name('ledenbeheren')
    ->middleware('role:beheerder');

Route::delete('/ledenbeheren/{id}/verwijderen', [AdminDashboardController::class, 'destroy'])
    ->name('admin.members.destroy')
    ->middleware('role:beheerder');

Route::get('/ledenbeheren/{id}', [AdminDashboardController::class, 'showMember'])
    ->name('admin.members.show')
    ->middleware('role:beheerder');
