<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;


Route::inertia('/', 'Auth/Login')->name('login');
Route::inertia('/register', 'Auth/Register')->name('register');
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('users.authenticate');
Route::post('/users/create', [UserController::class, 'store'])->name('users.store');


Route::inertia('employer/dahsboard', 'Employer/Dashboard')->name('employer.dashboard');
Route::inertia('employer/project', 'Employer/PostProject')->name('employer.project');
Route::inertia('freelance/home', 'Freelance/Home')->name('freelance.home');



Route::post('/logout', [UserController::class, 'logout'])->name('logout');