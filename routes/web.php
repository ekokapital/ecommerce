<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home');


Route::group(['middleware' => 'guest'], function() {
    //
});


Route::group(['middleware' => 'auth'], function () {
    // Route::view('profile', 'profile')->name('profile'); 

    Route::group(['middleware' => 'verified'], function () {
        // Route::view('dashboard', 'dashboard')->name('dashboard');
        

    });
});
