<?php

namespace Partymeister\Competitions\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\BooleanRenderer;

/**
 * Class VoteCategoryGrid
 *
 * @package Partymeister\Competitions\Grids
 */
class VoteCategoryGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('name', trans('motor-backend::backend/global.name'), true);
        $this->addColumn('points', trans('partymeister-competitions::backend/vote_categories.points'), true);
        $this->addColumn('has_negative', trans('partymeister-competitions::backend/vote_categories.has_negative'), true)
             ->renderer(BooleanRenderer::class);
        $this->addColumn('has_comment', trans('partymeister-competitions::backend/vote_categories.has_comment'), true)
             ->renderer(BooleanRenderer::class);
        $this->addColumn('has_special_vote', trans('partymeister-competitions::backend/vote_categories.has_special_vote'), true)
             ->renderer(BooleanRenderer::class);
        $this->setDefaultSorting('name', 'ASC');
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.vote_categories.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.vote_categories.destroy');
    }
}
