<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ApplicationController;

// Routes in api.php automatically get the '/api' prefix.
Route::middleware(['web', 'auth:sanctum'])->group(function () {

// Notification Routes
Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
Route::get('/', 'getUserNotifications')->name('read');
Route::put('/mark-all-as-viewed', 'markAllAsRead')->name('markAllAsViewed');
});

// Messaging Routes
Route::controller(MessageController::class)->prefix('messages')->name('messages.')->group(function () {
    Route::get('/',  'getMessageNotifications')->name('notifications.read');
    Route::get('/conversation/{userId}',  'conversation')->name('conversation.read');
    Route::post('/send',  'sendMessage')->name('store');
    Route::put('/mark-all-as-read',  'markAllAsRead')->name('markAllAsRead');
});

// Applications routes
Route::patch('applications/{application_id}',[ApplicationController::class, 'updateStatus'])->name('application.update');
});