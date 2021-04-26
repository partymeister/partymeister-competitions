<?php

namespace Partymeister\Competitions\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="LiveVoteResource",
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
 *     property="sort_position",
 *     type="integer",
 *     example="5"
 *   ),
 *   @OA\Property(
 *     property="title",
 *     type="string",
 *     example="Great Entry"
 *   ),
 *   @OA\Property(
 *     property="author",
 *     type="string",
 *     example="Grate Artiste"
 *   ),
 * )
 */
class LiveVoteResource extends JsonResource
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
            'competition_ud' => (int) $this->competition_id,
            'entry_id'       => (int) $this->entry_id,
            'sort_position'  => (int) $this->sort_position,
            'author'         => $this->author,
            'title'          => $this->title,
        ];
    }
}
