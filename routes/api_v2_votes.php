<?php

use Motor\Core\Http\Middleware\V2\V2ErrorHandler;
use Partymeister\Competitions\Http\Controllers\Api\V2;

Route::prefix('api/v2')
    ->name('v2.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::apiResource('votes', V2\VotesController::class);
        Route::apiResource('manual-votes', V2\ManualVotesController::class);
        Route::apiResource('live-votes', V2\LiveVotesController::class);
    });
