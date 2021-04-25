<?php

namespace Partymeister\Competition\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *   schema="ResultResource",
 *   @OA\Property(
 *     property="tbd",
 *     type="string",
 *     example="to be done"
 *   ),
 * )
 */
class ResultResource extends JsonResource
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
            'tbd' => 'to be doneE'
        ];
    }
}
