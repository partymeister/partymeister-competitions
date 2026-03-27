<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class AccessKeyPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'access_key' => 'sometimes|required|string|max:255',
            'ip_address' => 'sometimes|nullable|string|max:255',
            'registered_at' => 'sometimes|nullable|date',
            'is_remote' => 'sometimes|nullable|boolean',
            'is_satellite' => 'sometimes|nullable|boolean',
            'is_prepaid' => 'sometimes|nullable|boolean',
            'visitor_id' => 'sometimes|nullable|exists:visitors,id',
        ];
    }
}
