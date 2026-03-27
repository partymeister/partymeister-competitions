<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\ManualVoteGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\ManualVotePatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\ManualVotePostRequest;
use Partymeister\Competitions\Http\Resources\V2\ManualVoteCollection;
use Partymeister\Competitions\Http\Resources\V2\ManualVoteResource;
use Partymeister\Competitions\Models\ManualVote;
use Partymeister\Competitions\Services\ManualVoteService;

/**
 * @tags Manual Votes
 */
class ManualVotesController extends ApiController
{
    protected string $model = ManualVote::class;

    protected string $modelResource = 'manual_vote';

    public function index(ManualVoteGetRequest $request): ManualVoteCollection
    {
        $paginator = ManualVoteService::collection()->getPaginator();

        return (new ManualVoteCollection($paginator))
            ->additional(['meta' => ['message' => 'Manual votes retrieved']]);
    }

    public function store(ManualVotePostRequest $request): JsonResponse
    {
        $result = ManualVoteService::create($request)->getResult();

        return (new ManualVoteResource($result))
            ->additional(['meta' => ['message' => 'Manual vote created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(ManualVote $manual_vote): ManualVoteResource
    {
        $result = ManualVoteService::show($manual_vote)->getResult();

        return (new ManualVoteResource($result))
            ->additional(['meta' => ['message' => 'Manual vote retrieved']]);
    }

    public function update(ManualVotePatchRequest $request, ManualVote $manual_vote): ManualVoteResource
    {
        $result = ManualVoteService::update($manual_vote, $request)->getResult();

        return (new ManualVoteResource($result))
            ->additional(['meta' => ['message' => 'Manual vote updated']]);
    }

    public function destroy(ManualVote $manual_vote): Response
    {
        $result = ManualVoteService::delete($manual_vote)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting manual vote');
    }
}
