<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class ManualVotePatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id' => 'sometimes|required|exists:competitions,id',
            'entry_id'       => 'sometimes|required|exists:entries,id',
            'points'         => 'sometimes|required|integer',
            'ip_address'     => 'sometimes|nullable|string|max:255',
        ];
    }
}
