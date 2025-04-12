<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Inertia\Inertia;

Route::inertia('/', 'Login')->name('login');
Route::inertia('/Register', 'Register')->name('register');
Route::post('/users', [UserController::class, 'store'])->name('users.store');

