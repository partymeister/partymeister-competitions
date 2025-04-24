<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Kra8\Snowflake\HasShortflakePrimary;
use Motor\Backend\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * Partymeister\Competitions\Models\VoteCategory
 *
 * @property int $id
 * @property string $name
 * @property int $points
 * @property int $has_negative
 * @property int $has_comment
 * @property int $has_special_vote
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Competition[] $competitions
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read User $updater
 *
 * @method static Builder|VoteCategory filteredBy(Filter $filter, $column)
 * @method static Builder|VoteCategory filteredByMultiple(Filter $filter)
 * @method static Builder|VoteCategory newModelQuery()
 * @method static Builder|VoteCategory newQuery()
 * @method static Builder|VoteCategory query()
 * @method static Builder|VoteCategory search($q, $full_text = false)
 * @method static Builder|VoteCategory whereCreatedAt($value)
 * @method static Builder|VoteCategory whereCreatedBy($value)
 * @method static Builder|VoteCategory whereDeletedBy($value)
 * @method static Builder|VoteCategory whereHasComment($value)
 * @method static Builder|VoteCategory whereHasNegative($value)
 * @method static Builder|VoteCategory whereHasSpecialVote($value)
 * @method static Builder|VoteCategory whereId($value)
 * @method static Builder|VoteCategory whereName($value)
 * @method static Builder|VoteCategory wherePoints($value)
 * @method static Builder|VoteCategory whereUpdatedAt($value)
 * @method static Builder|VoteCategory whereUpdatedBy($value)
 *
 * @mixin Eloquent
 */
class VoteCategory extends Model
{
    use BlameableTrait;
    use Filterable;
    use HasShortflakePrimary;
    use Searchable;

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'points',
        'has_negative',
        'has_comment',
        'has_special_vote',
    ];

    /**
     * @return BelongsToMany
     */
    public function competitions()
    {
        return $this->belongsToMany(Competition::class);
    }
}
