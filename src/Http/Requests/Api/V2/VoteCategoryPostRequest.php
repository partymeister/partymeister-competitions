<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class VoteCategoryPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'points' => 'required|integer|min:1',
            'has_negative' => 'nullable|boolean',
            'has_comment' => 'nullable|boolean',
            'has_special_vote' => 'nullable|boolean',
        ];
    }
}
