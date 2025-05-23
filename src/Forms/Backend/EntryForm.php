<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;
use Partymeister\Competitions\Models\Competition;
use Partymeister\Competitions\Models\Entry;
use Symfony\Component\Intl\Countries;

/**
 * Class EntryForm
 */
class EntryForm extends Form
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
        }

        $files = [];
        if ($this->getModel() instanceof Entry) {
            foreach ($this->getModel()->ordered_files as $file) {
                $files[$file->id] = $file->created_at.' - '.$file->file_name;
            }
        }

        $this->add('competition_id', 'select2', [
            'attr' => ['class' => 'form-control reload-on-change'],
            'label' => trans('partymeister-competitions::backend/competitions.competition'),
            'empty_value' => trans('motor-backend::backend/global.please_choose'),
            'choices' => Competition::orderBy('sort_position')
                ->pluck('name', 'id')
                ->toArray(),
        ])
            ->add('reload_on_change', 'hidden', ['attr' => ['id' => 'reload_on_change']])
            ->add('sort_position', 'text', ['label' => trans('partymeister-competitions::backend/entries.sort_position')])
            ->add('title', 'text', [
                'label' => trans('partymeister-competitions::backend/entries.title'),
                'rules' => 'required',
            ])
            ->add('author', 'text', [
                'label' => trans('partymeister-competitions::backend/entries.author'),
                'rules' => 'required',
            ])
            ->add('description', 'textarea', ['label' => trans('partymeister-competitions::backend/entries.description')])
            ->add('organizer_description', 'textarea', ['label' => trans('partymeister-competitions::backend/entries.organizer_description')])
            ->add('custom_option', 'text', ['label' => trans('partymeister-competitions::backend/entries.custom_option')])
            ->add('allow_release', 'checkbox', [
                'label' => trans('partymeister-competitions::backend/entries.allow_release'),
                'default_value' => true,
            ])
            ->add('is_prepared', 'checkbox', ['label' => trans('partymeister-competitions::backend/entries.is_prepared')])
            ->add('has_explicit_content', 'checkbox', [
                'label' => trans('partymeister-competitions::backend/entries.has_explicit_content'),
                'default_value' => false,
            ])
            ->add('needs_content_check', 'checkbox', [
                'label' => trans('partymeister-competitions::backend/entries.needs_content_check'),
                'default_value' => false,
            ])
            ->add('status', 'select', [
                'label' => trans('partymeister-competitions::backend/entries.status'),
                'choices' => trans('partymeister-competitions::backend/entries.stati'),
            ])
            ->add('upload_enabled', 'checkbox', ['label' => trans('partymeister-competitions::backend/entries.upload_enabled')])
            ->add('final_file_media_id', 'select', [
                'label' => trans('partymeister-competitions::backend/entries.final_file_media_id'),
                'empty_value' => trans('partymeister-competitions::backend/entries.choose'),
                'choices' => $files,
            ])
            ->add('discord_name', 'text', ['label' => trans('partymeister-competitions::backend/entries.discord_name')])
            ->add('author_name', 'text', ['label' => trans('partymeister-competitions::backend/entries.name')])
            ->add('author_email', 'text', ['label' => trans('partymeister-competitions::backend/entries.email')])
            ->add('author_phone', 'text', ['label' => trans('partymeister-competitions::backend/entries.phone')])
            ->add('author_address', 'text', ['label' => trans('partymeister-competitions::backend/entries.address')])
            ->add('author_zip', 'text', ['label' => trans('partymeister-competitions::backend/entries.zip')])
            ->add('author_city', 'text', ['label' => trans('partymeister-competitions::backend/entries.city')])
            ->add('author_country_iso_3166_1', 'select2', [
                'label' => trans('partymeister-competitions::backend/entries.country'),
                'choices' => Countries::getNames(),
            ])
            ->add('options', 'form', [
                'wrapper' => [],
                'class' => '\Partymeister\Competitions\Forms\Backend\EntryOptionForm',
                'label' => false,
                'data' => $data,
            ])
            ->add('submit', 'submit', [
                'attr' => ['class' => 'btn btn-primary'],
                'label' => trans('partymeister-competitions::backend/entries.save'),
            ])
            ->add('file', 'file_file', [
                'label' => trans('partymeister-competitions::backend/entries.file'),
                'model' => Entry::class,
            ]);

        if (isset($data['competition'])) {
            if ($data['competition']->competition_type->has_screenshot) {
                for ($i = 1; $i <= $data['competition']->competition_type->number_of_work_stages; $i++) {
                    $this->add('work_stage_'.$i, 'file_image', [
                        'label' => trans('partymeister-competitions::backend/entries.work_stage').' '.$i,
                        'model' => Entry::class,
                    ]);
                }
            }
            if ($data['competition']->competition_type->has_config_file) {
                $this->add('config_file', 'file_file', [
                    'label' => trans('partymeister-competitions::backend/entries.config_file'),
                    'model' => Entry::class,
                ]);
            }
            if ($data['competition']->competition_type->has_screenshot) {
                $this->add('screenshot', 'file_image', [
                    'label' => trans('partymeister-competitions::backend/entries.screenshot'),
                    'model' => Entry::class,
                ]);
            }
            if ($data['competition']->competition_type->has_video) {
                $this->add('video', 'file_video', [
                    'label' => trans('partymeister-competitions::backend/entries.video'),
                    'model' => Entry::class,
                ]);
            }
            if ($data['competition']->competition_type->has_audio) {
                $this->add('audio', 'file_audio', [
                    'label' => trans('partymeister-competitions::backend/entries.audio'),
                    'model' => Entry::class,
                ]);
            }
            if ($data['competition']->competition_type->has_filesize) {
                $this->add('filesize', 'text', ['label' => trans('partymeister-competitions::backend/entries.filesize')]);
            }
            if ($data['competition']->competition_type->has_platform) {
                $this->add('platform', 'text', ['label' => trans('partymeister-competitions::backend/entries.platform')]);
            }
            if ($data['competition']->competition_type->has_running_time) {
                $this->add('running_time', 'text', ['label' => trans('partymeister-competitions::backend/entries.running_time')]);
            }
            if ($data['competition']->competition_type->has_remote_entries) {
                $this->add('is_remote', 'checkbox', ['label' => trans('partymeister-competitions::backend/entries.is_remote')]);
            }
            if ($data['competition']->competition_type->has_recordings) {
                $this->add('is_recorded', 'checkbox', ['label' => trans('partymeister-competitions::backend/entries.is_recorded')]);
            }
            if ($data['competition']->competition_type->has_ai_options) {
                $this->add('ai_usage', 'select2', [
                    'label' => trans('partymeister-competitions::backend/entries.ai_usage'),
                    'choices' => trans('partymeister-competitions::backend/entries.ai_usage_options'),
                    'empty_value' => trans('motor-backend::backend/global.please_choose'),
                    'rules' => 'required',
                ]);
                $this->add('ai_usage_description', 'textarea', ['label' => trans('partymeister-competitions::backend/entries.ai_usage_description')]);
            }
            if ($data['competition']->competition_type->has_engine_options) {
                $this->add('engine_option', 'select2', [
                    'label' => trans('partymeister-competitions::backend/entries.engine_option'),
                    'choices' => trans('partymeister-competitions::backend/entries.engine_options'),
                    'empty_value' => trans('motor-backend::backend/global.please_choose'),
                    'rules' => 'required',
                ]);
                $this->add('engine_option_description', 'text', ['label' => trans('partymeister-competitions::backend/entries.engine_option_description')]);
                $this->add('engine_creator_involvement', 'select2', [
                    'label' => trans('partymeister-competitions::backend/entries.engine_creator_involvement'),
                    'choices' => trans('partymeister-competitions::backend/entries.engine_creator_involvement_options'),
                    'empty_value' => trans('motor-backend::backend/global.please_choose'),
                    'rules' => 'required',
                ]);
            }

            if ($data['competition']->competition_type->has_composer) {
                $this->add('composer_name', 'text', ['label' => trans('partymeister-competitions::backend/entries.name')])
                    ->add('composer_email', 'text', ['label' => trans('partymeister-competitions::backend/entries.email')])
                    ->add('composer_phone', 'text', ['label' => trans('partymeister-competitions::backend/entries.phone')])
                    ->add('composer_address', 'text', ['label' => trans('partymeister-competitions::backend/entries.address')])
                    ->add('composer_zip', 'text', ['label' => trans('partymeister-competitions::backend/entries.zip')])
                    ->add('composer_city', 'text', ['label' => trans('partymeister-competitions::backend/entries.city')])
                    ->add('composer_country_iso_3166_1', 'select2', [
                        'label' => trans('partymeister-competitions::backend/entries.country'),
                        'choices' => Countries::getNames(),
                    ])
                    ->add('composer_not_member_of_copyright_collective', 'checkbox', ['label' => trans('partymeister-competitions::backend/entries.composer_not_member_of_copyright_collective')]);
            }
        }
    }
}
