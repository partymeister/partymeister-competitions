<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Rpc\Votes;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Partymeister\Competitions\Services\VoteService;

/**
 * @tags Votes
 */
class ResultsController extends Controller
{
    /**
     * @response array{data: array{results: array, special: array}, meta: array{api_version: string, message: string}}
     */
    public function __invoke(): JsonResponse
    {
        $results = VoteService::getAllVotesByRank();
        $special = VoteService::getAllSpecialVotesByRank();

        return response()->json([
            'data' => [
                'results' => array_values($results),
                'special' => $special,
            ],
            'meta' => [
                'api_version' => 'v2',
                'message' => 'Vote results retrieved',
            ],
        ]);
    }
}
