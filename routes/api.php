<?php

Route::group([
    'middleware' => ['auth:api', 'bindings', 'permission'],
    'namespace' => 'Partymeister\Competitions\Http\Controllers\Api',
    'prefix' => 'api',
    'as' => 'api.',
], function () {
    Route::apiResource('option_groups', 'OptionGroupsController');
    Route::apiResource('competition_types', 'CompetitionTypesController');
    Route::apiResource('competitions', 'CompetitionsController');
    // Route::get('competitions/{competition}/playlist', 'Competitions\PlaylistsController@index')
    //     ->name('competitions.playlist.index');
    Route::apiResource('vote_categories', 'VoteCategoriesController');
    Route::apiResource('entries', 'EntriesController');
    Route::apiResource('access_keys', 'AccessKeysController');
    Route::apiResource('competition_prizes', 'CompetitionPrizesController');
    Route::get('votes/results', 'Votes\ResultsController@index')
        ->name('votes.results');
    Route::apiResource('votes', 'VotesController');
    Route::apiResource('live_votes', 'LiveVotesController');
    Route::apiResource('manual_votes', 'ManualVotesController');
});

Route::group([
    'middleware' => ['auth:api', 'bindings', 'permission'],
    'namespace' => 'Partymeister\Competitions\Http\Controllers\ApiRPC',
    'prefix' => 'api-rpc',
    'as' => 'api-rpc.',
], function () {
    Route::post('access_keys/generate', 'AccessKeys\GenerateController@store')
        ->name('access_keys.generate');
});

Route::post('api/sync/competition', 'Partymeister\Competitions\Http\Controllers\Api\SyncController@competition');
Route::post('api/sync/entry', 'Partymeister\Competitions\Http\Controllers\Api\SyncController@entry');
Route::post('api/sync/livevote', 'Partymeister\Competitions\Http\Controllers\Api\SyncController@livevote');

Route::group([
    'middleware' => ['web', 'auth:visitor', 'bindings'],
    'namespace' => 'Partymeister\Competitions\Http\Controllers\Api\Frontend',
    'prefix' => 'ajax',
    'as' => 'ajax.',
], function () {
    Route::post('votes/{api_token}/submit', 'VotesController@store')
        ->name('votes.submit');
});

// TODO: remove once we abandon the php powered backend
Route::group([
    'middleware' => ['web', 'web_auth', 'bindings', 'permission'],
    'namespace' => 'Partymeister\Competitions\Http\Controllers\ApiRPC',
    'prefix' => 'ajax',
    'as' => 'ajax.',
], function () {
    Route::post('access_keys/generate', 'AccessKeys\GenerateController@store')
        ->name('access_keys.generate');
});
