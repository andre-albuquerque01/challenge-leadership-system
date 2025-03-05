<?php

use App\Http\Controllers\Api\AssignmentsController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('/register', [UserController::class, 'store']);
        Route::post('/sessions', [UserController::class, 'login']);
        Route::post('/reSendEmail', [UserController::class, 'reSendEmail']);
        Route::get('/verify/{id}/{token}', [UserController::class, 'verifyEmail']);
        Route::post('/sendTokenRecover', [UserController::class, 'sendTokenRecover']);
        Route::put('/resetPassword', [UserController::class, 'resetPassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [UserController::class, 'logout']);
            Route::get('/showMember', [UserController::class, 'showMember']);
            Route::get('/showLeader', [UserController::class, 'showLeader']);
            Route::get('/show', [UserController::class, 'show']);
            Route::put('/update', [UserController::class, 'update']);
            Route::put('/updateRole/{id}', [UserController::class, 'updateRole']);
            Route::delete('/destroy', [UserController::class, 'destroy']);
        });
    });

    Route::apiResource('/assignments', AssignmentsController::class)->middleware('auth:sanctum');
    Route::get('/showUser', [AssignmentsController::class, 'showUser'])->middleware('auth:sanctum');
});
