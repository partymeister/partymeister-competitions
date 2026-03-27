<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\VoteGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\VotePatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\VotePostRequest;
use Partymeister\Competitions\Http\Resources\V2\VoteCollection;
use Partymeister\Competitions\Http\Resources\V2\VoteResource;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Competitions\Services\VoteService;

/**
 * @tags Votes
 */
class VotesController extends ApiController
{
    protected string $model = Vote::class;

    protected string $modelResource = 'vote';

    public function index(VoteGetRequest $request): VoteCollection
    {
        $paginator = VoteService::collection()->getPaginator();

        return (new VoteCollection($paginator))
            ->additional(['meta' => ['message' => 'Votes retrieved']]);
    }

    public function store(VotePostRequest $request): JsonResponse
    {
        $result = VoteService::create($request)->getResult();

        return (new VoteResource($result))
            ->additional(['meta' => ['message' => 'Vote created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Vote $vote): VoteResource
    {
        $result = VoteService::show($vote)->getResult();

        return (new VoteResource($result))
            ->additional(['meta' => ['message' => 'Vote retrieved']]);
    }

    public function update(VotePatchRequest $request, Vote $vote): VoteResource
    {
        $result = VoteService::update($vote, $request)->getResult();

        return (new VoteResource($result))
            ->additional(['meta' => ['message' => 'Vote updated']]);
    }

    public function destroy(Vote $vote): Response
    {
        $result = VoteService::delete($vote)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting vote');
    }
}
