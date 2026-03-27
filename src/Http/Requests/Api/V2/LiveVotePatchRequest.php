<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class LiveVotePatchRequest extends FormRequest
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
            'sort_position'  => 'sometimes|required|integer',
            'title'          => 'sometimes|required|string|max:255',
            'author'         => 'sometimes|required|string|max:255',
        ];
    }
}
