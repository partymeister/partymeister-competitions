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
            ])
            ->add('is_remote', 'checkbox', [
                'label' => trans('partymeister-competitions::backend/access_keys.is_remote'),
            ])
            ->add('is_satellite', 'checkbox', [
                'label' => trans('partymeister-competitions::backend/access_keys.is_satellite'),
            ])
            ->add('is_prepaid', 'checkbox', [
                'label' => trans('partymeister-competitions::backend/access_keys.is_prepaid'),
            ])
            ->add('registered_at', 'static', ['label' => trans('partymeister-competitions::backend/access_keys.registered_at')])
            ->add('submit', 'submit', [
                'attr' => ['class' => 'btn btn-primary competition-submit'],
                'label' => trans('partymeister-competitions::backend/access_keys.save'),
            ]);
    }
}
