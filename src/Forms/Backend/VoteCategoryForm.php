<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class VoteCategoryForm
 */
class VoteCategoryForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('name', 'text', ['label' => trans('motor-backend::backend/global.name'), 'rules' => 'required'])
             ->add('points', 'text', ['label' => trans('partymeister-competitions::backend/vote_categories.points')])
             ->add('has_negative', 'checkbox', ['label' => trans('partymeister-competitions::backend/vote_categories.has_negative')])
             ->add('has_comment', 'checkbox', ['label' => trans('partymeister-competitions::backend/vote_categories.has_comment')])
             ->add('has_special_vote', 'checkbox', ['label' => trans('partymeister-competitions::backend/vote_categories.has_special_vote')])
             ->add('submit', 'submit', [
                 'attr'  => ['class' => 'btn btn-primary'],
                 'label' => trans('partymeister-competitions::backend/vote_categories.save'),
             ]);
    }
}
