<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;

use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Http\Requests\Backend\VoteRequest;
use Partymeister\Competitions\Services\VoteService;
use Partymeister\Competitions\Http\Resources\VoteResource;
use Partymeister\Competitions\Http\Resources\VoteCollection;

/**
 * Class VotesController
 * @package Partymeister\Competitions\Http\Controllers\Api
 */
class VotesController extends ApiController
{

    protected string $modelResource = 'vote';

    /**
     * @OA\Get (
     *   tags={"VotesController"},
     *   path="/api/votes",
     *   summary="Get vote collection",
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
     *         @OA\Items(ref="#/components/schemas/VoteResource")
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
     * @return VoteCollection
     */
    public function index()
    {
        $paginator = VoteService::collection()->getPaginator();
        return (new VoteCollection($paginator))->additional(['message' => 'Vote collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"VotesController"},
     *   path="/api/votes",
     *   summary="Create new vote",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/VoteRequest")
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
     *         ref="#/components/schemas/VoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Vote created"
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
     * @param VoteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VoteRequest $request)
    {
        $result = VoteService::create($request)->getResult();
        return (new VoteResource($result))->additional(['message' => 'Vote created'])->response()->setStatusCode(201);
    }


    /**
     * @OA\Get (
     *   tags={"VotesController"},
     *   path="/api/votes/{vote}",
     *   summary="Get single vote",
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
     *     name="vote",
     *     parameter="vote",
     *     description="Vote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/VoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Vote read"
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
     * @param Vote $record
     * @return VoteResource
     */
    public function show(Vote $record)
    {
        $result = VoteService::show($record)->getResult();
        return (new VoteResource($result))->additional(['message' => 'Vote read']);
    }


    /**
     * @OA\Put (
     *   tags={"VotesController"},
     *   path="/api/votes/{vote}",
     *   summary="Update an existing vote",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/VoteRequest")
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
     *     name="vote",
     *     parameter="vote",
     *     description="Vote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/VoteResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Vote updated"
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
     * @param VoteRequest $request
     * @param Vote        $record
     * @return VoteResource
     */
    public function update(VoteRequest $request, Vote $record)
    {
        $result = VoteService::update($record, $request)->getResult();
        return (new VoteResource($result))->additional(['message' => 'Vote updated']);
    }


    /**
     * @OA\Delete (
     *   tags={"VotesController"},
     *   path="/api/votes/{vote}",
     *   summary="Delete a vote",
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
     *     name="vote",
     *     parameter="vote",
     *     description="Vote id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Vote deleted"
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
     *         example="Problem deleting vote"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param Vote $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Vote $record)
    {
        $result = VoteService::delete($record)->getResult();

        if ($result) {
            return response()->json(['message' => 'Vote deleted']);
        }
        return response()->json(['message' => 'Problem deleting Vote'], 404);
    }
}
