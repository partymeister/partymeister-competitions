<?php

namespace Partymeister\Competitions\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="AccessKeyResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="access_key",
 *     type="string",
 *     example="REVI-SION"
 *   ),
 *   @OA\Property(
 *     property="ip_address",
 *     type="string",
 *     example="10.10.10.10"
 *   ),
 *   @OA\Property(
 *     property="registered_at",
 *     type="datetime",
 *     example="2021-05-28 12:00:00"
 *   ),
 * )
 */
class AccessKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => (int) $this->id,
            'access_key'    => $this->access_key,
            'ip_address'    => $this->ip_address,
            'registered_at' => $this->registered_at,
        ];
    }
}
