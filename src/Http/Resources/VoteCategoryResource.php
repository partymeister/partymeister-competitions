<?php

namespace Partymeister\Competitions\Http\Resources;

use Motor\Backend\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="VoteCategoryResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Default vote category"
 *   ),
 *   @OA\Property(
 *     property="points",
 *     type="integer",
 *     example="5"
 *   ),
 *   @OA\Property(
 *     property="has_negative",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_comment",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_special_vote",
 *     type="boolean",
 *     example="true"
 *   ),
 * )
 */
class VoteCategoryResource extends BaseResource
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
            'id'               => (int) $this->id,
            'name'             => $this->name,
            'points'           => (int) $this->points,
            'has_negative'     => (boolean) $this->has_negative,
            'has_comment'      => (boolean) $this->has_comment,
            'has_special_vote' => (boolean) $this->has_special_vote,
        ];
    }
}
