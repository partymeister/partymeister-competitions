<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;
use Partymeister\Competitions\Services\VoteService;

class VoteForm extends Form
{

    public function buildForm()
    {
        $results = VoteService::getAllVotesByRank();

        foreach ($results as $competition) {
            foreach ($competition['entries'] as $entry) {
                $this->add('entry[' . $competition['id'] . '][' . $entry['id'] . ']', 'text', [ 'label' => false ]);
            }
        }
        $this->add('submit', 'submit', [
                'attr'  => [ 'class' => 'btn btn-primary' ],
                'label' => trans('partymeister-competitions::backend/votes.save')
            ]);
    }
}
