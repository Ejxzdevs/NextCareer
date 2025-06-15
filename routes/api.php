<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

// Routes in api.php automatically get the '/api' prefix.
// So, this route will be accessible at: your-app.com/api/notifications/raw-projects
Route::get('/notifications/raw-projects', [NotificationController::class, 'getUserNotifications'])->name('api.raw.notifications');