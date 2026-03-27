<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                     => 'sometimes|required|string|max:255',
            'competition_type_id'      => 'sometimes|required|exists:competition_types,id',
            'has_prizegiving'          => 'sometimes|nullable|boolean',
            'upload_enabled'           => 'sometimes|nullable|boolean',
            'voting_enabled'           => 'sometimes|nullable|boolean',
            'sort_position'            => 'sometimes|nullable|integer',
            'prizegiving_sort_position' => 'sometimes|nullable|integer',
            'vote_categories'          => 'sometimes|nullable|array',
            'vote_categories.*'        => 'exists:vote_categories,id',
            'option_groups'            => 'sometimes|nullable|array',
            'option_groups.*'          => 'exists:option_groups,id',
        ];
    }
}
