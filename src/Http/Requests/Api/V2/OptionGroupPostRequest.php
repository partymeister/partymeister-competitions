<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class OptionGroupPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,multiple',
            'options' => 'nullable|array',
            'options.*.name' => 'required_with:options|string|max:255',
        ];
    }
}
