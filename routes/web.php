<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleAuthController;

// Landing Page Route
Route::inertia('/', 'LandingPage')->name('landing.page');

// show login and register pages
Route::inertia('/login', 'Auth/Login')->name('login');
Route::inertia('/register', 'Auth/Register')->name('register');

// Google Auth Routes
Route::get('/auth/google', [GoogleAuthController::class,'googleLogin'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'googleAuthentication'])->name('auth.google-callback');

// logout route
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// user authentication and creation routes
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('users.authenticate');
Route::post('/users/create', [UserController::class, 'store'])->name('users.store');

// employer
Route::middleware(['auth','role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/application', [ApplicationController::class, 'index'])->name('application');
       Route::controller(ProjectController::class)->group(function () {
            Route::get('/project', 'index')->name('project');
            Route::post('/create', 'store')->name('store');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
    });

// freelance
Route::middleware(['auth','role:freelance'])->prefix('freelance')->name('freelance.')->group(function () {
    Route::inertia('/home', 'Freelance/Home')->name('home');
    Route::get('/browse', [ProjectController::class, 'index'])->name('browse');
    Route::get('/application', [ApplicationController::class, 'index'])->name('application');
    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');
});

// shared routes
Route::middleware(['auth','role:employer,freelance'])->prefix('user')->group(function () {
    // notifications
    Route::inertia('/all/notifications', 'Employer/AllNotifications')->name('user.allNotifications.index');
    Route::inertia('/all/messages','Shared/AllMessages')->name('user.allMessages.index');

    // user profile routes
    Route::get('/project/{id}/{application_id?}', [ProjectController::class, 'show'])->name('project.show');
    Route::get('/profile/{id}', [ProfileController::class,'showProfile'])->name('userProfile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('userProfile.update');
});
