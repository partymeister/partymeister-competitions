<?php

namespace Partymeister\Competitions\Http\Requests\Backend\AccessKey;

use Motor\Backend\Http\Requests\Request;

/**
 * Class GenerateRequest
 */
class GenerateRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="AccessKeyGenerateRequest",
     *   @OA\Property(
     *     property="quantity",
     *     type="integer",
     *     example="500"
     *   ),
     *   required={"quantity"},
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
            'quantity' => 'required|integer',
        ];
    }
}
