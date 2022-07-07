<?php

namespace Partymeister\Competitions\Services;

use Illuminate\Support\Facades\Auth;
use Motor\Admin\Services\BaseService;
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
    protected $model = Entry::class;

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
        if (Auth::guard('visitor')
                ->user() != null) {
            $this->data['visitor_id'] = Auth::guard('visitor')
                                            ->user()->id;
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
        $this->uploadFile($this->request->file($prefix.'file'), 'file', 'file', null, true);
        $this->uploadFile($this->request->file($prefix.'config_file'), 'config_file', 'config_file');
    }

    public function afterUpdate()
    {
        $prefix = '';
        if (! is_null($this->form)) {
            $prefix = $this->form->getName() ? $this->form->getName().'.' : '';
        }
        //dd($this->request->input($prefix . 'options', []));

        if (count($this->request->input($prefix.'options', [])) > 0) {
            $this->record->options()
                         ->detach();
            $this->addOptions();
        } else {
            //$this->record->options()->detach();
        }
        $this->addImages();
        event(new EntrySaved($this->record));
    }
}
