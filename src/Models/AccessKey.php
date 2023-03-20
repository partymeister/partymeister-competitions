<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Motor\Backend\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use Partymeister\Core\Models\Visitor;
use RichanFongdasen\EloquentBlameable\BlameableTrait;

/**
 * Partymeister\Competitions\Models\AccessKey
 *
 * @property int $id
 * @property int|null $visitor_id
 * @property string $access_key
 * @property string $ip_address
 * @property string|null $registered_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read User $updater
 *
 * @method static Builder|AccessKey filteredBy(Filter $filter, $column)
 * @method static Builder|AccessKey filteredByMultiple(Filter $filter)
 * @method static Builder|AccessKey newModelQuery()
 * @method static Builder|AccessKey newQuery()
 * @method static Builder|AccessKey query()
 * @method static Builder|AccessKey search($q, $full_text = false)
 * @method static Builder|AccessKey whereAccessKey($value)
 * @method static Builder|AccessKey whereCreatedAt($value)
 * @method static Builder|AccessKey whereCreatedBy($value)
 * @method static Builder|AccessKey whereDeletedBy($value)
 * @method static Builder|AccessKey whereId($value)
 * @method static Builder|AccessKey whereIpAddress($value)
 * @method static Builder|AccessKey whereRegisteredAt($value)
 * @method static Builder|AccessKey whereUpdatedAt($value)
 * @method static Builder|AccessKey whereUpdatedBy($value)
 * @method static Builder|AccessKey whereVisitorId($value)
 * @mixin Eloquent
 */
class AccessKey extends Model
{
    use Searchable;
    use Filterable;
    use BlameableTrait;
    use HasUuids;

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'access_key',
        'ip_address',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visitor_id',
        'access_key',
        'ip_address',
        'registered_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
