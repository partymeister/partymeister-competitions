<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Kra8\Snowflake\HasShortflakePrimary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Motor\Backend\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * Partymeister\Competitions\Models\Option
 *
 * @property int $id
 * @property int $option_group_id
 * @property int $sort_position
 * @property string $name
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read OptionGroup $group
 * @property-read User $updater
 *
 * @method static Builder|Option filteredBy(Filter $filter, $column)
 * @method static Builder|Option filteredByMultiple(Filter $filter)
 * @method static Builder|Option newModelQuery()
 * @method static Builder|Option newQuery()
 * @method static Builder|Option query()
 * @method static Builder|Option search($q, $full_text = false)
 * @method static Builder|Option whereCreatedAt($value)
 * @method static Builder|Option whereCreatedBy($value)
 * @method static Builder|Option whereDeletedBy($value)
 * @method static Builder|Option whereId($value)
 * @method static Builder|Option whereName($value)
 * @method static Builder|Option whereOptionGroupId($value)
 * @method static Builder|Option whereSortPosition($value)
 * @method static Builder|Option whereUpdatedAt($value)
 * @method static Builder|Option whereUpdatedBy($value)
 * @mixin Eloquent
 */
class Option extends Model
{
    use Searchable;
    use Filterable;
    use BlameableTrait;
    use HasShortflakePrimary;

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
        'option_group_id',
        'name',
        'sort_position',
    ];

    /**
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(OptionGroup::class);
    }
}
