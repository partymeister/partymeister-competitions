<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionTypePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                          => 'required|string|max:255',
            'has_platform'                  => 'nullable|boolean',
            'has_filesize'                  => 'nullable|boolean',
            'has_screenshot'                => 'nullable|boolean',
            'has_video'                     => 'nullable|boolean',
            'has_audio'                     => 'nullable|boolean',
            'has_recordings'                => 'nullable|boolean',
            'has_composer'                  => 'nullable|boolean',
            'has_running_time'              => 'nullable|boolean',
            'is_anonymous'                  => 'nullable|boolean',
            'number_of_work_stages'         => 'nullable|integer|min:0',
            'has_remote_entries'            => 'nullable|boolean',
            'file_is_optional'              => 'nullable|boolean',
            'has_config_file'               => 'nullable|boolean',
            'has_ai_options'                => 'nullable|boolean',
            'has_engine_options'            => 'nullable|boolean',
            'has_out_of_competition_voting' => 'nullable|boolean',
        ];
    }
}
