<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class VotePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id'   => 'required|exists:competitions,id',
            'entry_id'         => 'required|exists:entries,id',
            'visitor_id'       => 'nullable|exists:visitors,id',
            'vote_category_id' => 'required|exists:vote_categories,id',
            'points'           => 'required|integer',
            'special_vote'     => 'nullable|boolean',
            'comment'          => 'nullable|string',
            'ip_address'       => 'nullable|string|max:255',
        ];
    }
}
