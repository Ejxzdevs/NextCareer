<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return inertia::render('Login');
});

Route::get('/Register', function () {
    return inertia::render('Register');
});
