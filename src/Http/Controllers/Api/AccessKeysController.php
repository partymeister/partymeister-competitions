<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Requests\Backend\AccessKeyRequest;
use Partymeister\Competitions\Http\Resources\AccessKeyCollection;
use Partymeister\Competitions\Http\Resources\AccessKeyResource;
use Partymeister\Competitions\Models\AccessKey;
use Partymeister\Competitions\Services\AccessKeyService;

/**
 * Class AccessKeysController
 */
class AccessKeysController extends ApiController
{
    protected string $model = 'Partymeister\Competitions\Models\AccessKey';

    protected string $modelResource = 'access_key';

    /**
     * @OA\Get (
     *   tags={"AccessKeysController"},
     *   path="/api/access_keys",
     *   summary="Get access_key collection",
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
     *         @OA\Items(ref="#/components/schemas/AccessKeyResource")
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
     * @return AccessKeyCollection
     */
    public function index()
    {
        $paginator = AccessKeyService::collection()
                                     ->getPaginator();

        return (new AccessKeyCollection($paginator))->additional(['message' => 'AccessKey collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"AccessKeysController"},
     *   path="/api/access_keys",
     *   summary="Create new access_key",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/AccessKeyRequest")
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
     *         ref="#/components/schemas/AccessKeyResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccessKey created"
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
     * @param  AccessKeyRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccessKeyRequest $request)
    {
        $result = AccessKeyService::create($request)
                                  ->getResult();

        return (new AccessKeyResource($result))->additional(['message' => 'AccessKey created'])
                                               ->response()
                                               ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"AccessKeysController"},
     *   path="/api/access_keys/{access_key}",
     *   summary="Get single access_key",
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
     *     name="access_key",
     *     parameter="access_key",
     *     description="AccessKey id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/AccessKeyResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccessKey read"
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
     * @param  AccessKey  $record
     * @return AccessKeyResource
     */
    public function show(AccessKey $record)
    {
        $result = AccessKeyService::show($record)
                                  ->getResult();

        return (new AccessKeyResource($result))->additional(['message' => 'AccessKey read']);
    }

    /**
     * @OA\Put (
     *   tags={"AccessKeysController"},
     *   path="/api/access_keys/{access_key}",
     *   summary="Update an existing access_key",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/AccessKeyRequest")
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
     *     name="access_key",
     *     parameter="access_key",
     *     description="AccessKey id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/AccessKeyResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccessKey updated"
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
     * @param  AccessKeyRequest  $request
     * @param  AccessKey  $record
     * @return AccessKeyResource
     */
    public function update(AccessKeyRequest $request, AccessKey $record)
    {
        $result = AccessKeyService::update($record, $request)
                                  ->getResult();

        return (new AccessKeyResource($result))->additional(['message' => 'AccessKey updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"AccessKeysController"},
     *   path="/api/access_keys/{access_key}",
     *   summary="Delete a access_key",
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
     *     name="access_key",
     *     parameter="access_key",
     *     description="AccessKey id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="AccessKey deleted"
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
     *         example="Problem deleting access_key"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  AccessKey  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AccessKey $record)
    {
        $result = AccessKeyService::delete($record)
                                  ->getResult();

        if ($result) {
            return response()->json(['message' => 'AccessKey deleted']);
        }

        return response()->json(['message' => 'Problem deleting AccessKey'], 404);
    }
}
