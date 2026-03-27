<?php

namespace Partymeister\Competitions\Http\Requests\Api\V2;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionTypePatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                          => 'sometimes|required|string|max:255',
            'has_platform'                  => 'sometimes|nullable|boolean',
            'has_filesize'                  => 'sometimes|nullable|boolean',
            'has_screenshot'                => 'sometimes|nullable|boolean',
            'has_video'                     => 'sometimes|nullable|boolean',
            'has_audio'                     => 'sometimes|nullable|boolean',
            'has_recordings'                => 'sometimes|nullable|boolean',
            'has_composer'                  => 'sometimes|nullable|boolean',
            'has_running_time'              => 'sometimes|nullable|boolean',
            'is_anonymous'                  => 'sometimes|nullable|boolean',
            'number_of_work_stages'         => 'sometimes|nullable|integer|min:0',
            'has_remote_entries'            => 'sometimes|nullable|boolean',
            'file_is_optional'              => 'sometimes|nullable|boolean',
            'has_config_file'               => 'sometimes|nullable|boolean',
            'has_ai_options'                => 'sometimes|nullable|boolean',
            'has_engine_options'            => 'sometimes|nullable|boolean',
            'has_out_of_competition_voting' => 'sometimes|nullable|boolean',
        ];
    }
}
