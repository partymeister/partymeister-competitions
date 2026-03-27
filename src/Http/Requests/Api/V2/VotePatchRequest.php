<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class VotePatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id'   => 'sometimes|required|exists:competitions,id',
            'entry_id'         => 'sometimes|required|exists:entries,id',
            'visitor_id'       => 'sometimes|nullable|exists:visitors,id',
            'vote_category_id' => 'sometimes|required|exists:vote_categories,id',
            'points'           => 'sometimes|required|integer',
            'special_vote'     => 'sometimes|nullable|boolean',
            'comment'          => 'sometimes|nullable|string',
            'ip_address'       => 'sometimes|nullable|string|max:255',
        ];
    }
}
