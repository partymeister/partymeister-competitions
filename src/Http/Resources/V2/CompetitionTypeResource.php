<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\CompetitionType;

/**
 * @mixin CompetitionType
 */
class CompetitionTypeResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id'                           => (int) $this->id,
            'name'                         => $this->name,
            'has_platform'                 => (bool) $this->has_platform,
            'has_filesize'                 => (bool) $this->has_filesize,
            'has_screenshot'               => (bool) $this->has_screenshot,
            'has_video'                    => (bool) $this->has_video,
            'has_audio'                    => (bool) $this->has_audio,
            'has_recordings'               => (bool) $this->has_recordings,
            'has_composer'                 => (bool) $this->has_composer,
            'has_running_time'             => (bool) $this->has_running_time,
            'is_anonymous'                 => (bool) $this->is_anonymous,
            'number_of_work_stages'        => (int) $this->number_of_work_stages,
            'has_remote_entries'           => (bool) $this->has_remote_entries,
            'file_is_optional'             => (bool) $this->file_is_optional,
            'has_config_file'              => (bool) $this->has_config_file,
            'has_ai_options'               => (bool) $this->has_ai_options,
            'has_engine_options'           => (bool) $this->has_engine_options,
            'has_out_of_competition_voting' => (bool) $this->has_out_of_competition_voting,
            'created_at'                   => $this->created_at?->toIso8601String(),
            'updated_at'                   => $this->updated_at?->toIso8601String(),
        ];
    }
}
