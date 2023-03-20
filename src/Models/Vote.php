<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;

/**
 * Partymeister\Competitions\Models\Vote
 *
 * @property int $id
 * @property int $competition_id
 * @property int $vote_category_id
 * @property int $entry_id
 * @property int $special_vote
 * @property int $visitor_id
 * @property string $points
 * @property string $comment
 * @property string $ip_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read VoteCategory $vote_category
 *
 * @method static Builder|Vote filteredBy(Filter $filter, $column)
 * @method static Builder|Vote filteredByMultiple(Filter $filter)
 * @method static Builder|Vote newModelQuery()
 * @method static Builder|Vote newQuery()
 * @method static Builder|Vote query()
 * @method static Builder|Vote search($q, $full_text = false)
 * @method static Builder|Vote whereComment($value)
 * @method static Builder|Vote whereCompetitionId($value)
 * @method static Builder|Vote whereCreatedAt($value)
 * @method static Builder|Vote whereEntryId($value)
 * @method static Builder|Vote whereId($value)
 * @method static Builder|Vote whereIpAddress($value)
 * @method static Builder|Vote wherePoints($value)
 * @method static Builder|Vote whereSpecialVote($value)
 * @method static Builder|Vote whereUpdatedAt($value)
 * @method static Builder|Vote whereVisitorId($value)
 * @method static Builder|Vote whereVoteCategoryId($value)
 * @mixin Eloquent
 */
class Vote extends Model
{
    use Searchable;
    use Filterable;
    use HasUuids;

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
        'visitor_id',
        'vote_category_id',
        'special_vote',
        'comment',
        'points',
        'ip_address',
    ];

    /**
     * @return BelongsTo
     */
    public function vote_category()
    {
        return $this->belongsTo(VoteCategory::class);
    }
}
