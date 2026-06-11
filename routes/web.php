<?php

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
