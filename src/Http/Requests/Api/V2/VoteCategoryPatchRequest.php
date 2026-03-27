<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class VoteCategoryPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => 'sometimes|required|string|max:255',
            'points'           => 'sometimes|required|integer|min:1',
            'has_negative'     => 'sometimes|nullable|boolean',
            'has_comment'      => 'sometimes|nullable|boolean',
            'has_special_vote' => 'sometimes|nullable|boolean',
        ];
    }
}
