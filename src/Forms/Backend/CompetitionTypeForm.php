<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class CompetitionTypeForm
 */
class CompetitionTypeForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('name', 'text', ['label' => trans('motor-backend::backend/global.name'), 'rules' => 'required'])
             ->add('has_platform', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_platform')])
             ->add('has_filesize', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_filesize')])
             ->add('has_screenshot', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_screenshot')])
             ->add('has_audio', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_audio')])
             ->add('has_video', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_video')])
             ->add('has_recordings', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_recordings')])
             ->add('has_composer', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_composer')])
             ->add('has_running_time', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_running_time')])
             ->add('is_anonymous', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.is_anonymous')])
             ->add('has_remote_entries', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_remote_entries')])
             ->add('file_is_optional', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.file_is_optional')])
             ->add('number_of_work_stages', 'text', ['label' => trans('partymeister-competitions::backend/competition_types.number_of_work_stages')])
             ->add('has_config_file', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_config_file')])
             ->add('has_ai_options', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_ai_options')])
             ->add('has_engine_options', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_engine_options')])
             ->add('has_out_of_competition_voting', 'checkbox', ['label' => trans('partymeister-competitions::backend/competition_types.has_out_of_competition_voting')])
             ->add('submit', 'submit', [
                 'attr'  => ['class' => 'btn btn-primary'],
                 'label' => trans('partymeister-competitions::backend/competition_types.save'),
             ]);
    }
}



