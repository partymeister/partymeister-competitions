<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseResource;
use Partymeister\Competitions\Models\Entry;

/**
 * @mixin Entry
 */
class EntryResource extends BaseResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this->id,
            'identifier' => (int) $this->identifier,
            'title' => $this->title,
            'author' => $this->author,
            'filesize' => $this->filesize,
            'platform' => $this->platform,
            'description' => $this->description,
            'organizer_description' => $this->organizer_description,
            'running_time' => $this->running_time,
            'custom_option' => $this->custom_option,
            'sort_position' => (int) $this->sort_position,
            'status' => (int) $this->status,
            'ip_address' => $this->ip_address,
            'allow_release' => (bool) $this->allow_release,
            'is_remote' => (bool) $this->is_remote,
            'is_recorded' => (bool) $this->is_recorded,
            'is_prepared' => (bool) $this->is_prepared,
            'upload_enabled' => (bool) $this->upload_enabled,
            'discord_name' => $this->discord_name,
            'has_explicit_content' => (bool) $this->has_explicit_content,
            'needs_content_check' => (bool) $this->needs_content_check,
            'notify_about_status' => (bool) $this->notify_about_status,
            'representative' => $this->representative,
            'ai_usage' => $this->ai_usage,
            'ai_usage_description' => $this->ai_usage_description,
            'engine_option' => $this->engine_option,
            'engine_option_description' => $this->engine_option_description,
            'author_name' => $this->author_name,
            'author_email' => $this->author_email,
            'author_phone' => $this->author_phone,
            'author_address' => $this->author_address,
            'author_zip' => $this->author_zip,
            'author_city' => $this->author_city,
            'author_country_iso_3166_1' => $this->author_country_iso_3166_1,
            'composer_name' => $this->composer_name,
            'composer_email' => $this->composer_email,
            'composer_phone' => $this->composer_phone,
            'composer_address' => $this->composer_address,
            'composer_zip' => $this->composer_zip,
            'composer_city' => $this->composer_city,
            'composer_country_iso_3166_1' => $this->composer_country_iso_3166_1,
            'composer_not_member_of_copyright_collective' => (bool) $this->composer_not_member_of_copyright_collective,
            'final_file_media_id' => $this->final_file_media_id,
            'competition' => new CompetitionResource($this->whenLoaded('competition')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
