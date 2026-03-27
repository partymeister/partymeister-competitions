<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class EntryPatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id' => 'sometimes|required|exists:competitions,id',
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
            'filesize' => 'sometimes|nullable|string|max:255',
            'platform' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|string',
            'organizer_description' => 'sometimes|nullable|string',
            'running_time' => 'sometimes|nullable|string|max:255',
            'custom_option' => 'sometimes|nullable|string|max:255',
            'sort_position' => 'sometimes|nullable|integer',
            'status' => 'sometimes|nullable|integer',
            'ip_address' => 'sometimes|nullable|string|max:255',
            'allow_release' => 'sometimes|nullable|boolean',
            'is_remote' => 'sometimes|nullable|boolean',
            'is_recorded' => 'sometimes|nullable|boolean',
            'is_prepared' => 'sometimes|nullable|boolean',
            'upload_enabled' => 'sometimes|nullable|boolean',
            'discord_name' => 'sometimes|nullable|string|max:255',
            'has_explicit_content' => 'sometimes|nullable|boolean',
            'needs_content_check' => 'sometimes|nullable|boolean',
            'notify_about_status' => 'sometimes|nullable|boolean',
            'representative' => 'sometimes|nullable|string|max:255',
            'ai_usage' => 'sometimes|nullable|string|max:255',
            'ai_usage_description' => 'sometimes|nullable|string',
            'engine_option' => 'sometimes|nullable|string|max:255',
            'engine_option_description' => 'sometimes|nullable|string',
            'author_name' => 'sometimes|nullable|string|max:255',
            'author_email' => 'sometimes|nullable|email|max:255',
            'author_phone' => 'sometimes|nullable|string|max:255',
            'author_address' => 'sometimes|nullable|string|max:255',
            'author_zip' => 'sometimes|nullable|string|max:255',
            'author_city' => 'sometimes|nullable|string|max:255',
            'author_country_iso_3166_1' => 'sometimes|nullable|string|max:2',
            'composer_name' => 'sometimes|nullable|string|max:255',
            'composer_email' => 'sometimes|nullable|email|max:255',
            'composer_phone' => 'sometimes|nullable|string|max:255',
            'composer_address' => 'sometimes|nullable|string|max:255',
            'composer_zip' => 'sometimes|nullable|string|max:255',
            'composer_city' => 'sometimes|nullable|string|max:255',
            'composer_country_iso_3166_1' => 'sometimes|nullable|string|max:2',
            'composer_not_member_of_copyright_collective' => 'sometimes|nullable|boolean',
            'final_file_media_id' => 'sometimes|nullable|integer',
        ];
    }
}
