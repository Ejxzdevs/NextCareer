<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;

Route::inertia('/', 'LandingPage')->name('landing.page');
Route::inertia('/Login', 'Auth/Login')->name('login');
Route::inertia('/register', 'Auth/Register')->name('register');
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->name('users.authenticate');
Route::post('/users/create', [UserController::class, 'store'])->name('users.store');


Route::get('employer/dahsboard', [DashboardController::class, 'index'])->name('employer.dashboard');
Route::get('/employer/project', [ProjectController::class, 'index'])->name('employer.project');
Route::inertia('employer/application', 'Employer/Application')->name('employer.application');
Route::post('/employer/create', [ProjectController::class, 'store'])->name('employer.store');
Route::get('/employer/project/{id}/{application_id?}', [ProjectController::class, 'show'])->name('project.show');
Route::put('/employer/{id}', [ProjectController::class, 'update'])->name('employer.update');
Route::delete('/employer/{id}', [ProjectController::class, 'destroy'])->name('employer.destroy');

Route::inertia('freelance/home', 'Freelance/Home')->name('freelance.home');
Route::get('freelance/browse', [ProjectController::class, 'index'])->name('freelance.browse');
Route::post('freelance/application', [ApplicationController::class, 'store'])->name('freelance.application');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::inertia('/notifications', 'Employer/AllNotifications')->name('notifications.all');