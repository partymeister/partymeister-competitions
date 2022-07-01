<?php

namespace Partymeister\Competitions\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class ManualVoteRequest
 */
class ManualVoteRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="ManualVoteRequest",
     *   @OA\Property(
     *     property="competition_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="entry_id",
     *     type="integer",
     *     example="2"
     *   ),
     *   @OA\Property(
     *     property="points",
     *     type="integer",
     *     example="3"
     *   ),
     *   required={"competition_id", "entry_id", "points"},
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
            'entry' => 'required|array',
        ];
    }
}
