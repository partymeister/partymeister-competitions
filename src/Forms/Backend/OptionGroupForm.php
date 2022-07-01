<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OptionGroupForm
 */
class OptionGroupForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('name', 'text', ['label' => trans('motor-backend::backend/global.name'), 'rules' => 'required'])
             ->add('type', 'select', [
                 'label'   => trans('partymeister-competitions::backend/option_groups.type'),
                 'choices' => trans('partymeister-competitions::backend/option_groups.types'),
             ])
             ->add('options', 'collection', [
                 'type'    => 'form',
                 'label'   => false,
                 'wrapper' => false,
                 'options' => [
                     'class'   => OptionForm::class,
                     'label'   => false,
                     'wrapper' => [
                         'class' => 'row',
                     ],
                 ],
             ])
             ->add('submit', 'submit', [
                 'attr'  => ['class' => 'btn btn-primary'],
                 'label' => trans('partymeister-competitions::backend/option_groups.save'),
             ]);
    }
}
