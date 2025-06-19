<?php

declare(strict_types=1);

use App\Http\Api\V1\Controllers\Auth\LoginController;
use App\Http\Api\V1\Controllers\PromoCode\CreatePromoCodeController;
use App\Http\Api\V1\Controllers\PromoCode\PromoCodeValidationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('login', LoginController::class)->name('login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::prefix('admin')->middleware(['role:admin'])->group(function () {
            Route::post('promo-codes', CreatePromoCodeController::class)->name('promo-codes.create');
        });

        Route::prefix('promo-codes')->middleware(['role:admin|user'])->group(function () {
            Route::post('validate', PromoCodeValidationController::class)->name('promo-codes.validate');
        });
    });
 });
