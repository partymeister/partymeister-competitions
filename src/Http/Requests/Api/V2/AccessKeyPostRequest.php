<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class AccessKeyPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'access_key'    => 'required|string|max:255',
            'ip_address'    => 'nullable|string|max:255',
            'registered_at' => 'nullable|date',
            'is_remote'     => 'nullable|boolean',
            'is_satellite'  => 'nullable|boolean',
            'is_prepaid'    => 'nullable|boolean',
            'visitor_id'    => 'nullable|exists:visitors,id',
        ];
    }
}
