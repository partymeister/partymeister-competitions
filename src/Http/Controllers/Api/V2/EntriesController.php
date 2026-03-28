<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\EntryGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\EntryPatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\EntryPostRequest;
use Partymeister\Competitions\Http\Resources\V2\EntryCollection;
use Partymeister\Competitions\Http\Resources\V2\EntryResource;
use Partymeister\Competitions\Models\Entry;
use Partymeister\Competitions\Services\EntryService;

/**
 * @tags Competitions: Entries
 */
class EntriesController extends ApiController
{
    protected string $model = Entry::class;

    protected string $modelResource = 'entry';

    public function index(EntryGetRequest $request): EntryCollection
    {
        $paginator = EntryService::collection()->getPaginator();

        return (new EntryCollection($paginator))
            ->additional(['meta' => ['message' => 'Entries retrieved']]);
    }

    public function store(EntryPostRequest $request): JsonResponse
    {
        $result = EntryService::create($request)->getResult();

        return (new EntryResource($result))
            ->additional(['meta' => ['message' => 'Entry created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Entry $entry): EntryResource
    {
        $result = EntryService::show($entry)->getResult();

        return (new EntryResource($result))
            ->additional(['meta' => ['message' => 'Entry retrieved']]);
    }

    public function update(EntryPatchRequest $request, Entry $entry): EntryResource
    {
        $result = EntryService::update($entry, $request)->getResult();

        return (new EntryResource($result))
            ->additional(['meta' => ['message' => 'Entry updated']]);
    }

    public function destroy(Entry $entry): Response
    {
        $result = EntryService::delete($entry)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting entry');
    }
}
