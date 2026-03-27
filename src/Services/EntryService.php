<?php

namespace Partymeister\Competitions\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Motor\Admin\Services\BaseService;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Motor\Core\Filter\Renderers\SelectRenderer;
use Partymeister\Competitions\Events\EntrySaved;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;

/**
 * Class EntryService
 */
class EntryService extends BaseService
{
    /**
     * @var string
     */
    protected string $model = Entry::class;

    protected array $loadColumns = ['competition'];

    public function filters()
    {
        //$this->filter->addClientFilter();
        $this->filter->add(new SelectRenderer('competition_id'))
                     ->setOptionPrefix(trans('partymeister-competitions::backend/competitions.competition'))
                     ->setEmptyOption('-- '.trans('partymeister-competitions::backend/competitions.competition').' --')
                     ->setOptions(Competition::orderBy('sort_position', 'ASC')
                                             ->pluck('name', 'id'));

        $this->filter->add(new SelectRenderer('status'))
                     ->setOptionPrefix(trans('partymeister-competitions::backend/entries.status'))
                     ->setEmptyOption('-- '.trans('partymeister-competitions::backend/entries.status').' --')
                     ->setOptions(trans('partymeister-competitions::backend/entries.stati'));
    }

    public function beforeCreate()
    {
        $visitor = Auth::guard('visitor')
                       ->user();
        if ($visitor != null) {
            $this->data['visitor_id'] = $visitor->id;

            // Set remote entry status, if the visitor is remote (d'uh)
            if ($visitor->is_remote) {
                $this->data['is_remote'] = true;
            }

        }
    }

    public function afterCreate()
    {
        $this->addOptions();
        $this->addImages();
        event(new EntrySaved($this->record));
    }

    protected function addOptions()
    {
        $prefix = $this->form->getName() ? $this->form->getName().'.' : '';
        foreach ($this->request->input($prefix.'options', []) as $group) {
            if (is_array($group)) {
                foreach ($group as $id) {
                    $this->record->options()
                                 ->attach($id);
                }
            } else {
                $this->record->options()
                             ->attach($group);
            }
        }
    }

    protected function addImages()
    {
        // We need this in case we have named forms
        $prefix = '';
        if (! is_null($this->form)) {
            $prefix = $this->form->getName() != null ? $this->form->getName().'.' : '';
        }

        $numberOfWorkStages = $this->record->competition->competition_type->number_of_work_stages;
        if ($numberOfWorkStages > 0) {
            for ($i = 1; $i <= $numberOfWorkStages; $i++) {
                $this->uploadFile($this->request->file($prefix.'work_stage_'.$i), 'work_stage_'.$i, 'work_stage_'.$i);
            }
        }

        $this->uploadFile($this->request->file($prefix.'screenshot'), 'screenshot');
        $this->uploadFile($this->request->file($prefix.'video'), 'video');
        $this->uploadFile($this->request->file($prefix.'audio'), 'audio');

        $file = $this->request->file($prefix.'file');
        if ($file instanceof UploadedFile && $file->isValid()) {
            $this->uploadFile($this->request->file($prefix.'file'), 'file', 'file', null, true);
            $this->record->is_recorded = false;
            $this->record->save();
        }

        $this->uploadFile($this->request->file($prefix.'config_file'), 'config_file', 'config_file');
    }

    public function afterUpdate()
    {
        $prefix = '';
        if (! is_null($this->form)) {
            $prefix = $this->form->getName() ? $this->form->getName().'.' : '';
        }
        if (count($this->request->input($prefix.'options', [])) > 0) {
            $this->record->options()
                         ->detach();
            $this->addOptions();
        }
        $this->addImages();
        $this->renameFinalFile();
        event(new EntrySaved($this->record));
    }

    protected function renameFinalFile()
    {
        $newName = $this->request->input('final_file_name');
        $mediaId = $this->record->final_file_media_id;

        if (! $newName || ! $mediaId) {
            return;
        }

        $media = Media::find($mediaId);
        if (is_null($media)) {
            return;
        }

        // Sanitize: strip path components, keep only the filename
        $newName = Str::ascii(basename(trim($newName)));

        if ($newName === '' || $newName === $media->file_name) {
            return;
        }

        // Get the old absolute path before changing the name
        $oldPath = $media->getPath();

        // Change the name to compute the new path
        $media->file_name = $newName;
        $newPath = $media->getPath();

        // Rename on disk
        if ($oldPath !== $newPath && file_exists($oldPath)) {
            rename($oldPath, $newPath);
        }

        // Persist to database without triggering Spatie's observer
        // (we already handled the filesystem rename above)
        $media->saveQuietly();
    }
}
