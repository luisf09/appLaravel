<?php

use App\Http\Controllers\User\GetUsersController;
use App\Http\Controllers\User\StoreUsersController;

Route::group(['prefix' => 'users'], function () {

    Route::get('/', GetUsersController::class);
    Route::post('/', StoreUsersController::class);
});
