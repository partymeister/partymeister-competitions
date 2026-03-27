<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'competition_type_id' => 'required|exists:competition_types,id',
            'has_prizegiving' => 'nullable|boolean',
            'upload_enabled' => 'nullable|boolean',
            'voting_enabled' => 'nullable|boolean',
            'sort_position' => 'nullable|integer',
            'prizegiving_sort_position' => 'nullable|integer',
            'vote_categories' => 'nullable|array',
            'vote_categories.*' => 'exists:vote_categories,id',
            'option_groups' => 'nullable|array',
            'option_groups.*' => 'exists:option_groups,id',
        ];
    }
}
