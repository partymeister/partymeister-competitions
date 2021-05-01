<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Requests\Backend\CompetitionPrizeRequest;
use Partymeister\Competitions\Http\Resources\CompetitionPrizeCollection;
use Partymeister\Competitions\Http\Resources\CompetitionPrizeResource;
use Partymeister\Competitions\Models\CompetitionPrize;
use Partymeister\Competitions\Services\CompetitionPrizeService;

/**
 * Class CompetitionPrizesController
 *
 * @package Partymeister\Competitions\Http\Controllers\Api
 */
class CompetitionPrizesController extends ApiController
{
    protected string $model = 'Partymeister\Competitions\Models\CompetitionPrize';

    protected string $modelResource = 'competition_prize';

    /**
     * @OA\Get (
     *   tags={"CompetitionPrizesController"},
     *   path="/api/competition_prizes",
     *   summary="Get competition_prize collection",
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
     *         @OA\Items(ref="#/components/schemas/CompetitionPrizeResource")
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
     * @return CompetitionPrizeCollection
     */
    public function index()
    {
        $paginator = CompetitionPrizeService::collection()
                                            ->getPaginator();

        return (new CompetitionPrizeCollection($paginator))->additional(['message' => 'CompetitionPrize collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"CompetitionPrizesController"},
     *   path="/api/competition_prizes",
     *   summary="Create new competition_prize",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/CompetitionPrizeRequest")
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
     *         ref="#/components/schemas/CompetitionPrizeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionPrize created"
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
     * @param CompetitionPrizeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CompetitionPrizeRequest $request)
    {
        $result = CompetitionPrizeService::create($request)
                                         ->getResult();

        return (new CompetitionPrizeResource($result))->additional(['message' => 'CompetitionPrize created'])
                                                      ->response()
                                                      ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"CompetitionPrizesController"},
     *   path="/api/competition_prizes/{competition_prize}",
     *   summary="Get single competition_prize",
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
     *     name="competition_prize",
     *     parameter="competition_prize",
     *     description="CompetitionPrize id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/CompetitionPrizeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionPrize read"
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
     * @param CompetitionPrize $record
     * @return CompetitionPrizeResource
     */
    public function show(CompetitionPrize $record)
    {
        $result = CompetitionPrizeService::show($record)
                                         ->getResult();

        return (new CompetitionPrizeResource($result))->additional(['message' => 'CompetitionPrize read']);
    }

    /**
     * @OA\Put (
     *   tags={"CompetitionPrizesController"},
     *   path="/api/competition_prizes/{competition_prize}",
     *   summary="Update an existing competition_prize",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/CompetitionPrizeRequest")
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
     *     name="competition_prize",
     *     parameter="competition_prize",
     *     description="CompetitionPrize id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/CompetitionPrizeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionPrize updated"
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
     * @param CompetitionPrizeRequest $request
     * @param CompetitionPrize $record
     * @return CompetitionPrizeResource
     */
    public function update(CompetitionPrizeRequest $request, CompetitionPrize $record)
    {
        $result = CompetitionPrizeService::update($record, $request)
                                         ->getResult();

        return (new CompetitionPrizeResource($result))->additional(['message' => 'CompetitionPrize updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"CompetitionPrizesController"},
     *   path="/api/competition_prizes/{competition_prize}",
     *   summary="Delete a competition_prize",
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
     *     name="competition_prize",
     *     parameter="competition_prize",
     *     description="CompetitionPrize id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionPrize deleted"
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
     *         example="Problem deleting competition_prize"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param CompetitionPrize $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CompetitionPrize $record)
    {
        $result = CompetitionPrizeService::delete($record)
                                         ->getResult();

        if ($result) {
            return response()->json(['message' => 'CompetitionPrize deleted']);
        }

        return response()->json(['message' => 'Problem deleting CompetitionPrize'], 404);
    }
}
