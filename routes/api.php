<?php

use Partymeister\Competitions\Http\Controllers\Api\AccessKeysController;
use Partymeister\Competitions\Http\Controllers\Api\CompetitionsController;
use Partymeister\Competitions\Http\Controllers\Api\CompetitionPlaylistController;
use Partymeister\Competitions\Http\Controllers\Api\CompetitionPrizesController;
use Partymeister\Competitions\Http\Controllers\Api\CompetitionTypesController;
use Partymeister\Competitions\Http\Controllers\Api\EntriesController;
use Partymeister\Competitions\Http\Controllers\Api\LiveVotesController;
use Partymeister\Competitions\Http\Controllers\Api\ManualVotesController;
use Partymeister\Competitions\Http\Controllers\Api\OptionGroupsController;
use Partymeister\Competitions\Http\Controllers\Api\PrizegivingPlaylistController;
use Partymeister\Competitions\Http\Controllers\Api\SyncController;
use Partymeister\Competitions\Http\Controllers\Api\VoteCategoriesController;
use Partymeister\Competitions\Http\Controllers\Api\VotesController;
use Partymeister\Competitions\Http\Controllers\Api\Votes\ResultsController;
use Partymeister\Competitions\Http\Controllers\Api\ShaderShowdownController;
use Partymeister\Competitions\Http\Controllers\ApiRPC\AccessKeys\GenerateController;
use Partymeister\Competitions\Http\Controllers\Api\ExportController;
use Partymeister\Competitions\Http\Middleware\ExportTokenAuth;
use Partymeister\Competitions\Http\Middleware\ShaderShowdownTokenAuth;

Route::group([
    'middleware' => ['auth:api', 'bindings', 'permission'],
    'prefix'     => 'api',
    'as'         => 'api.',
], function () {
    Route::apiResource('option_groups', OptionGroupsController::class);
    Route::apiResource('competition_types', CompetitionTypesController::class);
    Route::apiResource('competitions', CompetitionsController::class);
    //Route::get('competitions/{competition}/playlist', 'Competitions\PlaylistsController@index')
    //     ->name('competitions.playlist.index');
    Route::get('competitions/{competition}/playlist-data', [CompetitionPlaylistController::class, 'show'])
         ->name('competitions.playlist-data');
    Route::post('competitions/{competition}/playlist', [CompetitionPlaylistController::class, 'store'])
         ->name('competitions.playlist.store');
    Route::get('prizegiving/playlist-data', [PrizegivingPlaylistController::class, 'show'])
         ->name('prizegiving.playlist-data');
    Route::post('prizegiving/playlist', [PrizegivingPlaylistController::class, 'store'])
         ->name('prizegiving.playlist.store');
    Route::apiResource('vote_categories', VoteCategoriesController::class);
    Route::apiResource('entries', EntriesController::class);
    Route::apiResource('access_keys', AccessKeysController::class);
    Route::apiResource('competition_prizes', CompetitionPrizesController::class);
    Route::get('votes/results', [ResultsController::class, 'index'])
         ->name('votes.results');
    Route::apiResource('votes', VotesController::class);
    Route::apiResource('live_votes', LiveVotesController::class);
    Route::apiResource('manual_votes', ManualVotesController::class);
});

Route::group([
    'middleware' => ['auth:api', 'bindings', 'permission'],
    'prefix'     => 'api-rpc',
    'as'         => 'api-rpc.',
], function () {
    Route::post('access_keys/generate', [GenerateController::class, 'store'])
         ->name('access_keys.generate');
});

// Disabled 2026-03-18: these sync routes have no authentication and allow unauthenticated
// writes to competitions, entries (including arbitrary file writes via path traversal), and
// live votes. No longer needed — both instances share a central database.
// Route::post('api/sync/competition', [SyncController::class, 'competition']);
// Route::post('api/sync/entry', [SyncController::class, 'entry']);
// Route::post('api/sync/livevote', [SyncController::class, 'livevote']);

// TODO: remove once we abandon the php powered backend
Route::group([
    'middleware' => ['web', 'web_auth', 'bindings', 'permission'],
    'prefix'     => 'ajax',
    'as'         => 'ajax.',
], function () {
    Route::post('access_keys/generate', [GenerateController::class, 'store'])
         ->name('access_keys.generate');
});

Route::group([
    'middleware' => [ExportTokenAuth::class],
    'prefix' => 'api/export',
], function () {
    Route::get('entries', [ExportController::class, 'entries']);
});

Route::group([
    'middleware' => [ShaderShowdownTokenAuth::class],
    'prefix' => 'api/shader-showdown',
], function () {
    Route::get('competitions', [ShaderShowdownController::class, 'index']);
    Route::get('competitions/{id}', [ShaderShowdownController::class, 'show'])->where('id', '[0-9]+');
    Route::post('competitions/{id}/start', [ShaderShowdownController::class, 'start'])->where('id', '[0-9]+');
    Route::post('competitions/{id}/stop', [ShaderShowdownController::class, 'stop'])->where('id', '[0-9]+');
});
