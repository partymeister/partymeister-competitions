<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Competitions;

use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Resources\V2\CompetitionPrizeCollection;
use Partymeister\Competitions\Models\Competition;

/**
 * @tags Competitions: Competitions
 */
class PrizesController extends ApiController
{
    /**
     * @response Illuminate\Http\Resources\Json\AnonymousResourceCollection<Illuminate\Pagination\LengthAwarePaginator<\Partymeister\Competitions\Http\Resources\V2\CompetitionPrizeResource>>
     */
    public function index(Competition $competition): CompetitionPrizeCollection
    {
        $prizes = $competition->prizes()->orderBy('rank')->paginate();

        return (new CompetitionPrizeCollection($prizes))
            ->additional(['meta' => ['message' => 'Competition prizes retrieved']]);
    }
}
