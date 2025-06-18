<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

// Routes in api.php automatically get the '/api' prefix.
Route::get('/notifications/raw-projects', [NotificationController::class, 'getUserNotifications'])->name('api.raw.notifications');
Route::post('/notifications/mark-as-viewed', [NotificationController::class, 'markApplicationsAsViewed'])->name('update.applications.viewed');