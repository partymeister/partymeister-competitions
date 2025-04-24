<?php

namespace Partymeister\Competitions\Forms\Backend\Component;

use Kris\LaravelFormBuilder\Form;
use Motor\CMS\Models\Navigation;

/**
 * Class ComponentVotingForm
 */
class ComponentVotingForm extends Form
{
    /**
     * @return mixed|void
     */
    public function buildForm()
    {
        $nodes = Navigation::where('scope', 'main')
            ->where('parent_id', '!=', null)
            ->defaultOrder()
            ->get();

        $navigationItemOptions = [];

        foreach ($nodes as $node) {
            $prefixes = [];
            foreach ($node->ancestors as $ancestor) {
                $prefixes[] = $ancestor->name;
            }
            $navigationItemOptions[$node->id] = implode(' > ', $prefixes).' > '.$node->name;
        }

        $this->add('live_voting_page_id', 'select', [
            'label' => trans('partymeister-competitions::component/votings.live_voting_page'),
            'empty_value' => trans('motor-backend::backend/global.please_choose'),
            'choices' => $navigationItemOptions,
        ]);
    }
}
