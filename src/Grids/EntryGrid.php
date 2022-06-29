<?php

namespace Partymeister\Competitions\Grids;

use Motor\Backend\Grid\Grid;
use Motor\Backend\Grid\Renderers\BladeRenderer;
use Motor\Backend\Grid\Renderers\DateRenderer;
use Motor\Backend\Grid\Renderers\DecorationRenderer;

/**
 * Class EntryGrid
 */
class EntryGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('id', 'ID', true)
             ->style('min-width: 75px;')
             ->renderer(DecorationRenderer::class, ['style' => 'color: red; font-weight: bold;']);
        $this->addColumn('competition.name', trans('partymeister-competitions::backend/competitions.competition'));
        $this->addColumn('name', trans('partymeister-competitions::backend/entries.name'))
             ->renderer(BladeRenderer::class, ['template' => 'partymeister-competitions::grid.entry_name']);
        $this->addColumn('sort_position', trans('partymeister-competitions::backend/entries.sort_position_short'), true)
             ->renderer(BladeRenderer::class, [
                 'template' => 'partymeister-competitions::grid.input_callback',
                 'field'    => 'sort_position',
             ]);
        $this->addColumn('last_file_upload', trans('partymeister-competitions::backend/entries.last_file_uploaded_at'))
             ->renderer(DateRenderer::class)
             ->style('min-width: 100px;');
        $this->addColumn('status', trans('partymeister-competitions::backend/entries.status'))
             ->renderer(BladeRenderer::class, ['template' => 'partymeister-competitions::grid.entry_status'])
             ->style('min-width: 500px;');
        $this->setDefaultSorting('sort_position', 'ASC');
        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.entries.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.entries.destroy');
    }
}
