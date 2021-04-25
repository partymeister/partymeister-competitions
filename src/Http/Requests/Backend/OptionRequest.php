<?php

namespace Partymeister\Competition\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class OptionRequest
 *
 * @package Partymeister\Competition\Http\Requests\Backend
 */
class OptionRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="OptionRequest",
     *   @OA\Property(
     *     property="option_group_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="sort_position",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Intel CPU"
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
            'option_group_id' => 'nullable|integer',
            'sort_position'   => 'nullable|integer',
            'name'            => 'required',
        ];
    }
}
