<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class OptionGroupPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'sometimes|required|string|max:255',
            'type'           => 'sometimes|required|in:single,multiple',
            'options'        => 'sometimes|nullable|array',
            'options.*.name' => 'required_with:options|string|max:255',
        ];
    }
}
