<?php
// Legacy backend web routes commented out — backend uses V2 API
/*
use Partymeister\Competitions\Http\Controllers\Backend\AccessKeys\ExportController as AccessKeysExportController;
use Partymeister\Competitions\Http\Controllers\Backend\AccessKeysController;
use Partymeister\Competitions\Http\Controllers\Backend\CompetitionPrizes\ExportController as CompetitionPrizesExportController;
use Partymeister\Competitions\Http\Controllers\Backend\CompetitionPrizesController;
use Partymeister\Competitions\Http\Controllers\Backend\Competitions\PlaylistsController;
use Partymeister\Competitions\Http\Controllers\Backend\CompetitionsController;
use Partymeister\Competitions\Http\Controllers\Backend\CompetitionTypesController;
use Partymeister\Competitions\Http\Controllers\Backend\Component\ComponentEntriesController;
use Partymeister\Competitions\Http\Controllers\Backend\Component\ComponentEntryScreenshotsController;
use Partymeister\Competitions\Http\Controllers\Backend\Component\ComponentEntryUploadsController;
use Partymeister\Competitions\Http\Controllers\Backend\Component\ComponentVotingsController;
use Partymeister\Competitions\Http\Controllers\Backend\Entries\CommentsController;
use Partymeister\Competitions\Http\Controllers\Backend\EntriesController;
use Partymeister\Competitions\Http\Controllers\Backend\OptionGroupsController;
use Partymeister\Competitions\Http\Controllers\Backend\VoteCategoriesController;
use Partymeister\Competitions\Http\Controllers\Backend\Votes\ExportController as VotesExportController;
use Partymeister\Competitions\Http\Controllers\Backend\Votes\PlaylistsController as VotesPlaylistsController;
use Partymeister\Competitions\Http\Controllers\Backend\VotesController;

// Shader Showdown — standalone Vue app (token auth via API, no backend login required)
Route::get('backend/shader-showdown', function () {
    return view('partymeister-competitions::shader-showdown.index');
})->middleware('web')->name('backend.shader-showdown');

Route::group([
    'as' => 'backend.',
    'prefix' => 'backend',
    'middleware' => [
        'web',
        'web_auth',
        'navigation',
    ],
], function () {
    Route::group(['middleware' => ['permission']], function () {
        Route::resource('option_groups', OptionGroupsController::class);
        Route::resource('competition_types', CompetitionTypesController::class);
        Route::get('competitions/{competition}/playlist', [PlaylistsController::class, 'index'])
            ->name('competitions.playlist.index');
        Route::post('competitions/{competition}/playlist', [PlaylistsController::class, 'store'])
            ->name('competitions.playlist.store');
        Route::resource('competitions', CompetitionsController::class);
        Route::resource('vote_categories', VoteCategoriesController::class);
        Route::resource('entries', EntriesController::class);
        Route::get('entries/comments/{entry}', [CommentsController::class, 'index'])
            ->name('entries.comments.index');
        Route::post('entries/comments/{entry}', [CommentsController::class, 'store'])
            ->name('entries.comments.store');
        Route::get('access_keys/export_csv', [AccessKeysExportController::class, 'csv'])
            ->name('access_keys.export.csv');
        Route::get('access_keys/export_pdf', [AccessKeysExportController::class, 'pdf'])
            ->name('access_keys.export.pdf');
        Route::resource('access_keys', AccessKeysController::class);
        Route::get('competition_prizes/export_receipt', [CompetitionPrizesExportController::class, 'receipt'])
            ->name('competition_prizes.export.receipt');
        Route::get('competition_prizes/export_prizesheet', [CompetitionPrizesExportController::class, 'prizesheet'])
            ->name('competition_prizes.export.prizesheet');
        Route::get('votes/export/csv', [VotesExportController::class, 'csv'])
            ->name('votes.export.csv');
        Route::resource('competition_prizes', CompetitionPrizesController::class);
        Route::get('votes/playlist', [VotesPlaylistsController::class, 'index'])
            ->name('votes.playlist.index');
        Route::post('votes/playlist', [VotesPlaylistsController::class, 'store'])
            ->name('votes.playlist.store');
        Route::resource('votes', VotesController::class);
    });
});

// FIXME: build this with an actual controller so this doesn't break route caching
// Route::get('results', function () {
//    $results = \Partymeister\Competitions\Services\VoteService::getAllVotesByRank();
//
//    foreach ($results as $competition) {
//        echo $competition['name'] . "\r\n";
//        foreach ($competition['entries'] as $entry) {
//            echo $entry['points'] . "\t" . $entry['title'] . "\t" . $entry['author'] . "\r\n";
//        }
//        echo "\r\n";
//    }
// });
//
// Route::get('results-special', function () {
//    $results = \Partymeister\Competitions\Services\VoteService::getAllSpecialVotesByRank();
//
//    foreach ($results as $entry) {
//        echo $entry['special_votes'] . "\t" . $entry['title'] . "\t" . $entry['author'] . "\r\n";
//    }
// });

// Only add the route group if you don't already have one for the given namespace
Route::group([
    'as' => 'component.',
    'prefix' => 'component',
    'middleware' => [
        'web',
    ],
], function () {
    // You only need this part if you already have a component group for the given namespace
    Route::get('votings', [ComponentVotingsController::class, 'create'])
        ->name('votings.create');
    Route::post('votings', [ComponentVotingsController::class, 'store'])
        ->name('votings.store');
    Route::get('votings/{component_voting}', [ComponentVotingsController::class, 'edit'])
        ->name('votings.edit');
    Route::patch('votings/{component_voting}', [ComponentVotingsController::class, 'update'])
        ->name('votings.update');

    Route::get('entries', [ComponentEntriesController::class, 'create'])
        ->name('entries.create');
    Route::post('entries', [ComponentEntriesController::class, 'store'])
        ->name('entries.store');
    Route::get('entries/{component_entry}', [ComponentEntriesController::class, 'edit'])
        ->name('entries.edit');
    Route::patch('entries/{component_entry}', [ComponentEntriesController::class, 'update'])
        ->name('entries.update');

    Route::get('entry-screenshots', [ComponentEntryScreenshotsController::class, 'create'])
        ->name('entry-screenshots.create');
    Route::post('entry-screenshots', [ComponentEntryScreenshotsController::class, 'store'])
        ->name('entry-screenshots.store');
    Route::get('entry-screenshots/{component_entry_screenshot}', [ComponentEntryScreenshotsController::class, 'edit'])
        ->name('entry-screenshots.edit');
    Route::patch('entry-screenshots/{component_entry_screenshot}', [ComponentEntryScreenshotsController::class, 'update'])
        ->name('entry-screenshots.update');

    Route::get('entry-uploads', [ComponentEntryUploadsController::class, 'create'])
        ->name('entry-uploads.create');
    Route::post('entry-uploads', [ComponentEntryUploadsController::class, 'store'])
        ->name('entry-uploads.store');
    Route::get('entry-uploads/{component_entry_upload}', [ComponentEntryUploadsController::class, 'edit'])
        ->name('entry-uploads.edit');
    Route::patch('entry-uploads/{component_entry_upload}', [ComponentEntryUploadsController::class, 'update'])
        ->name('entry-uploads.update');
});
*/
