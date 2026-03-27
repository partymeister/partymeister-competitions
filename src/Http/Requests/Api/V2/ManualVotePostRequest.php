<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class ManualVotePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id' => 'required|exists:competitions,id',
            'entry_id' => 'required|exists:entries,id',
            'points' => 'required|integer',
            'ip_address' => 'nullable|string|max:255',
        ];
    }
}
