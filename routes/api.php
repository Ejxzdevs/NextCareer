<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ApplicationController;

// Routes in api.php automatically get the '/api' prefix.
Route::middleware('web')->group(function () {

// Notification Routes
Route::get('/notifications', [NotificationController::class, 'getUserNotifications'])->name('notifications.read');
Route::put('/notifications/mark-all-as-viewed', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsViewed');

// Messaging Routes
Route::get('/messages', [MessageController::class, 'getMessageNotifications'])->name('messages.notifications.read');
Route::get('/messages/conversation/{userId}', [MessageController::class, 'conversation'])->name('messages.conversation.read');
Route::post('/messages/send', [MessageController::class, 'sendMessage'])->name('messages.store');
Route::put('/messages/mark-all-as-read', [MessageController::class, 'markAllAsRead'])->name('messages.markAllAsRead');

// Applications routes
Route::patch('applications/{application_id}',[ApplicationController::class, 'updateStatus'])->name('application.update');
});