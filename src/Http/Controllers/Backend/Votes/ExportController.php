<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Votes;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Motor\Backend\Http\Controllers\Controller;
use Partymeister\Competitions\Services\VoteService;

/**
 * Class PlaylistsController
 */
class ExportController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function csv()
    {
        return response()->streamDownload(function () {
            echo VoteService::exportCSV();
        }, Str::kebab(config('motor-cms-frontend.name').'-results-'.Carbon::now().'.csv'));
    }
}
