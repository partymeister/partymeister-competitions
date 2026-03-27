<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class EntryPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'competition_id'                          => 'required|exists:competitions,id',
            'title'                                   => 'required|string|max:255',
            'author'                                  => 'required|string|max:255',
            'filesize'                                => 'nullable|string|max:255',
            'platform'                                => 'nullable|string|max:255',
            'description'                             => 'nullable|string',
            'organizer_description'                   => 'nullable|string',
            'running_time'                            => 'nullable|string|max:255',
            'custom_option'                           => 'nullable|string|max:255',
            'sort_position'                           => 'nullable|integer',
            'status'                                  => 'nullable|integer',
            'ip_address'                              => 'nullable|string|max:255',
            'allow_release'                           => 'nullable|boolean',
            'is_remote'                               => 'nullable|boolean',
            'is_recorded'                             => 'nullable|boolean',
            'is_prepared'                             => 'nullable|boolean',
            'upload_enabled'                          => 'nullable|boolean',
            'discord_name'                            => 'nullable|string|max:255',
            'has_explicit_content'                    => 'nullable|boolean',
            'needs_content_check'                     => 'nullable|boolean',
            'notify_about_status'                     => 'nullable|boolean',
            'representative'                          => 'nullable|string|max:255',
            'ai_usage'                                => 'nullable|string|max:255',
            'ai_usage_description'                    => 'nullable|string',
            'engine_option'                           => 'nullable|string|max:255',
            'engine_option_description'               => 'nullable|string',
            'author_name'                             => 'nullable|string|max:255',
            'author_email'                            => 'nullable|email|max:255',
            'author_phone'                            => 'nullable|string|max:255',
            'author_address'                          => 'nullable|string|max:255',
            'author_zip'                              => 'nullable|string|max:255',
            'author_city'                             => 'nullable|string|max:255',
            'author_country_iso_3166_1'               => 'nullable|string|max:2',
            'composer_name'                           => 'nullable|string|max:255',
            'composer_email'                          => 'nullable|email|max:255',
            'composer_phone'                          => 'nullable|string|max:255',
            'composer_address'                        => 'nullable|string|max:255',
            'composer_zip'                            => 'nullable|string|max:255',
            'composer_city'                           => 'nullable|string|max:255',
            'composer_country_iso_3166_1'             => 'nullable|string|max:2',
            'composer_not_member_of_copyright_collective' => 'nullable|boolean',
            'final_file_media_id'                     => 'nullable|integer',
        ];
    }
}
