<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user/login', function () {
    return view('login');
});

Route::get('/user/allposts', function () {
    return view('allposts');
});

Route::get('/user/addpost', function () {
    return view('addpost');
});