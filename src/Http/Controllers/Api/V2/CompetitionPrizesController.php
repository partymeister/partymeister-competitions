<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionPrizeGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionPrizePatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\CompetitionPrizePostRequest;
use Partymeister\Competitions\Http\Resources\V2\CompetitionPrizeCollection;
use Partymeister\Competitions\Http\Resources\V2\CompetitionPrizeResource;
use Partymeister\Competitions\Models\CompetitionPrize;
use Partymeister\Competitions\Services\CompetitionPrizeService;

/**
 * @tags Competition Prizes
 */
class CompetitionPrizesController extends ApiController
{
    protected string $model = CompetitionPrize::class;

    protected string $modelResource = 'competition_prize';

    public function index(CompetitionPrizeGetRequest $request): CompetitionPrizeCollection
    {
        $paginator = CompetitionPrizeService::collection()->getPaginator();

        return (new CompetitionPrizeCollection($paginator))
            ->additional(['meta' => ['message' => 'Competition prizes retrieved']]);
    }

    public function store(CompetitionPrizePostRequest $request): JsonResponse
    {
        $result = CompetitionPrizeService::create($request)->getResult();

        return (new CompetitionPrizeResource($result))
            ->additional(['meta' => ['message' => 'Competition prize created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(CompetitionPrize $competition_prize): CompetitionPrizeResource
    {
        $result = CompetitionPrizeService::show($competition_prize)->getResult();

        return (new CompetitionPrizeResource($result))
            ->additional(['meta' => ['message' => 'Competition prize retrieved']]);
    }

    public function update(CompetitionPrizePatchRequest $request, CompetitionPrize $competition_prize): CompetitionPrizeResource
    {
        $result = CompetitionPrizeService::update($competition_prize, $request)->getResult();

        return (new CompetitionPrizeResource($result))
            ->additional(['meta' => ['message' => 'Competition prize updated']]);
    }

    public function destroy(CompetitionPrize $competition_prize): Response
    {
        $result = CompetitionPrizeService::delete($competition_prize)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting competition prize');
    }
}
