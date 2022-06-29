<?php

namespace Partymeister\Competitions\Http\Resources;

use Motor\Backend\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="OptionGroupResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Graphics"
 *   ),
 *   @OA\Property(
 *     property="type",
 *     type="string",
 *     example="single_choice"
 *   ),
 *   @OA\Property(
 *     property="options",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/OptionResource"
 *     )
 *   ),
 * )
 */
class OptionGroupResource extends BaseResource
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
            'id'      => (int) $this->id,
            'name'    => $this->name,
            'type'    => $this->type,
            'options' => OptionResource::collection($this->whenLoaded('options')),
        ];
    }
}
