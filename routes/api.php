<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RefreshLoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::post('login', LoginController::class)
            ->name('login');

        Route::post('refresh', RefreshLoginController::class)
            ->name('refresh');

        Route::post('logout', LogoutController::class)
            ->name('logout')
            ->middleware('jwt.verify');
    });
