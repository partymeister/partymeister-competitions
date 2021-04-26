<?php

namespace Partymeister\Competitions\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Motor\Media\Http\Resources\FileResource;

/**
 * @OA\Schema(
 *   schema="CompetitionResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Amiga Demo"
 *   ),
 *   @OA\Property(
 *     property="competition_type",
 *     type="object",
 *     ref="#/components/schemas/CompetitionTypeResource"
 *   ),
 *   @OA\Property(
 *     property="has_prizegiving",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="voting_enabled",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="upload_enabled",
 *     type="boolean",
 *     example="false"
 *   ),
 *   @OA\Property(
 *     property="sort_position",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="prizegiving_sort_position",
 *     type="integer",
 *     example="20"
 *   ),
 *   @OA\Property(
 *     property="option_groups",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/OptionGroupResource"
 *     )
 *   ),
 *   @OA\Property(
 *     property="vote_categories",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/VoteCategoryResource"
 *     ),
 *   ),
 *   @OA\Property(
 *     property="video_1",
 *     type="object",
 *     ref="#/components/schemas/FileResource"
 *   ),
 *   @OA\Property(
 *     property="video_2",
 *     type="object",
 *     ref="#/components/schemas/FileResource"
 *   ),
 *   @OA\Property(
 *     property="video_3",
 *     type="object",
 *     ref="#/components/schemas/FileResource"
 *   ),
 *   @OA\Property(
 *     property="prizes",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/CompetitionPrizeResource"
 *     ),
 *   ),
 *   @OA\Property(
 *     property="entries",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/EntryResource"
 *     ),
 *   ),
 * )
 */
class CompetitionResource extends JsonResource
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
            'id'                        => (int) $this->id,
            'name'                      => $this->name,
            'competition_type'          => new CompetitionTypeResource($this->competition_type),
            'has_prizegiving'           => (boolean) $this->has_prizegiving,
            'upload_enabled'            => (boolean) $this->upload_enabled,
            'voting_enabled'            => (boolean) $this->voting_enabled,
            'sort_position'             => (int) $this->sort_position,
            'prizegiving_sort_position' => (int) $this->prizegiving_sort_position,
            'vote_categories'           => VoteCategoryResource::collection($this->vote_categories),
            'option_groups'             => OptionGroupResource::collection($this->option_groups),
            'video_1'                   => new FileResource($this->file_associations()
                                                                 ->where('identifier', 'video_1')
                                                                 ->first()),
            'video_2'                   => new FileResource($this->file_associations()
                                                                 ->where('identifier', 'video_2')
                                                                 ->first()),
            'video_3'                   => new FileResource($this->file_associations()
                                                                 ->where('identifier', 'video_3')
                                                                 ->first()),
            'prizes'                    => CompetitionPrizeResource::collection($this->prizes),
            'entries'                   => EntryResource::collection($this->entries),
        ];
    }
}
