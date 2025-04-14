<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::inertia('/', 'Auth/Login')->name('login');
Route::inertia('/register', 'Auth/Register')->name('register');
Route::inertia('/home', 'Home')->name('home');
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('users.authenticate');
Route::post('/users/create', [UserController::class, 'store'])->name('users.store');

