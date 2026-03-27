<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Competitions;

use Illuminate\Routing\Controller;
use Partymeister\Competitions\Http\Resources\V2\EntryCollection;
use Partymeister\Competitions\Models\Competition;

class EntriesController extends Controller
{
    public function index(Competition $competition): EntryCollection
    {
        $entries = $competition->entries()
            ->with('competition')
            ->orderBy('sort_position')
            ->paginate();

        return (new EntryCollection($entries))
            ->additional(['meta' => ['message' => 'Competition entries retrieved']]);
    }
}
