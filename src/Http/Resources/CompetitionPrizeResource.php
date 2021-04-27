<?php

namespace Partymeister\Competitions\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="CompetitionPrizeResource",
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
 *     property="amount",
 *     type="string",
 *     example="200"
 *   ),
 *   @OA\Property(
 *     property="additional",
 *     type="string",
 *     example="Additional prizes like a GPU!"
 *   ),
 *   @OA\Property(
 *     property="rank",
 *     type="string",
 *     example="1"
 *   ),
 * )
 */
class CompetitionPrizeResource extends JsonResource
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
            'id'          => (int) $this->id,
            'competition' => new CompetitionResource($this->whenLoaded('competition')),
            'amount'      => $this->amount,
            'additional'  => $this->additional,
            'rank'        => $this->rank,
        ];
    }
}
