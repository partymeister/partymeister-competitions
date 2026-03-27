<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Competitions;

use Illuminate\Routing\Controller;
use Partymeister\Competitions\Http\Resources\V2\CompetitionPrizeCollection;
use Partymeister\Competitions\Models\Competition;

class PrizesController extends Controller
{
    public function index(Competition $competition): CompetitionPrizeCollection
    {
        $prizes = $competition->prizes()->orderBy('rank')->paginate();

        return (new CompetitionPrizeCollection($prizes))
            ->additional(['meta' => ['message' => 'Competition prizes retrieved']]);
    }
}
