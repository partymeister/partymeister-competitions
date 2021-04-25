<?php

namespace Partymeister\Competition\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class CompetitionTypeRequest
 *
 * @package Partymeister\Competition\Http\Requests\Backend
 */
class CompetitionTypeRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="CompetitionTypeRequest",
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
     *   required={"name"},
     * )
     */

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required',
            'has_platform'          => 'nullable|boolean',
            'has_filesize'          => 'nullable|boolean',
            'has_screenshot'        => 'nullable|boolean',
            'has_video'             => 'nullable|boolean',
            'has_audio'             => 'nullable|boolean',
            'has_recordings'        => 'nullable|boolean',
            'has_composer'          => 'nullable|boolean',
            'has_running_time'      => 'nullable|boolean',
            'is_anonymous'          => 'nullable|boolean',
            'number_of_work_stages' => 'nullable|integer',
            'has_remote_entries'    => 'nullable|boolean',
            'file_is_optional'      => 'nullable|boolean',
            'has_config_file'       => 'nullable|boolean',
        ];
    }
}
