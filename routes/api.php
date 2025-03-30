<?php

use App\Presentation\Http\Api\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('/users', UserController::class);
});
