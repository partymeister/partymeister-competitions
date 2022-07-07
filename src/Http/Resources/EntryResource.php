<?php

namespace Partymeister\Competitions\Http\Resources;

use Motor\Admin\Helpers\Filesize;
use Motor\Admin\Http\Resources\BaseResource;
use Motor\Admin\Http\Resources\MediaResource;
use Partymeister\Core\Http\Resources\VisitorResource;

/**
 * @OA\Schema(
 *   schema="EntryResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="competition",
 *     type="object",
 *     ref="#/components/schemas/CompetitionResource"
 *   ),
 *   @OA\Property(
 *     property="visitor",
 *     type="object",
 *     ref="#/components/schemas/VisitorResource"
 *   ),
 *   @OA\Property(
 *     property="ip_address",
 *     type="string",
 *     example="10.10.10.10"
 *   ),
 *   @OA\Property(
 *     property="sort_position",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="sort_position_prefixed",
 *     type="string",
 *     example="01"
 *   ),
 *   @OA\Property(
 *     property="title",
 *     type="string",
 *     example="Great entry name"
 *   ),
 *   @OA\Property(
 *     property="author",
 *     type="string",
 *     example="Capable author"
 *   ),
 *   @OA\Property(
 *     property="filesize",
 *     type="string",
 *     example="31337 bytes"
 *   ),
 *   @OA\Property(
 *     property="platform",
 *     type="string",
 *     example="C64"
 *   ),
 *   @OA\Property(
 *     property="description",
 *     type="string",
 *     example="Credits, tools used, etc."
 *   ),
 *   @OA\Property(
 *     property="organizer_description",
 *     type="string",
 *     example="Please run this on an actual Amiga!"
 *   ),
 *   @OA\Property(
 *     property="running_time",
 *     type="string",
 *     example="2m30s"
 *   ),
 *   @OA\Property(
 *     property="custom_option",
 *     type="string",
 *     example="Custom built CPU"
 *   ),
 *   @OA\Property(
 *     property="allow_release",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="is_remote",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="is_recorded",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="is_prepared",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="upload_enabled",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="composer_not_member_of_copyright_collective",
 *     type="boolean",
 *     example="true"
 *   ),
 *   @OA\Property(
 *     property="final_file_media_id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="discord_name",
 *     type="string",
 *     example="SomePerson#1234"
 *   ),
 *   @OA\Property(
 *     property="status",
 *     type="integer",
 *     example="3"
 *   ),
 *   @OA\Property(
 *     property="author_name",
 *     type="string",
 *     example="Some Person"
 *   ),
 *   @OA\Property(
 *     property="author_email",
 *     type="string",
 *     example="some@person.com"
 *   ),
 *   @OA\Property(
 *     property="author_phone",
 *     type="string",
 *     example="+1 123 909090"
 *   ),
 *   @OA\Property(
 *     property="author_address",
 *     type="string",
 *     example="15 Entry lane"
 *   ),
 *   @OA\Property(
 *     property="author_zip",
 *     type="string",
 *     example="12345"
 *   ),
 *   @OA\Property(
 *     property="author_city",
 *     type="string",
 *     example="Compotown"
 *   ),
 *   @OA\Property(
 *     property="author_country_iso_3166_1",
 *     type="string",
 *     example="US"
 *   ),
 *   @OA\Property(
 *     property="composer_name",
 *     type="string",
 *     example="Some Musician"
 *   ),
 *   @OA\Property(
 *     property="composer_email",
 *     type="string",
 *     example="some@musician.com"
 *   ),
 *   @OA\Property(
 *     property="composer_phone",
 *     type="string",
 *     example="+1 123 909091"
 *   ),
 *   @OA\Property(
 *     property="composer_address",
 *     type="string",
 *     example="15 Composer lane"
 *   ),
 *   @OA\Property(
 *     property="composer_zip",
 *     type="string",
 *     example="12345"
 *   ),
 *   @OA\Property(
 *     property="composer_city",
 *     type="string",
 *     example="Musicville"
 *   ),
 *   @OA\Property(
 *     property="composer_country_iso_3166_1",
 *     type="string",
 *     example="US"
 *   ),
 *   @OA\Property(
 *     property="file",
 *     type="array",
 *     @OA\Items(
 *       ref="#/components/schemas/MediaResource"
 *     ),
 *   ),
 *   @OA\Property(
 *     property="screenshot",
 *     type="object",
 *     ref="#/components/schemas/MediaResource"
 *   ),
 *   @OA\Property(
 *     property="video",
 *     type="object",
 *     ref="#/components/schemas/MediaResource"
 *   ),
 *   @OA\Property(
 *     property="audio",
 *     type="object",
 *     ref="#/components/schemas/MediaResource"
 *   ),
 *   @OA\Property(
 *     property="config_file",
 *     type="object",
 *     ref="#/components/schemas/MediaResource"
 *   ),
 * )
 */
class EntryResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $workStages = $this->media()
                           ->where('collection_name', 'LIKE', 'work_stage%')
                           ->orderBy('collection_name');

        return [
            'id'                                          => (int) $this->id,
            'competition'                                 => new CompetitionResource($this->whenLoaded('competition')),
            'visitor'                                     => new VisitorResource($this->visitor),
            'ip_address'                                  => $this->ip_address,
            'sort_position'                               => (int) $this->sort_position,
            'sort_position_prefixed'                      => (strlen($this->sort_position) == 1 ? '0'.$this->sort_position : $this->sort_position),
            'title'                                       => $this->title,
            'author'                                      => $this->author,
            'filesize'                                    => (int) $this->filesize,
            'filesize_human'                              => Filesize::bytesToHuman((int) $this->filesize),
            'platform'                                    => $this->platform,
            'description'                                 => $this->description,
            'organizer_description'                       => $this->organizer_description,
            'running_time'                                => $this->running_time,
            'custom_option'                               => $this->custom_option,
            'allow_release'                               => (bool) $this->allow_release,
            'is_remote'                                   => (bool) $this->is_remote,
            'is_recorded'                                 => (bool) $this->is_recorded,
            'is_prepared'                                 => (bool) $this->is_prepared,
            'upload_enabled'                              => (bool) $this->upload_enabled,
            'composer_not_member_of_copyright_collective' => (bool) $this->composer_not_member_of_copyright_collective,
            'author_name'                                 => $this->author_name,
            'author_email'                                => $this->author_email,
            'author_phone'                                => $this->author_phone,
            'author_address'                              => $this->author_address,
            'author_zip'                                  => $this->author_zip,
            'author_city'                                 => $this->author_zip,
            'author_country_iso_3166_1'                   => $this->author_country_iso_3166_1,
            'composer_name'                               => $this->composer_name,
            'composer_email'                              => $this->composer_email,
            'composer_phone'                              => $this->composer_phone,
            'composer_address'                            => $this->composer_address,
            'composer_zip'                                => $this->composer_zip,
            'composer_city'                               => $this->composer_city,
            'composer_country_iso_3166_1'                 => $this->composer_country_iso_3166_1,
            'final_file_media_id'                         => 'nullable|integer',
            'status'                                      => (int) $this->status,
            'discord_name'                                => $this->discord_name,
            'file'                                        => MediaResource::collection($this->getMedia('files')),
            'screenshot'                                  => new MediaResource($this->getFirstMedia('screenshot')),
            'video'                                       => new MediaResource($this->getFirstMedia('video')),
            'audio'                                       => new MediaResource($this->getFirstMedia('audio')),
            'config_file'                                 => new MediaResource($this->getFirstMedia('config_file')),
            'work_stages'                                 => MediaResource::collection($workStages->get()),
        ];
    }
}
