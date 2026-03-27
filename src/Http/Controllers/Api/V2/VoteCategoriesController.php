<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\VoteCategoryGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\VoteCategoryPatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\VoteCategoryPostRequest;
use Partymeister\Competitions\Http\Resources\V2\VoteCategoryCollection;
use Partymeister\Competitions\Http\Resources\V2\VoteCategoryResource;
use Partymeister\Competitions\Models\VoteCategory;
use Partymeister\Competitions\Services\VoteCategoryService;

/**
 * @tags Vote Categories
 */
class VoteCategoriesController extends ApiController
{
    protected string $model = VoteCategory::class;

    protected string $modelResource = 'vote_category';

    public function index(VoteCategoryGetRequest $request): VoteCategoryCollection
    {
        $paginator = VoteCategoryService::collection()->getPaginator();

        return (new VoteCategoryCollection($paginator))
            ->additional(['meta' => ['message' => 'Vote categories retrieved']]);
    }

    public function store(VoteCategoryPostRequest $request): JsonResponse
    {
        $result = VoteCategoryService::create($request)->getResult();

        return (new VoteCategoryResource($result))
            ->additional(['meta' => ['message' => 'Vote category created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(VoteCategory $vote_category): VoteCategoryResource
    {
        $result = VoteCategoryService::show($vote_category)->getResult();

        return (new VoteCategoryResource($result))
            ->additional(['meta' => ['message' => 'Vote category retrieved']]);
    }

    public function update(VoteCategoryPatchRequest $request, VoteCategory $vote_category): VoteCategoryResource
    {
        $result = VoteCategoryService::update($vote_category, $request)->getResult();

        return (new VoteCategoryResource($result))
            ->additional(['meta' => ['message' => 'Vote category updated']]);
    }

    public function destroy(VoteCategory $vote_category): Response
    {
        $result = VoteCategoryService::delete($vote_category)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting vote category');
    }
}
