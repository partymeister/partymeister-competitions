<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class OptionForm
 *
 * @package Partymeister\Competitions\Forms\Backend
 */
class OptionForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('name', 'text', [
            'label'   => false,
            'wrapper' => ['class' => 'form-group col-md-11'],
            'attr'    => ['class' => 'form-control options-name', 'tabindex' => 10],
        ])
             ->add('delete', 'custom_button', [
                 'label'   => false,
                 'tag'     => 'button',
                 'wrapper' => ['class' => 'col-md-1'],
                 'attr'    => ['class' => 'btn-danger remove-options', 'icon' => 'fa fa-trash'],
             ]);
    }
}
