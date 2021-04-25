<?php

namespace Partymeister\Competition\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class VoteRequest
 *
 * @package Partymeister\Competition\Http\Requests\Backend
 */
class VoteRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="VoteRequest",
     *   @OA\Property(
     *     property="competition_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="entry_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="visitor_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="vote_category_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="special_vote",
     *     type="boolean",
     *     example="true"
     *   ),
     *   @OA\Property(
     *     property="comment",
     *     type="string",
     *     example="Fantastic entry"
     *   ),
     *   @OA\Property(
     *     property="points",
     *     type="integer",
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
            'competition_id'   => 'required|integer',
            'entry_id'         => 'required|integer',
            'visitor_id'       => 'required|integer',
            'vote_category_id' => 'required|integer',
            'special_vote'     => 'nullable|boolean',
            'comment'          => 'nullable',
            'points'           => 'required|integer',
        ];
    }
}
