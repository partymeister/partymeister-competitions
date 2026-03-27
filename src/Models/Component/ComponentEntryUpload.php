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
 * Partymeister\Competitions\Models\Component\ComponentEntryUpload
 *
 * @property int $id
 * @property int|null $entries_page_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|PageVersionComponent[] $component
 * @property-read Navigation|null $entries_page
 *
 * @method static Builder|ComponentEntryUpload newModelQuery()
 * @method static Builder|ComponentEntryUpload newQuery()
 * @method static Builder|ComponentEntryUpload query()
 * @method static Builder|ComponentEntryUpload whereCreatedAt($value)
 * @method static Builder|ComponentEntryUpload whereEntriesPageId($value)
 * @method static Builder|ComponentEntryUpload whereId($value)
 * @method static Builder|ComponentEntryUpload whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class ComponentEntryUpload extends ComponentBaseModel
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
            'name' => trans('partymeister-competitions::component/entry-uploads.component'),
            'preview' => 'Preview for ComponentEntryUpload component',
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
