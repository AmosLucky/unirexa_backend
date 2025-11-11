<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/documentation', function () {
    return view('/documentation/index');
});
