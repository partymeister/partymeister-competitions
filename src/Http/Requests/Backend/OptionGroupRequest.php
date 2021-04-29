<?php

namespace Partymeister\Competitions\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class OptionGroupRequest
 *
 * @package Partymeister\Competitions\Http\Requests\Backend
 */
class OptionGroupRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="OptionGroupRequest",
     *   @OA\Property(
     *     property="name",
     *     type="string",
     *     example="Graphics"
     *   ),
     *   @OA\Property(
     *     property="type",
     *     type="string",
     *     example="single_choice"
     *   ),
     *   @OA\Property(
     *     property="options",
     *     type="array",
     *     @OA\Items(
     *       ref="#/components/schemas/OptionRequest"
     *     )
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
            'name'    => 'required',
            'type'    => 'required|in:'.implode(',', array_flip(trans('partymeister-core::backend/option_groups.types'))),
            'options' => 'nullable|array',
        ];
    }
}
