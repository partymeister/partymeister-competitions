<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;

use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Http\Requests\Backend\CompetitionTypeRequest;
use Partymeister\Competitions\Services\CompetitionTypeService;
use Partymeister\Competitions\Http\Resources\CompetitionTypeResource;
use Partymeister\Competitions\Http\Resources\CompetitionTypeCollection;

/**
 * Class CompetitionTypesController
 * @package Partymeister\Competitions\Http\Controllers\Api
 */
class CompetitionTypesController extends ApiController
{

    protected string $modelResource = 'competition_type';

    /**
     * @OA\Get (
     *   tags={"CompetitionTypesController"},
     *   path="/api/competition_types",
     *   summary="Get competition_type collection",
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
     *         @OA\Items(ref="#/components/schemas/CompetitionTypeResource")
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
     * @return CompetitionTypeCollection
     */
    public function index()
    {
        $paginator = CompetitionTypeService::collection()->getPaginator();
        return (new CompetitionTypeCollection($paginator))->additional(['message' => 'CompetitionType collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"CompetitionTypesController"},
     *   path="/api/competition_types",
     *   summary="Create new competition_type",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/CompetitionTypeRequest")
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
     *         ref="#/components/schemas/CompetitionTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionType created"
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
     * @param CompetitionTypeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CompetitionTypeRequest $request)
    {
        $result = CompetitionTypeService::create($request)->getResult();
        return (new CompetitionTypeResource($result))->additional(['message' => 'CompetitionType created'])->response()->setStatusCode(201);
    }


    /**
     * @OA\Get (
     *   tags={"CompetitionTypesController"},
     *   path="/api/competition_types/{competition_type}",
     *   summary="Get single competition_type",
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
     *     name="competition_type",
     *     parameter="competition_type",
     *     description="CompetitionType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/CompetitionTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionType read"
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
     * @param CompetitionType $record
     * @return CompetitionTypeResource
     */
    public function show(CompetitionType $record)
    {
        $result = CompetitionTypeService::show($record)->getResult();
        return (new CompetitionTypeResource($result))->additional(['message' => 'CompetitionType read']);
    }


    /**
     * @OA\Put (
     *   tags={"CompetitionTypesController"},
     *   path="/api/competition_types/{competition_type}",
     *   summary="Update an existing competition_type",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/CompetitionTypeRequest")
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
     *     name="competition_type",
     *     parameter="competition_type",
     *     description="CompetitionType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/CompetitionTypeResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionType updated"
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
     * @param CompetitionTypeRequest $request
     * @param CompetitionType        $record
     * @return CompetitionTypeResource
     */
    public function update(CompetitionTypeRequest $request, CompetitionType $record)
    {
        $result = CompetitionTypeService::update($record, $request)->getResult();
        return (new CompetitionTypeResource($result))->additional(['message' => 'CompetitionType updated']);
    }


    /**
     * @OA\Delete (
     *   tags={"CompetitionTypesController"},
     *   path="/api/competition_types/{competition_type}",
     *   summary="Delete a competition_type",
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
     *     name="competition_type",
     *     parameter="competition_type",
     *     description="CompetitionType id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="CompetitionType deleted"
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
     *         example="Problem deleting competition_type"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param CompetitionType $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CompetitionType $record)
    {
        $result = CompetitionTypeService::delete($record)->getResult();

        if ($result) {
            return response()->json(['message' => 'CompetitionType deleted']);
        }
        return response()->json(['message' => 'Problem deleting CompetitionType'], 404);
    }
}
