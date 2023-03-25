<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Kra8\Snowflake\HasSnowflakePrimary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;

/**
 * Partymeister\Competitions\Models\ManualVote
 *
 * @property int $id
 * @property int $competition_id
 * @property int $entry_id
 * @property string $points
 * @property string $ip_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|ManualVote filteredBy(Filter $filter, $column)
 * @method static Builder|ManualVote filteredByMultiple(Filter $filter)
 * @method static Builder|ManualVote newModelQuery()
 * @method static Builder|ManualVote newQuery()
 * @method static Builder|ManualVote query()
 * @method static Builder|ManualVote search($q, $full_text = false)
 * @method static Builder|ManualVote whereCompetitionId($value)
 * @method static Builder|ManualVote whereCreatedAt($value)
 * @method static Builder|ManualVote whereEntryId($value)
 * @method static Builder|ManualVote whereId($value)
 * @method static Builder|ManualVote whereIpAddress($value)
 * @method static Builder|ManualVote wherePoints($value)
 * @method static Builder|ManualVote whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ManualVote extends Model
{
    use Searchable;
    use Filterable;
    use HasSnowflakePrimary;

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
        'points',
        'ip_address',
    ];
}
