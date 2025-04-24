<?php

namespace Partymeister\Competitions\Models\Component;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Kra8\Snowflake\HasShortflakePrimary;
use Motor\CMS\Models\ComponentBaseModel;
use Motor\CMS\Models\Navigation;
use Motor\CMS\Models\PageVersionComponent;

/**
 * Partymeister\Competitions\Models\Component\ComponentEntryScreenshot
 *
 * @property int $id
 * @property int|null $entries_page_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|PageVersionComponent[] $component
 * @property-read Navigation|null $entries_page
 *
 * @method static Builder|ComponentEntryScreenshot newModelQuery()
 * @method static Builder|ComponentEntryScreenshot newQuery()
 * @method static Builder|ComponentEntryScreenshot query()
 * @method static Builder|ComponentEntryScreenshot whereCreatedAt($value)
 * @method static Builder|ComponentEntryScreenshot whereEntriesPageId($value)
 * @method static Builder|ComponentEntryScreenshot whereId($value)
 * @method static Builder|ComponentEntryScreenshot whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class ComponentEntryScreenshot extends ComponentBaseModel
{
    use HasShortflakePrimary;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entries_page_id',
    ];

    /**
     * Preview function for the page editor
     *
     * @return mixed
     */
    public function preview()
    {
        return [
            'name' => trans('partymeister-competitions::component/entry-screenshots.component'),
            'preview' => 'Preview for ComponentEntryScreenshot component',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function entries_page()
    {
        return $this->belongsTo(Navigation::class, 'entries_page_id');
    }
}
