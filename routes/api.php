<?php

use App\Http\{Controllers};
use Illuminate\Support\Facades\Route;

Route::post('/auth/token', [Controllers\AuthController::class, 'issueToken'])
    ->name('auth.token');

Route::group(['prefix' => '/', 'middleware' => 'auth:sanctum'], function () {
    Route::post('users/create_reader', [Controllers\UserController::class, 'createReader'])
        ->name('users.create_reader')
        ->middleware('admin');

    Route::group([
        'prefix' => 'topics',
        'as' => 'topics.',
        'middleware' => 'min-reader',
    ], function () {
        Route::apiResource('', Controllers\TopicController::class)->only('index');

        Route::apiResource('', Controllers\TopicController::class)->only('store')
            ->middleware('admin')
            ->withoutMiddleware('min-reader');

        Route::apiResource('/{topic}/subscriptions', Controllers\TopicSubscriptionController::class)
            ->only('index', 'store');

        Route::apiResource('topics.subscriptions', Controllers\TopicSubscriptionController::class)
            ->only('destroy')
            ->shallow();

        Route::apiResource('/{topic}/messages', Controllers\TopicMessageController::class)
            ->only('index', 'store');
    });
});
