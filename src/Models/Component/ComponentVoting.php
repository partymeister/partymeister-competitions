<?php

namespace Partymeister\Competitions\Models\Component;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Motor\CMS\Models\ComponentBaseModel;
use Motor\CMS\Models\Navigation;
use Motor\CMS\Models\PageVersionComponent;

/**
 * Partymeister\Competitions\Models\Component\ComponentVoting
 *
 * @property int $id
 * @property int|null $live_voting_page_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|PageVersionComponent[] $component
 * @property-read Navigation|null $live_voting_page
 *
 * @method static Builder|ComponentVoting newModelQuery()
 * @method static Builder|ComponentVoting newQuery()
 * @method static Builder|ComponentVoting query()
 * @method static Builder|ComponentVoting whereCreatedAt($value)
 * @method static Builder|ComponentVoting whereId($value)
 * @method static Builder|ComponentVoting whereLiveVotingPageId($value)
 * @method static Builder|ComponentVoting whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ComponentVoting extends ComponentBaseModel
{

    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'live_voting_page_id',
    ];

    /**
     * Preview function for the page editor
     *
     * @return mixed
     */
    public function preview()
    {
        return [
            'name'    => trans('partymeister-competitions::component/votings.component'),
            'preview' => 'Preview for ComponentVoting component',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function live_voting_page()
    {
        return $this->belongsTo(Navigation::class, 'live_voting_page_id');
    }
}
