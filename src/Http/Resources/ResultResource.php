<?php

namespace Partymeister\Competitions\Http\Resources;

use Illuminate\Http\Request;
use Motor\Admin\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="ResultResource",
 *
 *   @OA\Property(
 *     property="tbd",
 *     type="string",
 *     example="to be done"
 *   ),
 * )
 */
class ResultResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'tbd' => 'to be done',
        ];
    }
}
