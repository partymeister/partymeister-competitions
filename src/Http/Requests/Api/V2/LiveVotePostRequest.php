<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class LiveVotePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id' => 'required|exists:competitions,id',
            'entry_id'       => 'required|exists:entries,id',
            'sort_position'  => 'required|integer',
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
        ];
    }
}
