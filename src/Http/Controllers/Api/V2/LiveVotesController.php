<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\LiveVoteGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\LiveVotePatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\LiveVotePostRequest;
use Partymeister\Competitions\Http\Resources\V2\LiveVoteCollection;
use Partymeister\Competitions\Http\Resources\V2\LiveVoteResource;
use Partymeister\Competitions\Models\LiveVote;
use Partymeister\Competitions\Services\LiveVoteService;

/**
 * @tags Competitions: Live Votes
 */
class LiveVotesController extends ApiController
{
    protected string $model = LiveVote::class;

    protected string $modelResource = 'live_vote';

    public function index(LiveVoteGetRequest $request): LiveVoteCollection
    {
        $paginator = LiveVoteService::collection()->getPaginator();

        return (new LiveVoteCollection($paginator))
            ->additional(['meta' => ['message' => 'Live votes retrieved']]);
    }

    public function store(LiveVotePostRequest $request): JsonResponse
    {
        $result = LiveVoteService::create($request)->getResult();

        return (new LiveVoteResource($result))
            ->additional(['meta' => ['message' => 'Live vote created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(LiveVote $live_vote): LiveVoteResource
    {
        $result = LiveVoteService::show($live_vote)->getResult();

        return (new LiveVoteResource($result))
            ->additional(['meta' => ['message' => 'Live vote retrieved']]);
    }

    public function update(LiveVotePatchRequest $request, LiveVote $live_vote): LiveVoteResource
    {
        $result = LiveVoteService::update($live_vote, $request)->getResult();

        return (new LiveVoteResource($result))
            ->additional(['meta' => ['message' => 'Live vote updated']]);
    }

    public function destroy(LiveVote $live_vote): Response
    {
        $result = LiveVoteService::delete($live_vote)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting live vote');
    }
}
