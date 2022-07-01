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
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Motor\Admin\Models\User;
use Motor\Core\Filter\Filter;
use Motor\Core\Traits\Filterable;
use Motor\Core\Traits\Searchable;

/**
 * Partymeister\Competitions\Models\CompetitionType
 *
 * @property int $id
 * @property string $name
 * @property int $has_platform
 * @property int $has_filesize
 * @property int $has_screenshot
 * @property int $has_audio
 * @property int $has_video
 * @property int $has_recordings
 * @property int $has_composer
 * @property int $has_running_time
 * @property int $is_anonymous
 * @property int $number_of_work_stages
 * @property int $has_remote_entries
 * @property int $file_is_optional
 * @property int $has_config_file
 * @property int $created_by
 * @property int $updated_by
 * @property int|null $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Competition[] $competitions
 * @property-read User $creator
 * @property-read User|null $eraser
 * @property-read mixed $properties
 * @property-read mixed $translated_properties
 * @property-read User $updater
 *
 * @method static Builder|CompetitionType filteredBy(Filter $filter, $column)
 * @method static Builder|CompetitionType filteredByMultiple(Filter $filter)
 * @method static Builder|CompetitionType newModelQuery()
 * @method static Builder|CompetitionType newQuery()
 * @method static Builder|CompetitionType query()
 * @method static Builder|CompetitionType search($q, $full_text = false)
 * @method static Builder|CompetitionType whereCreatedAt($value)
 * @method static Builder|CompetitionType whereCreatedBy($value)
 * @method static Builder|CompetitionType whereDeletedBy($value)
 * @method static Builder|CompetitionType whereFileIsOptional($value)
 * @method static Builder|CompetitionType whereHasAudio($value)
 * @method static Builder|CompetitionType whereHasComposer($value)
 * @method static Builder|CompetitionType whereHasConfigFile($value)
 * @method static Builder|CompetitionType whereHasFilesize($value)
 * @method static Builder|CompetitionType whereHasPlatform($value)
 * @method static Builder|CompetitionType whereHasRecordings($value)
 * @method static Builder|CompetitionType whereHasRemoteEntries($value)
 * @method static Builder|CompetitionType whereHasRunningTime($value)
 * @method static Builder|CompetitionType whereHasScreenshot($value)
 * @method static Builder|CompetitionType whereHasVideo($value)
 * @method static Builder|CompetitionType whereId($value)
 * @method static Builder|CompetitionType whereIsAnonymous($value)
 * @method static Builder|CompetitionType whereName($value)
 * @method static Builder|CompetitionType whereNumberOfWorkStages($value)
 * @method static Builder|CompetitionType whereUpdatedAt($value)
 * @method static Builder|CompetitionType whereUpdatedBy($value)
 * @mixin Eloquent
 */
class CompetitionType extends Model
{
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
        'name',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'has_platform',
        'has_filesize',
        'has_screenshot',
        'has_video',
        'has_audio',
        'has_recordings',
        'has_composer',
        'has_running_time',
        'is_anonymous',
        'number_of_work_stages',
        'has_remote_entries',
        'file_is_optional',
        'has_config_file',
    ];

    /**
     * @return HasMany
     */
    public function competitions()
    {
        return $this->hasMany(Competition::class);
    }

    /**
     * @return array
     */
    public function getTranslatedPropertiesAttribute()
    {
        $properties = $this->getPropertiesAttribute();
        foreach ($properties as $index => $property) {
            if ($property == 'number_of_work_stages') {
                $properties[$index] = trans('partymeister-competitions::backend/competition_types.n_number_of_work_stages', ['number' => $this->number_of_work_stages]);
            } else {
                $properties[$index] = trans('partymeister-competitions::backend/competition_types.'.$property);
            }
        }

        return $properties;
    }

    /**
     * @return array
     */
    public function getPropertiesAttribute()
    {
        $properties = [];
        if ($this->has_platform) {
            $properties[] = 'has_platform';
        }
        if ($this->has_filesize) {
            $properties[] = 'has_filesize';
        }
        if ($this->has_screenshot) {
            $properties[] = 'has_screenshot';
        }
        if ($this->has_audio) {
            $properties[] = 'has_audio';
        }
        if ($this->has_video) {
            $properties[] = 'has_video';
        }
        if ($this->has_recordings) {
            $properties[] = 'has_recordings';
        }
        if ($this->has_running_time) {
            $properties[] = 'has_running_time';
        }
        if ($this->number_of_work_stages > 0) {
            $properties[] = 'number_of_work_stages';
        }
        if ($this->has_composer) {
            $properties[] = 'has_composer';
        }
        if ($this->is_anonymous) {
            $properties[] = 'is_anonymous';
        }
        if ($this->has_remote_entries) {
            $properties[] = 'has_remote_entries';
        }
        if ($this->file_is_optional) {
            $properties[] = 'file_is_optional';
        }
        if ($this->has_config_file) {
            $properties[] = 'has_config_file';
        }

        return $properties;
    }
}
