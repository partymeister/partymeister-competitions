<?php

namespace Partymeister\Competitions\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class LiveVoteRequest
 *
 * @package Partymeister\Competitions\Http\Requests\Backend
 */
class LiveVoteRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="LiveVoteRequest",
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
     *     property="sort_position",
     *     type="integer",
     *     example="5"
     *   ),
     *   @OA\Property(
     *     property="title",
     *     type="string",
     *     example="Great Entry"
     *   ),
     *   @OA\Property(
     *     property="author",
     *     type="string",
     *     example="Grate Artiste"
     *   ),
     *   required={"competition_id", "entry_id", "sort_position", "title", "author"},
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
            'competition_id' => 'required|integer',
            'entry_id'       => 'required|integer',
            'sort_position'  => 'required|integer',
            'title'          => 'required',
            'author'         => 'required',
        ];
    }
}
