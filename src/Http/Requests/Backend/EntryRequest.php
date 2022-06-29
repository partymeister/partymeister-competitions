<?php

namespace Partymeister\Competitions\Http\Requests\Backend;

use Motor\Backend\Http\Requests\Request;

/**
 * Class EntryRequest
 */
class EntryRequest extends Request
{
    /**
     * @OA\Schema(
     *   schema="EntryRequest",
     *   @OA\Property(
     *     property="competition_id",
     *     type="integer",
     *     example="1"
     *   ),
     *   @OA\Property(
     *     property="visitor_id",
     *     type="integer",
     *     example="1"
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
     *     type="file",
     *     example="file.zip"
     *   ),
     *   @OA\Property(
     *     property="screenshot",
     *     type="file",
     *     example="screenshot.zip"
     *   ),
     *   @OA\Property(
     *     property="video",
     *     type="file",
     *     example="video.mp4"
     *   ),
     *   @OA\Property(
     *     property="audio",
     *     type="file",
     *     example="audio.mp3"
     *   ),
     *   @OA\Property(
     *     property="config_file",
     *     type="file",
     *     example="config.cfg"
     *   ),
     *   required={"competition_id", "ip_address", "title", "author", "author_name", "author_email", "author_phone", "author_address", "author_zip", "author_city", "author_countyry_iso_3166_1"},
     * )
     */

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // FIXME: separate request for ApiController
        switch ($this->method()) {
            case 'POST':
                if ($this->is('backend/*')) {
                    $author = [
                        'author_name'               => 'nullable',
                        'author_email'              => 'nullable|email',
                        'author_phone'              => 'nullable',
                        'author_address'            => 'nullable',
                        'author_zip'                => 'nullable',
                        'author_city'               => 'nullable',
                        'author_country_iso_3166_1' => 'nullable',
                    ];
                } else {
                    $author = [
                        'author_name'               => 'required',
                        'author_email'              => 'required|email',
                        'author_phone'              => 'required',
                        'author_address'            => 'required',
                        'author_zip'                => 'required',
                        'author_city'               => 'required',
                        'author_country_iso_3166_1' => 'required',
                    ];
                }

                return [
                    'competition_id'                              => 'required|integer',
                    'visitor_id'                                  => 'nullable|integer',
                    'ip_address'                                  => 'nullable',
                    'sort_position'                               => 'nullable|integer',
                    'title'                                       => 'required',
                    'author'                                      => 'required',
                    'filesize'                                    => 'nullable',
                    'platform'                                    => 'nullable',
                    'description'                                 => 'nullable',
                    'organizer_description'                       => 'nullable',
                    'running_time'                                => 'nullable',
                    'custom_option'                               => 'nullable',
                    'allow_release'                               => 'nullable|boolean',
                    'is_remote'                                   => 'nullable|boolean',
                    'is_recorded'                                 => 'nullable|boolean',
                    'is_prepared'                                 => 'nullable|boolean',
                    'upload_enabled'                              => 'nullable|boolean',
                    'composer_not_member_of_copyright_collective' => 'nullable|boolean',
                    'composer_name'                               => 'nullable',
                    'composer_email'                              => 'nullable|email',
                    'composer_phone'                              => 'nullable',
                    'composer_address'                            => 'nullable',
                    'composer_zip'                                => 'nullable',
                    'composer_city'                               => 'nullable',
                    'composer_country_iso_3166_1'                 => 'nullable',
                    'final_file_media_id'                         => 'nullable|integer',
                    'status'                                      => 'nullable|integer',
                    'discord_name'                                => 'nullable',
                    'file'                                        => 'nullable|file',
                    'screenshot'                                  => 'nullable|image',
                    'video'                                       => 'nullable|file',
                    'audio'                                       => 'nullable|file',
                    'config_file'                                 => 'nullable|file',
                ] + $author;
            case 'PUT':
            case 'PATCH':
                return [];
        }

        return [];
    }
}
