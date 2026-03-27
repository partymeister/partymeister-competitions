<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionPrizePatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id' => 'sometimes|required|exists:competitions,id',
            'amount' => 'sometimes|nullable|string|max:255',
            'additional' => 'sometimes|nullable|string|max:255',
            'rank' => 'sometimes|required|integer|min:1',
        ];
    }
}
