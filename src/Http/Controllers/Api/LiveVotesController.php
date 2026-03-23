<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Admin\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Requests\Backend\LiveVoteRequest;
use Partymeister\Competitions\Http\Resources\LiveVoteCollection;
use Partymeister\Competitions\Http\Resources\LiveVoteResource;
use Partymeister\Competitions\Models\LiveVote;
use Partymeister\Competitions\Services\LiveVoteService;

/**
 * Class LiveVotesController
 */
class LiveVotesController extends ApiController
{
    protected string $model = 'Partymeister\Competitions\Models\LiveVote';

    protected string $modelResource = 'live_vote';

    /**
     * @OA\Get (
     *   tags={"LiveVotesController"},
     *   path="/api/live_votes",
     *   summary="Get live_vote collection",
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
     *         property="data",
     *         type="array",
     *         @OA\Items(ref="#/components/schemas/LiveVoteResource")
     *       ),
     *       @OA\Property(
     *         property="meta",
     *         ref="#/components/schemas/PaginationMeta"
     *       ),
     *       @OA\Property(
     *         property="links",
     *         ref="#/components/schemas/PaginationLinks"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Collection read"
     *       )
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
     * @return LiveVoteCollection
     */
    public function index()
    {
        $paginator = LiveVoteService::collection()
                                    ->getPaginator();

        return (new LiveVoteCollection($paginator))->additional(['message' => 'LiveVote collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"LiveVotesController"},
     *   path="/api/live_votes",
     *   summary="Create new live_vote",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/LiveVoteRequest")
     *   ),
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
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/LiveVoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="LiveVote created"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  LiveVoteRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(LiveVoteRequest $request)
    {
        $result = LiveVoteService::create($request)
                                 ->getResult();

        return (new LiveVoteResource($result))->additional(['message' => 'LiveVote created'])
                                              ->response()
                                              ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"LiveVotesController"},
     *   path="/api/live_votes/{live_vote}",
     *   summary="Get single live_vote",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="live_vote",
     *     parameter="live_vote",
     *     description="LiveVote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/LiveVoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="LiveVote read"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Display the specified resource.
     *
     * @param  LiveVote  $record
     * @return LiveVoteResource
     */
    public function show(LiveVote $record)
    {
        $result = LiveVoteService::show($record)
                                 ->getResult();

        return (new LiveVoteResource($result))->additional(['message' => 'LiveVote read']);
    }

    /**
     * @OA\Put (
     *   tags={"LiveVotesController"},
     *   path="/api/live_votes/{live_vote}",
     *   summary="Update an existing live_vote",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/LiveVoteRequest")
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="live_vote",
     *     parameter="live_vote",
     *     description="LiveVote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/LiveVoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="LiveVote updated"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  LiveVoteRequest  $request
     * @param  LiveVote  $record
     * @return LiveVoteResource
     */
    public function update(LiveVoteRequest $request, LiveVote $record)
    {
        $result = LiveVoteService::update($record, $request)
                                 ->getResult();

        return (new LiveVoteResource($result))->additional(['message' => 'LiveVote updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"LiveVotesController"},
     *   path="/api/live_votes/{live_vote}",
     *   summary="Delete a live_vote",
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="integer"),
     *     in="path",
     *     name="live_vote",
     *     parameter="live_vote",
     *     description="LiveVote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="LiveVote deleted"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   ),
     *   @OA\Response(
     *     response="404",
     *     description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/NotFound"),
     *   ),
     *   @OA\Response(
     *     response="400",
     *     description="Bad request",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Problem deleting live_vote"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  LiveVote  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(LiveVote $record)
    {
        $result = LiveVoteService::delete($record)
                                 ->getResult();

        if ($result) {
            return response()->json(['message' => 'LiveVote deleted']);
        }

        return response()->json(['message' => 'Problem deleting LiveVote'], 404);
    }
}
