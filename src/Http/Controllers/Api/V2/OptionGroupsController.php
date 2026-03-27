<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Motor\Core\Http\Controllers\Api\V2\ApiController;
use Partymeister\Competitions\Http\Requests\Api\V2\OptionGroupGetRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\OptionGroupPatchRequest;
use Partymeister\Competitions\Http\Requests\Api\V2\OptionGroupPostRequest;
use Partymeister\Competitions\Http\Resources\V2\OptionGroupCollection;
use Partymeister\Competitions\Http\Resources\V2\OptionGroupResource;
use Partymeister\Competitions\Models\OptionGroup;
use Partymeister\Competitions\Services\OptionGroupService;

/**
 * @tags Option Groups
 */
class OptionGroupsController extends ApiController
{
    protected string $model = OptionGroup::class;

    protected string $modelResource = 'option_group';

    public function index(OptionGroupGetRequest $request): OptionGroupCollection
    {
        $paginator = OptionGroupService::collection()->getPaginator();

        return (new OptionGroupCollection($paginator))
            ->additional(['meta' => ['message' => 'Option groups retrieved']]);
    }

    public function store(OptionGroupPostRequest $request): JsonResponse
    {
        $result = OptionGroupService::create($request)->getResult();

        return (new OptionGroupResource($result->load('options')))
            ->additional(['meta' => ['message' => 'Option group created']])
            ->response()
            ->setStatusCode(201);
    }

    public function show(OptionGroup $option_group): OptionGroupResource
    {
        $result = OptionGroupService::show($option_group)->getResult();

        return (new OptionGroupResource($result->load('options')))
            ->additional(['meta' => ['message' => 'Option group retrieved']]);
    }

    public function update(OptionGroupPatchRequest $request, OptionGroup $option_group): OptionGroupResource
    {
        $result = OptionGroupService::update($option_group, $request)->getResult();

        return (new OptionGroupResource($result->load('options')))
            ->additional(['meta' => ['message' => 'Option group updated']]);
    }

    public function destroy(OptionGroup $option_group): Response
    {
        $result = OptionGroupService::delete($option_group)->getResult();

        if ($result) {
            return $this->noContentResponse();
        }

        abort(404, 'Problem deleting option group');
    }
}
