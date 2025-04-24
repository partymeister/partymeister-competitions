<?php

namespace Partymeister\Competitions\Forms\Backend;

use Kris\LaravelFormBuilder\Form;
use Partymeister\Competitions\Models\Competition;

/**
 * Class CompetitionPrizeForm
 */
class CompetitionPrizeForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        foreach (Competition::where('has_prizegiving', true)
            ->orderBy('prizegiving_sort_position', 'ASC')
            ->get() as $competition) {
            for ($i = 1; $i <= 3; $i++) {
                $prize = $competition->prizes()
                    ->where('rank', $i)
                    ->first();

                $this->add('amount['.$competition->id.']['.$i.']', 'text', [
                    'default_value' => (! is_null($prize) ? $prize->amount : ''),
                    'label' => trans('partymeister-competitions::backend/competition_prizes.amount'),
                ])
                    ->add('additional['.$competition->id.']['.$i.']', 'textarea', [
                        'default_value' => (! is_null($prize) ? $prize->additional : ''),
                        'label' => trans('partymeister-competitions::backend/competition_prizes.additional'),
                    ])
                    ->add('rank['.$competition->id.']['.$i.']', 'hidden', ['default_value' => $i]);
            }
        }

        $this->add('submit', 'submit', [
            'attr' => ['class' => 'btn btn-primary'],
            'label' => trans('partymeister-competitions::backend/competition_prizes.save'),
        ]);
    }
}
