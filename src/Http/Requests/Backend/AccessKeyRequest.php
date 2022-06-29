<?php

namespace Partymeister\Competitions\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class AccessKeyRequest
 */
class AccessKeyRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="AccessKeyRequest",
     *   @OA\Property(
     *     property="visitor_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="access_key",
     *     type="string",
     *     example="REVI-SION"
     *   ),
     *   @OA\Property(
     *     property="ip_address",
     *     type="string",
     *     example="10.10.10.10"
     *   ),
     *   @OA\Property(
     *     property="registered_at",
     *     type="datetime",
     *     example="2021-05-28 12:00:00"
     *   ),
     *   required={"access_key"},
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
            'visitor_id'    => 'nullable|integer',
            'access_key'    => 'required',
            'ip_address'    => 'nullable',
            'registered_at' => 'nullable|date_format:Y-m-d H:i:s',
        ];
    }
}
