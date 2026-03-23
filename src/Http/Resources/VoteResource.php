<?php

namespace Partymeister\Competitions\Http\Resources;

use Motor\Admin\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="VoteResource",
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
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="visitor_id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="vote_category_id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="special_vote",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="comment",
 *     type="string",
 *     example="Fantastic entry"
 *   ),
 *   @OA\Property(
 *     property="points",
 *     type="integer",
 *     example="4"
 *   ),
 *   @OA\Property(
 *     property="ip_address",
 *     type="string",
 *     example="10.10.10.10"
 *   ),
 * )
 */
class VoteResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'               => (int) $this->id,
            'competition_id'   => (int) $this->competition_id,
            'entry_id'         => (int) $this->entry_id,
            'visitor_id'       => (int) $this->visitor_id,
            'vote_category_id' => (int) $this->vote_category_id,
            'special_vote'     => (bool) $this->special_vote,
            'comment'          => $this->comment,
            'points'           => (int) $this->points,
            'ip_address'       => $this->ip_address,
        ];
    }
}
