<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('/dashboard/authors', function () {
    return view('dashboard.authors');
});

Route::get('/dashboard/publishers', function () {
    return view('dashboard.publishers');
});