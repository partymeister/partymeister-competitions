<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionPrizePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id' => 'required|exists:competitions,id',
            'amount'         => 'nullable|string|max:255',
            'additional'     => 'nullable|string|max:255',
            'rank'           => 'required|integer|min:1',
        ];
    }
}
