<?php

namespace Partymeister\Competition\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class VoteCategoryRequest
 *
 * @package Partymeister\Competition\Http\Requests\Backend
 */
class VoteCategoryRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="VoteCategoryRequest",
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Default vote category"
     *   ),
     *   @OA\Property(
     *     property="points",
     *     type="integer",
     *     example="5"
     *   ),
     *   @OA\Property(
     *     property="has_negative",
     *     type="boolean",
     *     example="true"
     *   ),
     *   @OA\Property(
     *     property="has_comment",
     *     type="boolean",
     *     example="true"
     *   ),
     *   @OA\Property(
     *     property="has_special_vote",
     *     type="boolean",
     *     example="true"
     *   ),
     *   required={"name", "points"},
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
            'name'             => 'required',
            'points'           => 'required|integer',
            'has_negative'     => 'nullable|boolean',
            'has_comment'      => 'nullable|boolean',
            'has_special_vote' => 'nullable|boolean',
        ];
    }
}
