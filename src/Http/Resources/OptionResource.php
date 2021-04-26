<?php

namespace Partymeister\Competitions\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="OptionResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="option_group_id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="sort_position",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Intel CPU"
 *   ),
 * )
 */
class OptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $comesFromOptionEndpoint = ($request->route()
                                            ->uri() === 'api/options') ? true : false;

        return [
            'id'            => (int) $this->id,
            'option_group'  => $this->when(! $comesFromOptionEndpoint, new OptionGroupResource($this->option_group)),
            'sort_position' => (int) $this->sort_position,
            'name'          => $this->name,
        ];
    }
}
