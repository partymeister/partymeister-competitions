<?php

namespace Partymeister\Competitions\Http\Resources\Vote;

use Illuminate\Support\Facades\Session;
use Motor\Backend\Helpers\Filesize;
use Motor\Backend\Http\Resources\BaseResource;
use Motor\Backend\Http\Resources\MediaResource;
use Partymeister\Competitions\Http\Resources\OptionResource;
use Partymeister\Competitions\Http\Resources\VoteResource;
use Partymeister\Competitions\Models\Vote;
use Partymeister\Core\Http\Resources\VisitorResource;

/**
 * @OA\Schema(
 *   schema="VoteEntryResource",
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

        if ($this->competition && $this->competition->vote_categories && $this->competition->vote_categories[0]) {

            $vote = Vote::where('entry_id', $this->id)
                        ->where('vote_category_id', $this->competition->vote_categories[0]->id)
                        ->where('visitor_id', Session::get('visitor')) //$params->get('visitor_id'))
                        ->first();

            if (is_null($vote)) {
                $vote = new Vote();
            }
        }

        // AUDIO, COMPETITION GEFILTERT, CURRENT VOTE
        return [
            'id'                             => (int) $this->id,
            'sort_position_prefixed'         => (strlen($this->sort_position) == 1 ? '0'.$this->sort_position : $this->sort_position),
            'competition_id'                 => $this->competition_id,
            'competition_name'               => $this->competition->name,
            'title'                          => $this->title,
            'author'                         => $this->author,
            'description'                    => $this->description,
            'screenshot'                     => new MediaResource($this->getFirstMedia('screenshot')),
            'has_screenshot'                 => (bool) $this->competition->competition_type->has_screenshot,
            'vote_category_has_comment'      => (bool) (! is_null($this->competition->vote_categories) ? $this->competition->vote_categories[0]->has_comment : false),
            'vote_category_has_special_vote' => (bool) (! is_null($this->competition->vote_categories) ? $this->competition->vote_categories[0]->has_special_vote : false),
            'vote_category_has_negative'     => (bool) (! is_null($this->competition->vote_categories) ? $this->competition->vote_categories[0]->has_negative : false),
            'vote_category_points'           => (int) (! is_null($this->competition->vote_categories) ? $this->competition->vote_categories[0]->points : 0),
            'vote_category_id'               => (int) (! is_null($this->competition->vote_categories) ? $this->competition->vote_categories[0]->id : 1),
            'vote'                           => isset($vote) ? new VoteResource($vote) : null,
        ];
    }
}
