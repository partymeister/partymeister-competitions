<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Requests\Backend\OptionGroupRequest;
use Partymeister\Competitions\Http\Resources\OptionGroupCollection;
use Partymeister\Competitions\Http\Resources\OptionGroupResource;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Competitions\Services\OptionGroupService;

/**
 * Class OptionGroupsController
 */
class OptionGroupsController extends ApiController
{
    protected string $model = 'Partymeister\Competitions\Models\OptionGroup';

    protected string $modelResource = 'option_group';

    /**
     * @OA\Get (
     *   tags={"OptionGroupsController"},
     *   path="/api/option_groups",
     *   summary="Get option_group collection",
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
     *         @OA\Items(ref="#/components/schemas/OptionGroupResource")
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
     * @return OptionGroupCollection
     */
    public function index()
    {
        $paginator = OptionGroupService::collection()
                                       ->getPaginator();

        return (new OptionGroupCollection($paginator))->additional(['message' => 'OptionGroup collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"OptionGroupsController"},
     *   path="/api/option_groups",
     *   summary="Create new option_group",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/OptionGroupRequest")
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
     *         ref="#/components/schemas/OptionGroupResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="OptionGroup created"
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
     * @param  OptionGroupRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OptionGroupRequest $request)
    {
        $result = OptionGroupService::create($request)
                                    ->getResult();

        return (new OptionGroupResource($result))->additional(['message' => 'OptionGroup created'])
                                                 ->response()
                                                 ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"OptionGroupsController"},
     *   path="/api/option_groups/{option_group}",
     *   summary="Get single option_group",
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
     *     name="option_group",
     *     parameter="option_group",
     *     description="OptionGroup id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/OptionGroupResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="OptionGroup read"
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
     * @param  OptionGroup  $record
     * @return OptionGroupResource
     */
    public function show(OptionGroup $record)
    {
        $result = OptionGroupService::show($record)
                                    ->getResult();

        return (new OptionGroupResource($result))->additional(['message' => 'OptionGroup read']);
    }

    /**
     * @OA\Put (
     *   tags={"OptionGroupsController"},
     *   path="/api/option_groups/{option_group}",
     *   summary="Update an existing option_group",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/OptionGroupRequest")
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
     *     name="option_group",
     *     parameter="option_group",
     *     description="OptionGroup id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/OptionGroupResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="OptionGroup updated"
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
     * @param  OptionGroupRequest  $request
     * @param  OptionGroup  $record
     * @return OptionGroupResource
     */
    public function update(OptionGroupRequest $request, OptionGroup $record)
    {
        $result = OptionGroupService::update($record, $request)
                                    ->getResult();

        return (new OptionGroupResource($result))->additional(['message' => 'OptionGroup updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"OptionGroupsController"},
     *   path="/api/option_groups/{option_group}",
     *   summary="Delete a option_group",
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
     *     name="option_group",
     *     parameter="option_group",
     *     description="OptionGroup id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="OptionGroup deleted"
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
     *         example="Problem deleting option_group"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  OptionGroup  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(OptionGroup $record)
    {
        $result = OptionGroupService::delete($record)
                                    ->getResult();

        if ($result) {
            return response()->json(['message' => 'OptionGroup deleted']);
        }

        return response()->json(['message' => 'Problem deleting OptionGroup'], 404);
    }
}
