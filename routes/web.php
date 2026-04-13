<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/privacy_policy', function () {
    return view('privacy_policy');
});
Route::get('/terms_of_service', function () {
    return view('terms');
});



Route::get('/documentation', function () {
    return view('/documentation/index');
});
