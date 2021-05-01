<?php

namespace Partymeister\Competitions\Forms\Backend\Component;

use Kris\LaravelFormBuilder\Form;
use Motor\CMS\Models\Navigation;

/**
 * Class ComponentEntryForm
 *
 * @package Partymeister\Competitions\Forms\Backend\Component
 */
class ComponentEntryForm extends Form
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

        $this->add('entry_edit_page_id', 'select', [
            'label'       => trans('partymeister-competitions::component/entries.entry_edit_page'),
            'empty_value' => trans('motor-backend::backend/global.please_choose'),
            'choices'     => $navigationItemOptions,
        ]);

        $this->add('entry_detail_page_id', 'select', [
            'label'       => trans('partymeister-competitions::component/entries.entry_detail_page'),
            'empty_value' => trans('motor-backend::backend/global.please_choose'),
            'choices'     => $navigationItemOptions,
        ]);

        $this->add('entry_comments_page_id', 'select', [
            'label'       => trans('partymeister-competitions::component/entries.entry_comments_page'),
            'empty_value' => trans('motor-backend::backend/global.please_choose'),
            'choices'     => $navigationItemOptions,
        ]);

        $this->add('entry_screenshots_page_id', 'select', [
            'label'       => trans('partymeister-competitions::component/entries.entry_screenshots_page'),
            'empty_value' => trans('motor-backend::backend/global.please_choose'),
            'choices'     => $navigationItemOptions,
        ]);
    }
}
