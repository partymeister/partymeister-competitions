<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Competitions;

use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Resources\V2\CompetitionPrizeCollection;
use Partymeister\Competitions\Models\Competition;

class PrizesController extends ApiController
{
    public function index(Competition $competition): CompetitionPrizeCollection
    {
        $prizes = $competition->prizes()->orderBy('rank')->paginate();

        return (new CompetitionPrizeCollection($prizes))
            ->additional(['meta' => ['message' => 'Competition prizes retrieved']]);
    }
}
