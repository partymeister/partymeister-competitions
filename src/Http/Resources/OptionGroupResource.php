<?php

namespace Partymeister\Competition\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
class OptionGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $comesFromOptionGroupsEndpoint = ($request->route()
                                                  ->uri() === 'api/option_groups') ? true : false;

        return [
            'id'      => (int) $this->id,
            'name'    => $this->name,
            'type'    => $this->type,
            'options' => $this->when(! $comesFromOptionGroupsEndpoint, OptionResource::collection($this->options)),
        ];
    }
}
