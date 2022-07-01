<?php

namespace Partymeister\Competitions\Grids;

use Motor\Backend\Grid\Grid;

/**
 * Class CompetitionPrizeGrid
 */
class CompetitionPrizeGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('id', 'ID', true);
        $this->setDefaultSorting('id', 'ASC');
        //$this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.competition_prizes.edit');
        //$this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.competition_prizes.destroy');
    }
}
