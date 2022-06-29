<?php

namespace Partymeister\Competitions\Http\Controllers\Api;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Requests\Backend\VoteCategoryRequest;
use Partymeister\Competitions\Http\Resources\VoteCategoryCollection;
use Partymeister\Competitions\Http\Resources\VoteCategoryResource;
use Partymeister\Competitions\Models\VoteCategory;
use Partymeister\Competitions\Services\VoteCategoryService;

/**
 * Class VoteCategoriesController
 */
class VoteCategoriesController extends ApiController
{
    protected string $model = 'Partymeister\Competitions\Models\VoteCategory';

    protected string $modelResource = 'vote_category';

    /**
     * @OA\Get (
     *   tags={"VoteCategoriesController"},
     *   path="/api/vote_categories",
     *   summary="Get vote_category collection",
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
     *         @OA\Items(ref="#/components/schemas/VoteCategoryResource")
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
     * @return VoteCategoryCollection
     */
    public function index()
    {
        $paginator = VoteCategoryService::collection()
                                        ->getPaginator();

        return (new VoteCategoryCollection($paginator))->additional(['message' => 'VoteCategory collection read']);
    }

    /**
     * @OA\Post (
     *   tags={"VoteCategoriesController"},
     *   path="/api/vote_categories",
     *   summary="Create new vote_category",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/VoteCategoryRequest")
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
     *         ref="#/components/schemas/VoteCategoryResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="VoteCategory created"
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
     * @param  VoteCategoryRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VoteCategoryRequest $request)
    {
        $result = VoteCategoryService::create($request)
                                     ->getResult();

        return (new VoteCategoryResource($result))->additional(['message' => 'VoteCategory created'])
                                                  ->response()
                                                  ->setStatusCode(201);
    }

    /**
     * @OA\Get (
     *   tags={"VoteCategoriesController"},
     *   path="/api/vote_categories/{vote_category}",
     *   summary="Get single vote_category",
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
     *     name="vote_category",
     *     parameter="vote_category",
     *     description="VoteCategory id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/VoteCategoryResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="VoteCategory read"
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
     * @param  VoteCategory  $record
     * @return VoteCategoryResource
     */
    public function show(VoteCategory $record)
    {
        $result = VoteCategoryService::show($record)
                                     ->getResult();

        return (new VoteCategoryResource($result))->additional(['message' => 'VoteCategory read']);
    }

    /**
     * @OA\Put (
     *   tags={"VoteCategoriesController"},
     *   path="/api/vote_categories/{vote_category}",
     *   summary="Update an existing vote_category",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/VoteCategoryRequest")
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
     *     name="vote_category",
     *     parameter="vote_category",
     *     description="VoteCategory id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="data",
     *         type="object",
     *         ref="#/components/schemas/VoteCategoryResource"
     *       ),
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="VoteCategory updated"
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
     * @param  VoteCategoryRequest  $request
     * @param  VoteCategory  $record
     * @return VoteCategoryResource
     */
    public function update(VoteCategoryRequest $request, VoteCategory $record)
    {
        $result = VoteCategoryService::update($record, $request)
                                     ->getResult();

        return (new VoteCategoryResource($result))->additional(['message' => 'VoteCategory updated']);
    }

    /**
     * @OA\Delete (
     *   tags={"VoteCategoriesController"},
     *   path="/api/vote_categories/{vote_category}",
     *   summary="Delete a vote_category",
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
     *     name="vote_category",
     *     parameter="vote_category",
     *     description="VoteCategory id"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="VoteCategory deleted"
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
     *         example="Problem deleting vote_category"
     *       )
     *     )
     *   )
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  VoteCategory  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(VoteCategory $record)
    {
        $result = VoteCategoryService::delete($record)
                                     ->getResult();

        if ($result) {
            return response()->json(['message' => 'VoteCategory deleted']);
        }

        return response()->json(['message' => 'Problem deleting VoteCategory'], 404);
    }
}
