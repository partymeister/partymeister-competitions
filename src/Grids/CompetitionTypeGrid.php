<?php

namespace Partymeister\Competitions\Grids;

use Motor\Admin\Grid\Grid;
use Partymeister\Competitions\Grid\Renderers\CompetitionTypeRenderer;

/**
 * Class CompetitionTypeGrid
 */
class CompetitionTypeGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('name', trans('motor-admin::backend/global.name'), true);
        $this->addColumn('translated_properties', trans('partymeister-competitions::backend/competition_types.properties'))
            ->renderer(CompetitionTypeRenderer::class);
        $this->setDefaultSorting('name', 'ASC');
        $this->addEditAction(trans('motor-admin::backend/global.edit'), 'backend.competition_types.edit');
        $this->addDeleteAction(trans('motor-admin::backend/global.delete'), 'backend.competition_types.destroy');
    }
}
