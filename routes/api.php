<?php

use Motor\Core\Http\Middleware\V2\V2ErrorHandler;
use Partymeister\Competitions\Http\Controllers\Api\V2;

// V2 API routes
Route::prefix('api/v2')
    ->name('v2.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::apiResource('competition-types', V2\CompetitionTypesController::class);
        Route::apiResource('vote-categories', V2\VoteCategoriesController::class);
        Route::apiResource('option-groups', V2\OptionGroupsController::class);
        Route::apiResource('competitions', V2\CompetitionsController::class);
        Route::get('competitions/{competition}/entries', [V2\Competitions\EntriesController::class, 'index'])->name('competitions.entries.index');
        Route::get('competitions/{competition}/prizes', [V2\Competitions\PrizesController::class, 'index'])->name('competitions.prizes.index');
        Route::apiResource('entries', V2\EntriesController::class);
        Route::apiResource('access-keys', V2\AccessKeysController::class);
        Route::apiResource('competition-prizes', V2\CompetitionPrizesController::class);
    });

// V2 RPC routes
Route::prefix('api/v2/rpc')
    ->name('v2.rpc.')
    ->middleware([V2ErrorHandler::class, 'auth:sanctum', 'bindings'])
    ->group(function () {
        Route::get('votes/results', V2\Rpc\Votes\ResultsController::class)
            ->name('votes.results');
        Route::post('access-keys/generate', V2\Rpc\AccessKeys\GenerateController::class)
            ->name('access-keys.generate');
    });
