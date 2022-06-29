<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Requests\Backend\ManualVoteRequest;
use Partymeister\Competitions\Http\Resources\ManualVoteCollection;
use Partymeister\Competitions\Http\Resources\ManualVoteResource;
use Partymeister\Competitions\Models\ManualVote;
use Partymeister\Competitions\Services\ManualVoteService;

/**
 * Class ManualVotesController
 */
class ManualVotesController extends ApiController
{
    protected string $model = 'Partymeister\Competitions\Models\ManualVote';

    protected string $modelResource = 'manual_vote';

    /**
     * @OA\Get (
     *   tags={"ManualVotesController"},
     *   path="/api/manual_votes",
     *   summary="Get manual_vote collection",
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
     *         @OA\Items(ref="#/components/schemas/ManualVoteResource")
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
     * @return ManualVoteCollection
     */
    public function index()
    {
        $paginator = ManualVoteService::collection()
                                      ->getPaginator();

        return (new ManualVoteCollection($paginator))->additional(['message' => 'ManualVote collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"ManualVotesController"},
     *   path="/api/manual_votes",
     *   summary="Create new manual_vote",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/ManualVoteRequest")
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
     *         ref="#/components/schemas/ManualVoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ManualVote created"
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
     * @param  ManualVoteRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ManualVoteRequest $request)
    {
        $result = ManualVoteService::create($request)
                                   ->getResult();

        return (new ManualVoteResource($result))->additional(['message' => 'ManualVote created'])
                                                ->response()
                                                ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"ManualVotesController"},
     *   path="/api/manual_votes/{manual_vote}",
     *   summary="Get single manual_vote",
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
     *     name="manual_vote",
     *     parameter="manual_vote",
     *     description="ManualVote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/ManualVoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ManualVote read"
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
     * @param  ManualVote  $record
     * @return ManualVoteResource
     */
    public function show(ManualVote $record)
    {
        $result = ManualVoteService::show($record)
                                   ->getResult();

        return (new ManualVoteResource($result))->additional(['message' => 'ManualVote read']);
    }

    /**
     * @OA\Put (
     *   tags={"ManualVotesController"},
     *   path="/api/manual_votes/{manual_vote}",
     *   summary="Update an existing manual_vote",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/ManualVoteRequest")
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
     *     name="manual_vote",
     *     parameter="manual_vote",
     *     description="ManualVote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/ManualVoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ManualVote updated"
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
     * @param  ManualVoteRequest  $request
     * @param  ManualVote  $record
     * @return ManualVoteResource
     */
    public function update(ManualVoteRequest $request, ManualVote $record)
    {
        $result = ManualVoteService::update($record, $request)
                                   ->getResult();

        return (new ManualVoteResource($result))->additional(['message' => 'ManualVote updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"ManualVotesController"},
     *   path="/api/manual_votes/{manual_vote}",
     *   summary="Delete a manual_vote",
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
     *     name="manual_vote",
     *     parameter="manual_vote",
     *     description="ManualVote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="ManualVote deleted"
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
     *         example="Problem deleting manual_vote"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  ManualVote  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ManualVote $record)
    {
        $result = ManualVoteService::delete($record)
                                   ->getResult();

        if ($result) {
            return response()->json(['message' => 'ManualVote deleted']);
        }

        return response()->json(['message' => 'Problem deleting ManualVote'], 404);
    }
}
