<?php

namespace Partymeister\Competitions\Models\Component;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Kra8\Snowflake\HasShortflakePrimary;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Motor\CMS\Models\ComponentBaseModel;
use Motor\CMS\Models\Navigation;
use Motor\CMS\Models\PageVersionComponent;

/**
 * Partymeister\Competitions\Models\Component\ComponentEntry
 *
 * @property int $id
 * @property int|null $entry_comments_page_id
 * @property int|null $entry_screenshots_page_id
 * @property int|null $entry_edit_page_id
 * @property int|null $entry_detail_page_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|PageVersionComponent[] $component
 * @property-read Navigation|null $entry_comments_page
 * @property-read Navigation|null $entry_detail_page
 * @property-read Navigation|null $entry_edit_page
 * @property-read Navigation|null $entry_screenshots_page
 *
 * @method static Builder|ComponentEntry newModelQuery()
 * @method static Builder|ComponentEntry newQuery()
 * @method static Builder|ComponentEntry query()
 * @method static Builder|ComponentEntry whereCreatedAt($value)
 * @method static Builder|ComponentEntry whereEntryCommentsPageId($value)
 * @method static Builder|ComponentEntry whereEntryDetailPageId($value)
 * @method static Builder|ComponentEntry whereEntryEditPageId($value)
 * @method static Builder|ComponentEntry whereEntryScreenshotsPageId($value)
 * @method static Builder|ComponentEntry whereId($value)
 * @method static Builder|ComponentEntry whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ComponentEntry extends ComponentBaseModel
{

    use HasShortflakePrimary;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entry_comments_page_id',
        'entry_screenshots_page_id',
        'entry_edit_page_id',
        'entry_detail_page_id',
    ];

    /**
     * Preview function for the page editor
     *
     * @return mixed
     */
    public function preview()
    {
        return [
            'name'    => trans('partymeister-competitions::component/entries.component'),
            'preview' => 'Preview for ComponentEntry component',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function entry_comments_page()
    {
        return $this->belongsTo(Navigation::class, 'entry_comments_page_id');
    }

    /**
     * @return BelongsTo
     */
    public function entry_screenshots_page()
    {
        return $this->belongsTo(Navigation::class, 'entry_screenshots_page_id');
    }

    /**
     * @return BelongsTo
     */
    public function entry_edit_page()
    {
        return $this->belongsTo(Navigation::class, 'entry_edit_page_id');
    }

    /**
     * @return BelongsTo
     */
    public function entry_detail_page()
    {
        return $this->belongsTo(Navigation::class, 'entry_detail_page_id');
    }
}
