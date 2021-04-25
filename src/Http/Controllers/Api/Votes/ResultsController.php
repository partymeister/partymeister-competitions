<?php

namespace Partymeister\Competitions\Http\Controllers\Api\Votes;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Services\VoteService;

/**
 * Class ResultsController
 *
 * @package Partymeister\Competitions\Http\Controllers\Api\Votes
 */
class ResultsController extends ApiController
{
    /**
     * @OA\Get (
     *   tags={"VoteResultsController"},
     *   path="/api/votes/results",
     *   summary="Generate results",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Results generated"
     *       ),
     *       @OA\Property(
     *         property="results",
     *         type="array",
     *         @OA\Items(
     *           ref="#/components/schemas/ResultResource"
     *         ),
     *       ),
     *       @OA\Property(
     *         property="special",
     *         type="array",
     *         @OA\Items(
     *           ref="#/components/schemas/ResultResource"
     *         ),
     *       ),
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   )
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $resultsData = VoteService::getAllVotesByRank();
        $specialData = VoteService::getAllSpecialVotesByRank();

        return response()->json([
            'message' => 'Results generated',
            'results' => $resultsData['data'],
            'special' => $specialData['data'],
        ]);
    }
}
