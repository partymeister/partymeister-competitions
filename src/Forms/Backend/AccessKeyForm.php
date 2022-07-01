<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class AccessKeyForm
 */
class AccessKeyForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('access_key', 'text', [
            'label' => trans('partymeister-competitions::backend/access_keys.access_key'),
            'rules' => 'required',
        ])
             ->add('ip_address', 'text', [
                 'label' => trans('partymeister-competitions::backend/access_keys.ip_address'),
                 'rules' => 'required',
             ])
             ->add('registered_at', 'static', ['label' => trans('partymeister-competitions::backend/access_keys.registered_at')])
             ->add('submit', 'submit', [
                 'attr'  => ['class' => 'btn btn-primary competition-submit'],
                 'label' => trans('partymeister-competitions::backend/access_keys.save'),
             ]);
    }
}
