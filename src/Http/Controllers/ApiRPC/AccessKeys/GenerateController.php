<?php

namespace Partymeister\Competitions\Http\Controllers\ApiRPC\AccessKeys;

use Motor\Backend\Http\Controllers\ApiController;
use Partymeister\Competitions\Http\Requests\Backend\AccessKey\GenerateRequest;
use Partymeister\Competitions\Services\AccessKeyService;

/**
 * Class GenerateController
 */
class GenerateController extends ApiController
{
    /**
     * @OA\Post (
     *   tags={"AccessKeysGenerateController"},
     *   path="/api/access_keys/generate",
     *   summary="Generate new access keys",
     *   @OA\RequestBody(
     *     @OA\JsonContent(ref="#/components/schemas/AccessKeyGenerateRequest")
     *   ),
     *   @OA\Parameter(
     *     @OA\Schema(type="string"),
     *     in="query",
     *     allowReserved=true,
     *     name="api_token",
     *     parameter="api_token",
     *     description="Personal api_token of the user"
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *       @OA\Property(
     *         property="message",
     *         type="string",
     *         example="Generated new access keys"
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response="403",
     *     description="Access denied",
     *     @OA\JsonContent(ref="#/components/schemas/AccessDenied"),
     *   )
     * )
     *
     * Display a listing of the resource.
     *
     * @param  \Partymeister\Competitions\Http\Requests\Backend\AccessKey\GenerateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function store(GenerateRequest $request)
    {
        AccessKeyService::generate($request);

        return response()->json(['message' => 'Generated '.$request->get('quantity').' access keys']);
    }
}
