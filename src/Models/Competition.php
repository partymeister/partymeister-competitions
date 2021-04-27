<?php

namespace Partymeister\Competitions\Models;

use Culpa\Traits\Blameable;
use Culpa\Traits\CreatedBy;
use Culpa\Traits\DeletedBy;
use Culpa\Traits\UpdatedBy;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Motor\Backend\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use Motor\Media\Models\FileAssociation;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Partymeister\Competitions\Models\Competition
 *
 * @property int $id
 * @property int|null $competition_type_id
 * @property int $sort_position
 * @property int $prizegiving_sort_position
 * @property string $name
 * @property int $has_prizegiving
 * @property int $upload_enabled
 * @property int $voting_enabled
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CompetitionType|null $competition_type
 * @property-read User $creator
 * @property-read Collection|Entry[] $entries
 * @property-read User|null $eraser
 * @property-read Collection|FileAssociation[] $file_associations
 * @property-read mixed $entry_count
 * @property-read mixed $sorted_entries
 * @property-read Collection|Media[] $media
 * @property-read Collection|OptionGroup[] $option_groups
 * @property-read Collection|CompetitionPrize[] $prizes
 * @property-read User $updater
 * @property-read Collection|VoteCategory[] $vote_categories
 * @method static Builder|Competition filteredBy(Filter $filter, $column)
 * @method static Builder|Competition filteredByMultiple(Filter $filter)
 * @method static Builder|Competition newModelQuery()
 * @method static Builder|Competition newQuery()
 * @method static Builder|Competition query()
 * @method static Builder|Competition search($q, $full_text = false)
 * @method static Builder|Competition whereCompetitionTypeId($value)
 * @method static Builder|Competition whereCreatedAt($value)
 * @method static Builder|Competition whereCreatedBy($value)
 * @method static Builder|Competition whereDeletedBy($value)
 * @method static Builder|Competition whereHasPrizegiving($value)
 * @method static Builder|Competition whereId($value)
 * @method static Builder|Competition whereName($value)
 * @method static Builder|Competition wherePrizegivingSortPosition($value)
 * @method static Builder|Competition whereSortPosition($value)
 * @method static Builder|Competition whereUpdatedAt($value)
 * @method static Builder|Competition whereUpdatedBy($value)
 * @method static Builder|Competition whereUploadEnabled($value)
 * @method static Builder|Competition whereVotingEnabled($value)
 * @mixin Eloquent
 */
class Competition extends Model implements HasMedia
{
    use InteractsWithMedia;
    use Searchable;
    use Filterable;
    use Blameable, CreatedBy, UpdatedBy, DeletedBy;

    /**
     * Columns for the Blameable trait
     *
     * @var array
     */
    protected $blameable = ['created', 'updated', 'deleted'];

    /**
     * Searchable columns for the searchable trait
     *
     * @var array
     */
    protected $searchableColumns = [
        'competitions.name',
        'competition_type.name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'competition_type_id',
        'has_prizegiving',
        'sort_position',
        'prizegiving_sort_position',
        'upload_enabled',
        'voting_enabled',
    ];

    /**
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(320)
             ->height(240)
             ->nonQueued();

        $this->addMediaConversion('preview')
             ->width(1280)
             ->height(1024)
             ->nonQueued();
    }

    ///**
    // * @return Collection
    // */
    //public function getSortedEntriesAttribute()
    //{
    //    return $this->entries()
    //                ->where('status', 1)
    //                ->orderBy('sort_position', 'ASC')
    //                ->with('competition')
    //                ->get();
    //}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualified_entries()
    {
        return $this->hasMany(Entry::class)->where('status', 1)->orderBy('sort_position', 'ASC');
    }

    /**
     * @return HasMany
     */
    public function entries()
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * @return int
     */
    public function getEntryCountAttribute()
    {
        return $this->entries()
                    ->count();
    }

    /**
     * @return BelongsTo
     */
    public function competition_type()
    {
        return $this->belongsTo(CompetitionType::class);
    }

    /**
     * @return BelongsToMany
     */
    public function option_groups()
    {
        return $this->belongsToMany(OptionGroup::class);
    }

    /**
     * @return BelongsToMany
     */
    public function vote_categories()
    {
        return $this->belongsToMany(VoteCategory::class);
    }

    /**
     * @return MorphMany
     */
    public function file_associations()
    {
        return $this->morphMany(FileAssociation::class, 'model');
    }

    /**
     * @return HasMany
     */
    public function prizes()
    {
        return $this->hasMany(CompetitionPrize::class);
    }
}
