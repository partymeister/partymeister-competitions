<?php

namespace Partymeister\Competitions\Http\Resources;

use Motor\Backend\Http\Resources\BaseResource;

/**
 * @OA\Schema(
 *   schema="CompetitionTypeResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="name",
 *     type="string",
 *     example="Graphics competition"
 *   ),
 *   @OA\Property(
 *     property="has_platform",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_filesize",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_screenshot",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_video",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_audio",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_recordings",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_composer",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_running_time",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="is_anonymous",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_remote_entries",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="file_is_optional",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="has_config_file",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="number_of_work_stages",
 *     type="intger",
 *     example="4"
 *   ),
 * )
 */
class CompetitionTypeResource extends BaseResource
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
            'id'                    => (int) $this->id,
            'name'                  => $this->name,
            'has_platform'          => (bool) $this->has_platform,
            'has_filesize'          => (bool) $this->has_platform,
            'has_screenshot'        => (bool) $this->has_platform,
            'has_video'             => (bool) $this->has_platform,
            'has_audio'             => (bool) $this->has_platform,
            'has_recordings'        => (bool) $this->has_platform,
            'has_composer'          => (bool) $this->has_platform,
            'has_running_time'      => (bool) $this->has_platform,
            'is_anonymous'          => (bool) $this->has_platform,
            'number_of_work_stages' => (int) $this->number_of_work_stages,
            'has_remote_entries'    => (bool) $this->has_platform,
            'file_is_optional'      => (bool) $this->has_platform,
            'has_config_file'       => (bool) $this->has_platform,
        ];
    }
}
