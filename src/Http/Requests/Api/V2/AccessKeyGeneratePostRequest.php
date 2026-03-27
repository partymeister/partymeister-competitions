<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class AccessKeyGeneratePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|integer|min:1|max:2000',
        ];
    }
}
