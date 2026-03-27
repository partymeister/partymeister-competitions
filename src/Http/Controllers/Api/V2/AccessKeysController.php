<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\AccessKeyGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\AccessKeyPatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\AccessKeyPostRequest;
use Partymeister\Competitions\Http\Resources\V2\AccessKeyCollection;
use Partymeister\Competitions\Http\Resources\V2\AccessKeyResource;
use Partymeister\Competitions\Models\AccessKey;
use Partymeister\Competitions\Services\AccessKeyService;

/**
 * @tags Access Keys
 */
class AccessKeysController extends ApiController
{
    protected string $model = AccessKey::class;

    protected string $modelResource = 'access_key';

    public function index(AccessKeyGetRequest $request): AccessKeyCollection
    {
        $paginator = AccessKeyService::collection()->getPaginator();

        return (new AccessKeyCollection($paginator))
            ->additional(['meta' => ['message' => 'Access keys retrieved']]);
    }

    public function store(AccessKeyPostRequest $request): JsonResponse
    {
        $result = AccessKeyService::create($request)->getResult();

        return (new AccessKeyResource($result))
            ->additional(['meta' => ['message' => 'Access key created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(AccessKey $access_key): AccessKeyResource
    {
        $result = AccessKeyService::show($access_key)->getResult();

        return (new AccessKeyResource($result))
            ->additional(['meta' => ['message' => 'Access key retrieved']]);
    }

    public function update(AccessKeyPatchRequest $request, AccessKey $access_key): AccessKeyResource
    {
        $result = AccessKeyService::update($access_key, $request)->getResult();

        return (new AccessKeyResource($result))
            ->additional(['meta' => ['message' => 'Access key updated']]);
    }

    public function destroy(AccessKey $access_key): Response
    {
        $result = AccessKeyService::delete($access_key)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting access key');
    }
}
