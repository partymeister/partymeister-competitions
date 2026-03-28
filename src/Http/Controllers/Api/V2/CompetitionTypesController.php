<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionTypeGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionTypePatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionTypePostRequest;
use Partymeister\Competitions\Http\Resources\V2\CompetitionTypeCollection;
use Partymeister\Competitions\Http\Resources\V2\CompetitionTypeResource;
use Partymeister\Competitions\Models\CompetitionType;
use Partymeister\Competitions\Services\CompetitionTypeService;

/**
 * @tags Competitions: Competition Types
 */
class CompetitionTypesController extends ApiController
{
    protected string $model = CompetitionType::class;

    protected string $modelResource = 'competition_type';

    public function index(CompetitionTypeGetRequest $request): CompetitionTypeCollection
    {
        $paginator = CompetitionTypeService::collection()->getPaginator();

        return (new CompetitionTypeCollection($paginator))
            ->additional(['meta' => ['message' => 'Competition types retrieved']]);
    }

    public function store(CompetitionTypePostRequest $request): JsonResponse
    {
        $result = CompetitionTypeService::create($request)->getResult();

        return (new CompetitionTypeResource($result))
            ->additional(['meta' => ['message' => 'Competition type created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(CompetitionType $competition_type): CompetitionTypeResource
    {
        $result = CompetitionTypeService::show($competition_type)->getResult();

        return (new CompetitionTypeResource($result))
            ->additional(['meta' => ['message' => 'Competition type retrieved']]);
    }

    public function update(CompetitionTypePatchRequest $request, CompetitionType $competition_type): CompetitionTypeResource
    {
        $result = CompetitionTypeService::update($competition_type, $request)->getResult();

        return (new CompetitionTypeResource($result))
            ->additional(['meta' => ['message' => 'Competition type updated']]);
    }

    public function destroy(CompetitionType $competition_type): Response
    {
        $result = CompetitionTypeService::delete($competition_type)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting competition type');
    }
}
