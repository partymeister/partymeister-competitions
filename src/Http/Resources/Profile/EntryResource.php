<?php

namespace Partymeister\Competitions\Http\Resources\Profile;

use Illuminate\Support\Facades\Session;
use Motor\Backend\Helpers\Filesize;
use Motor\Backend\Http\Resources\BaseResource;
use Motor\Backend\Http\Resources\MediaResource;
use Partymeister\Competitions\Http\Resources\OptionResource;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Core\Http\Resources\VisitorResource;

/**
 * @OA\Schema(
 *   schema="ProfileEntryResource",
 *   @OA\Property(
 *     property="id",
 *     type="integer",
 *     example="1"
 *   ),
 *   @OA\Property(
 *     property="title",
 *     type="string",
 *     example="Great entry name"
 *   ),
 *   @OA\Property(
 *     property="competition_name",
 *     type="string",
 *     example="PC Demo"
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
 *     property="screenshot",
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
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                          => (int) $this->id,
            'competition_name'            => $this->competition->name,
            'title'                       => $this->title,
            'author'                      => $this->author,
            'filesize'                    => (int) $this->filesize,
            'filesize_human'              => Filesize::bytesToHuman((int) $this->filesize),
            'platform'                    => $this->platform,
            'description'                 => $this->description,
            'organizer_description'       => $this->organizer_description,
            'running_time'                => $this->running_time,
            'options'                     => OptionResource::collection($this->options),
            'custom_option'               => $this->custom_option,
            'author_name'                 => $this->author_name,
            'author_email'                => $this->author_email,
            'author_phone'                => $this->author_phone,
            'author_address'              => $this->author_address,
            'author_zip'                  => $this->author_zip,
            'author_city'                 => $this->author_zip,
            'author_country_iso_3166_1'   => $this->author_country_iso_3166_1,
            'composer_name'               => $this->composer_name,
            'composer_email'              => $this->composer_email,
            'composer_phone'              => $this->composer_phone,
            'composer_address'            => $this->composer_address,
            'composer_zip'                => $this->composer_zip,
            'composer_city'               => $this->composer_city,
            'composer_country_iso_3166_1' => $this->composer_country_iso_3166_1,
            'discord_name'                => $this->discord_name,
            'screenshot'                  => new MediaResource($this->getFirstMedia('screenshot')),
            'has_composer'                => (bool) $this->competition->competition_type->has_composer,
            'has_screenshot'              => (bool) $this->competition->competition_type->has_screenshot,
        ];
    }
}
