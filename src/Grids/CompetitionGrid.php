<?php

namespace Partymeister\Competitions\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\BladeRenderer;
use Motor\Backend\Grid\Renderers\BooleanRenderer;

/**
 * Class CompetitionGrid
 *
 * @package Partymeister\Competitions\Grids
 */
class CompetitionGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('name', trans('motor-backend::backend/global.name'), true)
             ->renderer(BladeRenderer::class, ['template' => 'partymeister-competitions::grid.competition_name']);

        $this->addColumn('entry_count', trans('partymeister-competitions::backend/entries.entries'));

        $this->addColumn('competition_type.name', trans('partymeister-competitions::backend/competition_types.competition_type'));

        $this->addColumn('sort_position', trans('partymeister-competitions::backend/competitions.sort_position_short'), true)
             ->renderer(BladeRenderer::class, [
                     'template' => 'partymeister-competitions::grid.input_callback',
                     'field'    => 'sort_position',
                 ]);

        $this->addColumn('has_prizegiving', trans('partymeister-competitions::backend/competitions.has_prizegiving_short'), true)
             ->renderer(BooleanRenderer::class);

        $this->addColumn('prizegiving_sort_position', trans('partymeister-competitions::backend/competitions.prizegiving_sort_position_short'), true)
             ->renderer(BladeRenderer::class, [
                 'template' => 'partymeister-competitions::grid.input_callback',
                 'field'    => 'prizegiving_sort_position',
             ])
             ->onCondition('has_prizegiving', true);

        $this->addColumn('switches', trans('partymeister-competitions::backend/competitions.status'))
             ->renderer(BladeRenderer::class, ['template' => 'partymeister-competitions::grid.competition_status']);

        $this->setDefaultSorting('sort_position', 'ASC');
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.competitions.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.competitions.destroy');
    }
}
