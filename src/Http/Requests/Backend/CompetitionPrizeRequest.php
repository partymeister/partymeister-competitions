<?php

namespace Partymeister\Competitions\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class CompetitionPrizeRequest
 */
class CompetitionPrizeRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="CompetitionPrizeRequest",
     *   @OA\Property(
     *     property="competition_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="amount",
     *     type="string",
     *     example="200"
     *   ),
     *   @OA\Property(
     *     property="additional",
     *     type="string",
     *     example="Additional prizes like a GPU!"
     *   ),
     *   @OA\Property(
     *     property="rank",
     *     type="string",
     *     example="1"
     *   ),
     *   required={"competition_id", "rank"},
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
            'amount'     => 'array',
            'additional' => 'array',
            'rank'       => 'array',
        ];
    }
}
