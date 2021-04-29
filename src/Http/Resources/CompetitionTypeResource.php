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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    => (int) $this->id,
            'name'                  => $this->name,
            'has_platform'          => (boolean) $this->has_platform,
            'has_filesize'          => (boolean) $this->has_platform,
            'has_screenshot'        => (boolean) $this->has_platform,
            'has_video'             => (boolean) $this->has_platform,
            'has_audio'             => (boolean) $this->has_platform,
            'has_recordings'        => (boolean) $this->has_platform,
            'has_composer'          => (boolean) $this->has_platform,
            'has_running_time'      => (boolean) $this->has_platform,
            'is_anonymous'          => (boolean) $this->has_platform,
            'number_of_work_stages' => (int) $this->number_of_work_stages,
            'has_remote_entries'    => (boolean) $this->has_platform,
            'file_is_optional'      => (boolean) $this->has_platform,
            'has_config_file'       => (boolean) $this->has_platform,
        ];
    }
}
