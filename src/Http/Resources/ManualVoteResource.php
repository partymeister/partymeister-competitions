<?php

namespace Partymeister\Competition\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="ManualVoteResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="competition_id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="entry_id",
 *     type="integer",
 *     example="2"
 *   ),
 *   @OA\Property(
 *     property="points",
 *     type="integer",
 *     example="3"
 *   ),
 *   @OA\Property(
 *     property="ip_address",
 *     type="string",
 *     example="10.10.10.10"
 *   ),
 * )
 */
class ManualVoteResource extends JsonResource
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
            'id'             => (int) $this->id,
            'competition_id' => (int) $this->competition_id,
            'entry_id'       => (int) $this->entry_id,
            'points'         => (int) $this->points,
            'ip_address'     => $this->ip_address,
        ];
    }
}
