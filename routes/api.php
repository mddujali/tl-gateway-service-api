<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuditLogs\GetAuditLogsController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RefreshLoginController;
use App\Http\Controllers\Api\IpAddressController;
use App\Http\Controllers\Api\Profile\GetProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->group(function (): void {
        Route::post('login', LoginController::class)
            ->name('login');

        Route::post('refresh', RefreshLoginController::class)
            ->name('refresh');

        Route::post('logout', LogoutController::class)
            ->name('logout')
            ->middleware('jwt.verify');
    });

Route::get('profile', GetProfileController::class)
    ->name('profile')
    ->middleware('jwt.verify');

Route::apiResource('ip-addresses', IpAddressController::class)
    ->parameters(['ip-addresses' => 'ip_address_id'])
    ->middleware('jwt.verify');

Route::get('audit-logs', GetAuditLogsController::class)
    ->name('audit-logs.index')
    ->middleware('jwt.verify');
