<?php

namespace Partymeister\Competitions\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class CompetitionRequest
 */
class CompetitionRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="CompetitionRequest",
     *
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Amiga Demo"
     *   ),
     *   @OA\Property(
     *     property="competition_type_id",
     *     type="integer",
     *     example="1"
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
     *
     *     @OA\Items(
     *
     *       @OA\Property(
     *         property="option_group_id",
     *         type="integer",
     *         example="1"
     *       ),
     *     )
     *   ),
     *   @OA\Property(
     *     property="vote_category_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="video_1",
     *     type="file",
     *     example="video_1.mp4"
     *   ),
     *   @OA\Property(
     *     property="video_2",
     *     type="file",
     *     example="video_2.mp4"
     *   ),
     *   @OA\Property(
     *     property="video_3",
     *     type="file",
     *     example="video_3.mp4"
     *   ),
     *   required={"name", "competition_type_id"},
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
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required',
                    'competition_type_id' => 'required|integer',
                    'has_prizegiving' => 'nullable|boolean',
                    'sort_position' => 'nullable|integer',
                    'prizegiving_sort_position' => 'nullable|integer',
                    'upload_enabled' => 'nullable|boolean',
                    'voting_enabled' => 'nullable|boolean',
                    'option_groups' => 'nullable|array',
                    'vote_category_id' => 'nullable|integer',
                    'video_1' => 'nullable|file',
                    'video_2' => 'nullable|file',
                    'video_3' => 'nullable|file',
                ];
            case 'PUT':
            case 'PATCH':
                return [];
        }
    }
}
