<?php

namespace Partymeister\Competitions\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Kra8\Snowflake\HasShortflakePrimary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Motor\Admin\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;
use Motor\Media\Models\FileAssociation;
use RichanFongdasen\EloquentBlameable\BlameableTrait;
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
 *
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
    use BlameableTrait;
    use HasShortflakePrimary;

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
     *
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(?Media $media = null): void
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

    /**
     * Check if all qualified entries have their final file confirmed.
     */
    public function getAllFinalFilesConfirmedAttribute(): bool
    {
        $qualified = $this->qualified_entries()->get();

        if ($qualified->isEmpty()) {
            return false;
        }

        return $qualified->every(fn ($entry) => $entry->final_file_media_id > 0);
    }

    public function getLiveVotingEnabledAttribute()
    {
        if ($this->competition_type->has_out_of_competition_voting) {
            $lv = LiveVote::first();
            if (!is_null($lv) && $lv->competition_id == $this->id) {
                return true;
            }
        }
        return false;
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
        return $this->hasMany(Entry::class)
                    ->where('status', 1)
                    ->orderBy('sort_position', 'ASC');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unqualified_entries_with_opt_in()
    {
        return $this->hasMany(Entry::class)
                    ->where('status', '>=', 3) // not preselected or disqualified
                    ->where('visitor_id', '>', 0)
                    ->where('notify_about_status', true)
                    ->orderBy('sort_position', 'ASC');
    }

    /**
     * Get all qualified entries for this competition with bulk-computed vote totals.
     *
     * Returns a collection of arrays keyed by entry_id, each containing:
     *   id, title, author, points, special_votes, comments, remote_type, rank, tie
     *
     * Uses bulk queries instead of per-entry accessors to avoid N+1.
     */
    public function getEntryVotesAttribute(): array
    {
        $entries = $this->entries()
            ->where('status', 1)
            ->with('visitor.access_key')
            ->get();

        if ($entries->isEmpty()) {
            return [];
        }

        $entryIds = $entries->pluck('id')->all();

        // Bulk: visitor vote totals (average per visitor, then sum across visitors)
        $voteTotals = DB::table(
            DB::raw('(SELECT entry_id, visitor_id, SUM(points)/COUNT(id) as points_per_visitor FROM votes WHERE entry_id IN (' . implode(',', $entryIds) . ') GROUP BY entry_id, visitor_id) as sub')
        )
            ->select('entry_id', DB::raw('SUM(points_per_visitor) as total_points'))
            ->groupBy('entry_id')
            ->pluck('total_points', 'entry_id');

        // Bulk: manual vote totals
        $manualVoteTotals = DB::table('manual_votes')
            ->select('entry_id', DB::raw('SUM(points) as total_points'))
            ->whereIn('entry_id', $entryIds)
            ->groupBy('entry_id')
            ->pluck('total_points', 'entry_id');

        // Bulk: special vote totals
        $specialVoteTotals = DB::table('votes')
            ->select('entry_id', DB::raw('SUM(special_vote) as special_votes'))
            ->whereIn('entry_id', $entryIds)
            ->groupBy('entry_id')
            ->pluck('special_votes', 'entry_id');

        // Bulk: vote comments
        $allComments = DB::table('votes')
            ->select('entry_id', 'comment')
            ->whereIn('entry_id', $entryIds)
            ->where('comment', '!=', '')
            ->get()
            ->groupBy('entry_id')
            ->map(fn ($rows) => $rows->pluck('comment')->toArray());

        $results = [];
        foreach ($entries as $entry) {
            $points = ($voteTotals[$entry->id] ?? 0) + ($manualVoteTotals[$entry->id] ?? 0);
            $results[] = [
                'id'            => $entry->id,
                'title'         => $entry->title,
                'author'        => $entry->author,
                'points'        => $points,
                'special_votes' => (int) ($specialVoteTotals[$entry->id] ?? 0),
                'comments'      => $allComments[$entry->id] ?? [],
                'remote_type'   => $entry->remote_type,
                'tie'           => false,
            ];
        }

        // Sort by points descending
        usort($results, fn ($a, $b) => $b['points'] <=> $a['points']);

        // Assign ranks and detect ties
        $uniquePoints = [];
        foreach ($results as $key => $entry) {
            $pointsKey = (string) $entry['points'];
            if (! array_key_exists($pointsKey, $uniquePoints)) {
                $uniquePoints[$pointsKey] = 1;
                $rank = array_sum($uniquePoints);
            } else {
                $uniquePoints[$pointsKey]++;
            }

            $results[$key]['rank'] = $rank;

            if (isset($results[$key - 1]) && $results[$key]['points'] == $results[$key - 1]['points']) {
                $results[$key]['tie'] = true;
                $results[$key - 1]['tie'] = true;
            }
        }

        return $results;
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
