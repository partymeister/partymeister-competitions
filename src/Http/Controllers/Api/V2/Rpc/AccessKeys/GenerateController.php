<?php

namespace Partymeister\Competitions\Http\Controllers\Api\V2\Rpc\AccessKeys;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Partymeister\Competitions\Http\Requests\Api\V2\AccessKeyGeneratePostRequest;
use Partymeister\Competitions\Models\AccessKey;
use Partymeister\Competitions\Services\AccessKeyService;

/**
 * @tags Access Keys
 */
class GenerateController extends Controller
{
    /**
     * @response array{data: array{generated: int}, meta: array{api_version: string, message: string}}
     */
    public function __invoke(AccessKeyGeneratePostRequest $request): JsonResponse
    {
        $countBefore = AccessKey::count();

        AccessKeyService::generate($request);

        $countAfter = AccessKey::count();
        $generated = $countAfter - $countBefore;

        return response()->json([
            'data' => [
                'generated' => $generated,
            ],
            'meta' => [
                'api_version' => 'v2',
                'message' => "{$generated} access keys generated",
            ],
        ], 201);
    }
}
