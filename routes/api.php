<?php

use App\Presentation\Http\Api\Shared\CountryController;
use App\Presentation\Http\Api\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/countries', [CountryController::class, 'index'])
    ->name('countries.index');

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('/users', UserController::class);
    Route::prefix('users')
        ->as('users.')
        ->controller(UserController::class)
        ->group(function () {
            Route::put('/{user}/update-password', 'updatePassword')
                ->name('update-password');

            Route::put('/{user}/update-selfie', 'updateSelfie')
                ->name('update-selfie');
        });
});
