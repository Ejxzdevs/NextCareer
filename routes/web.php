<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;


// Landing Page Route
Route::inertia('/', 'LandingPage')->name('landing.page');

// show login and register pages
Route::inertia('/Login', 'Auth/Login')->name('login');
Route::inertia('/register', 'Auth/Register')->name('register');

// logout route
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// user authentication and creation routes
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('users.authenticate');
Route::post('/users/create', [UserController::class, 'store'])->name('users.store');

// employer
Route::get('employer/dashboard', [DashboardController::class, 'index'])->name('employer.dashboard');
Route::get('/employer/project', [ProjectController::class, 'index'])->name('employer.project');
Route::get('employer/application', [ApplicationController::class, 'index'])->name('employer.application');
Route::post('/employer/create', [ProjectController::class, 'store'])->name('employer.store');
Route::get('/employer/project/{id}/{application_id?}', [ProjectController::class, 'show'])->name('project.show');
Route::put('/employer/{id}', [ProjectController::class, 'update'])->name('employer.update');
Route::delete('/employer/{id}', [ProjectController::class, 'destroy'])->name('employer.destroy');

// freelance
Route::inertia('freelance/home', 'Freelance/Home')->name('freelance.home');
Route::get('freelance/browse', [ProjectController::class, 'index'])->name('freelance.browse');
Route::post('freelance/application', [ApplicationController::class, 'store'])->name('freelance.application');

// shared routes
// go to all notifications page
Route::inertia('user/all/notifications', 'Employer/AllNotifications')->name('user.allNotifications.index');
Route::inertia('user/all/messages','Employer/AllMessages')->name('user.AllMessages.index');

// user profile routes
Route::get('user/profile/{id}', [ProfileController::class,'showProfile'])->name('userProfile.show');
Route::put('/user/profile', [ProfileController::class, 'updateProfile'])->name('userProfile.update');


