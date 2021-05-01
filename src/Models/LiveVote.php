<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Partymeister\Competitions\Models\LiveVote
 *
 * @property int $id
 * @property int $competition_id
 * @property int $entry_id
 * @property int $sort_position
 * @property string $title
 * @property string $author
 * @property int $is_current
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Competition $competition
 * @property-read Entry $entry
 * @method static Builder|LiveVote newModelQuery()
 * @method static Builder|LiveVote newQuery()
 * @method static Builder|LiveVote query()
 * @method static Builder|LiveVote whereAuthor($value)
 * @method static Builder|LiveVote whereCompetitionId($value)
 * @method static Builder|LiveVote whereCreatedAt($value)
 * @method static Builder|LiveVote whereEntryId($value)
 * @method static Builder|LiveVote whereId($value)
 * @method static Builder|LiveVote whereIsCurrent($value)
 * @method static Builder|LiveVote whereSortPosition($value)
 * @method static Builder|LiveVote whereTitle($value)
 * @method static Builder|LiveVote whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LiveVote extends Model
{
    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'competition_id',
        'entry_id',
        'sort_position',
        'title',
        'author',
    ];

    /**
     * @return BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * @return BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }
}
