<?php

namespace Partymeister\Competitions\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Motor\Backend\Services\BaseService;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Motor\Media\Models\FileAssociation;
use Partymeister\Competitions\Events\CompetitionSaved;
use Partymeister\Competitions\Models\Competition;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class CompetitionService
 */
class CompetitionService extends BaseService
{
    /**
     * @var string
     */
    protected $model = Competition::class;

    public function filters()
    {
        //$this->filter->addClientFilter();
        $this->filter->add(new SelectRenderer('has_prizegiving'))
                     ->setOptionPrefix(trans('partymeister-competitions::backend/competitions.has_prizegiving'))
                     ->setEmptyOption('-- '.trans('partymeister-competitions::backend/competitions.has_prizegiving').' --')
                     ->setOptions([
                         1 => trans('motor-backend::backend/global.yes'),
                         0 => trans('motor-backend::backend/global.no'),
                     ]);
        $this->filter->add(new SelectRenderer('upload_enabled'))
                     ->setOptionPrefix(trans('partymeister-competitions::backend/competitions.upload_enabled'))
                     ->setEmptyOption('-- '.trans('partymeister-competitions::backend/competitions.upload_enabled').' --')
                     ->setOptions([
                         1 => trans('motor-backend::backend/global.yes'),
                         0 => trans('motor-backend::backend/global.no'),
                     ]);
        $this->filter->add(new SelectRenderer('voting_enabled'))
                     ->setOptionPrefix(trans('partymeister-competitions::backend/competitions.voting_enabled'))
                     ->setEmptyOption('-- '.trans('partymeister-competitions::backend/competitions.voting_enabled').' --')
                     ->setOptions([
                         1 => trans('motor-backend::backend/global.yes'),
                         0 => trans('motor-backend::backend/global.no'),
                     ]);
    }

    public function beforeUpdate()
    {
        if (isset($this->data['upload_enabled'])) {
            $this->data['upload_enabled'] = (bool) $this->data['upload_enabled'];
        }
        if (isset($this->data['voting_enabled'])) {
            $this->data['voting_enabled'] = (bool) $this->data['voting_enabled'];
        }
    }

    public function afterUpdate()
    {
        if (count($this->request->get('option_groups', [])) > 0) {
            $this->record->option_groups()
                         ->detach();
        }
        if ($this->request->get('vote_categories', null) > 0) {
            $this->record->vote_categories()
                         ->detach();
        }

        // Delete all playlist items for this playlist
        foreach ($this->record->file_associations()
                              ->get() as $fileAssociation) {
            if ($this->request->get($fileAssociation->identifier) != '' || $this->request->get($fileAssociation->identifier) == 'deleted') {
                $fileAssociation->delete();
            }
        }

        self::hardLinkReleases($this->record);

        $this->afterCreate();
    }

    /**
     * @param $competition
     */
    public static function hardLinkReleases($competition)
    {
        if (! $competition->voting_enabled) {
            return;
        }

        if (! is_dir(base_path('releases'))) {
            mkdir(base_path('releases'));
        }

        $directory = base_path('releases/'.Str::slug($competition->name));

        if (! is_dir($directory)) {
            mkdir($directory);
        }

        try {
            $files = glob($directory.'/*'); //get all file names
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        } catch (Exception $e) {
            Log::channel('debug')
               ->info($e->getMessage());
        }

        // Hardlink the files in the correct order, clear directory beforehand
        foreach ($competition->qualified_entries as $entry) {
            if ($entry->final_file_media_id > 0) {
                Log::channel('debug')
                   ->info($entry->final_file_media_id);
                $media = Media::find($entry->final_file_media_id);
                if (is_null($media)) {
                    continue;
                }
                if (file_exists($media->getPath()) && ! file_exists($directory.'/'.$media->file_name)) {
                    link($media->getPath(), $directory.'/'.$media->file_name);
                }
            }
        }
    }

    public function afterCreate()
    {
        foreach ($this->request->get('option_groups', []) as $id) {
            $this->record->option_groups()
                         ->attach($id);
        }
        if ($this->request->get('vote_categories', null) > 0) {
            $this->record->vote_categories()
                         ->attach($this->request->get('vote_categories'));
        }
        $this->addFileAssociation('video_1');
        $this->addFileAssociation('video_2');
        $this->addFileAssociation('video_3');

        event(new CompetitionSaved($this->record));
    }

    /**
     * @param $field
     */
    protected function addFileAssociation($field)
    {
        if ($this->request->get($field) == '' || $this->request->get($field) == 'deleted') {
            return;
        }

        $file = json_decode($this->request->get($field));

        // Create file association
        $fa = new FileAssociation();
        $fa->file_id = $file->id;
        $fa->model_type = get_class($this->record);
        $fa->model_id = $this->record->id;
        $fa->identifier = $field;
        $fa->save();
    }
}
