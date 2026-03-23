<?php

namespace Partymeister\Competitions\Grids;

use Motor\Admin\Grid\Grid;

/**
 * Class VoteGrid
 */
class VoteGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('id', 'ID', true);
        $this->setDefaultSorting('id', 'ASC');
        $this->addEditAction(trans('motor-admin::backend/global.edit'), 'backend.votes.edit');
        $this->addDeleteAction(trans('motor-admin::backend/global.delete'), 'backend.votes.destroy');
    }
}
