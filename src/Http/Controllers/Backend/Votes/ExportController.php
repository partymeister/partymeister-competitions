<?php

namespace Partymeister\Competitions\Http\Controllers\Backend\Votes;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Motor\Admin\Http\Controllers\Controller;
use Partymeister\Competitions\Services\VoteService;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Class PlaylistsController
 */
class ExportController extends Controller
{
    /**
     * @return StreamedResponse
     */
    public function csv()
    {
        return response()->streamDownload(function () {
            echo VoteService::exportCSV();
        }, Str::kebab(config('motor-cms-frontend.name').'-results-'.Carbon::now()->format('Y-m-d_H-i-s').'.csv'));
    }
}
