<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Kra8\Snowflake\HasShortflakePrimary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Motor\Backend\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * Partymeister\Competitions\Models\OptionGroup
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Competition[] $competitions
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read Collection|Option[] $options
 * @property-read User $updater
 *
 * @method static Builder|OptionGroup filteredBy(Filter $filter, $column)
 * @method static Builder|OptionGroup filteredByMultiple(Filter $filter)
 * @method static Builder|OptionGroup newModelQuery()
 * @method static Builder|OptionGroup newQuery()
 * @method static Builder|OptionGroup query()
 * @method static Builder|OptionGroup search($q, $full_text = false)
 * @method static Builder|OptionGroup whereCreatedAt($value)
 * @method static Builder|OptionGroup whereCreatedBy($value)
 * @method static Builder|OptionGroup whereDeletedBy($value)
 * @method static Builder|OptionGroup whereId($value)
 * @method static Builder|OptionGroup whereName($value)
 * @method static Builder|OptionGroup whereType($value)
 * @method static Builder|OptionGroup whereUpdatedAt($value)
 * @method static Builder|OptionGroup whereUpdatedBy($value)
 * @mixin Eloquent
 */
class OptionGroup extends Model
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
        'type',
    ];

    /**
     * @return HasMany
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    /**
     * @return BelongsToMany
     */
    public function competitions()
    {
        return $this->belongsToMany(Competition::class);
    }
}
