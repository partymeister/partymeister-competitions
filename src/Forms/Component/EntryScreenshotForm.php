<?php

namespace Partymeister\Competitions\Forms\Component;

use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Form;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;

/**
 * Class EntryScreenshotForm
 *
 * @package Partymeister\Competitions\Forms\Component
 */
class EntryScreenshotForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $data = [];

        if (old('competition_id')) {
            $data['competition'] = Competition::find(old('competition_id'));
        } elseif (is_object($this->getModel()) && $this->getModel()->competition_id > 0) {
            $data['competition'] = Competition::find($this->getModel()->competition_id);
        } elseif (is_array($this->getModel()[$this->getName()])) {
            $data['competition'] = Competition::find($this->getModel()[$this->getName()]['competition_id']);
        }
        $data['id'] = Arr::get($this->getModel(), 'entry-upload.id');

        $this->add('competition_id', 'static', [
            'label'       => trans('partymeister-competitions::backend/competitions.competition'),
            'empty_value' => trans('motor-backend::backend/global.please_choose'),
        ]);

        $this->add('submit', 'submit', [
            'attr'  => ['class' => 'button success expanded'],
            'label' => trans('partymeister-competitions::backend/entries.save'),
        ]);

        if (isset($data['competition'])) {
            if ($data['competition']->competition_type->has_screenshot) {
                $this->add('screenshot', 'file_image', [
                    'label' => trans('partymeister-competitions::backend/entries.screenshot'),
                    'model' => Entry::class,
                ]);
            }
        }
    }
}
