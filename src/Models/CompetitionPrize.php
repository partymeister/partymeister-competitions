<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Kra8\Snowflake\HasSnowflakePrimary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Motor\Backend\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * Partymeister\Competitions\Models\CompetitionPrize
 *
 * @property int $id
 * @property int $competition_id
 * @property string $amount
 * @property string $additional
 * @property string $rank
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property-read Competition $competition
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read User $updater
 *
 * @method static Builder|CompetitionPrize filteredBy(Filter $filter, $column)
 * @method static Builder|CompetitionPrize filteredByMultiple(Filter $filter)
 * @method static Builder|CompetitionPrize newModelQuery()
 * @method static Builder|CompetitionPrize newQuery()
 * @method static Builder|CompetitionPrize query()
 * @method static Builder|CompetitionPrize search($q, $full_text = false)
 * @method static Builder|CompetitionPrize whereAdditional($value)
 * @method static Builder|CompetitionPrize whereAmount($value)
 * @method static Builder|CompetitionPrize whereCompetitionId($value)
 * @method static Builder|CompetitionPrize whereCreatedAt($value)
 * @method static Builder|CompetitionPrize whereCreatedBy($value)
 * @method static Builder|CompetitionPrize whereDeletedBy($value)
 * @method static Builder|CompetitionPrize whereId($value)
 * @method static Builder|CompetitionPrize whereRank($value)
 * @method static Builder|CompetitionPrize whereUpdatedAt($value)
 * @method static Builder|CompetitionPrize whereUpdatedBy($value)
 * @mixin Eloquent
 */
class CompetitionPrize extends Model
{
    use Searchable;
    use Filterable;
    use BlameableTrait;
    use HasSnowflakePrimary;

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'amount',
        'competition.name',
        'additional',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'competition_id',
        'amount',
        'additional',
        'rank',
    ];

    /**
     * @return BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
