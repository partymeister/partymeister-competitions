<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Competitions;

use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Resources\V2\EntryCollection;
use Partymeister\Competitions\Models\Competition;

/**
 * @tags Competitions: Competitions
 */
class EntriesController extends ApiController
{
    /**
     * @response Illuminate\Http\Resources\Json\AnonymousResourceCollection<Illuminate\Pagination\LengthAwarePaginator<\Partymeister\Competitions\Http\Resources\V2\EntryResource>>
     */
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
