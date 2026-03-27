<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionPatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionPostRequest;
use Partymeister\Competitions\Http\Resources\V2\CompetitionCollection;
use Partymeister\Competitions\Http\Resources\V2\CompetitionResource;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Services\CompetitionService;

/**
 * @tags Competitions
 */
class CompetitionsController extends ApiController
{
    protected string $model = Competition::class;

    protected string $modelResource = 'competition';

    public function index(CompetitionGetRequest $request): CompetitionCollection
    {
        $paginator = CompetitionService::collection()->getPaginator();

        return (new CompetitionCollection($paginator))
            ->additional(['meta' => ['message' => 'Competitions retrieved']]);
    }

    public function store(CompetitionPostRequest $request): JsonResponse
    {
        $result = CompetitionService::create($request)->getResult();

        return (new CompetitionResource($result))
            ->additional(['meta' => ['message' => 'Competition created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Competition $competition): CompetitionResource
    {
        $result = CompetitionService::show($competition)->getResult();

        return (new CompetitionResource($result))
            ->additional(['meta' => ['message' => 'Competition retrieved']]);
    }

    public function update(CompetitionPatchRequest $request, Competition $competition): CompetitionResource
    {
        $result = CompetitionService::update($competition, $request)->getResult();

        return (new CompetitionResource($result))
            ->additional(['meta' => ['message' => 'Competition updated']]);
    }

    public function destroy(Competition $competition): Response
    {
        $result = CompetitionService::delete($competition)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting competition');
    }
}
