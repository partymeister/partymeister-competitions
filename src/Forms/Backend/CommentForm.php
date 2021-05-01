<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

/**
 * Class CommentForm
 *
 * @package Partymeister\Competitions\Forms\Backend
 */
class CommentForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $this->add('message', 'textarea', ['label' => 'Message'])
             ->add('mark_as_read', 'hidden', ['attr' => ['id' => 'mark_as_read']])
             ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary btn-block'], 'label' => 'Send']);
    }
}
