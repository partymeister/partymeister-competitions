<?php

namespace Partymeister\Competitions\Grids;

use Motor\Admin\Grid\Grid;
use Motor\Admin\Grid\Renderers\CollectionRenderer;
use Motor\Admin\Grid\Renderers\TranslateRenderer;

/**
 * Class OptionGroupGrid
 */
class OptionGroupGrid extends Grid
{
    protected function setup()
    {
        $this->addColumn('name', trans('motor-backend::backend/global.name'), true);
        $this->setDefaultSorting('name', 'ASC');
        $this->addColumn('type', trans('partymeister-competitions::backend/option_groups.type'), true)
             ->renderer(TranslateRenderer::class, ['file' => 'partymeister-competitions::backend/option_groups.types']);

        $this->addColumn('options', trans('partymeister-competitions::backend/option_groups.options'))
             ->renderer(CollectionRenderer::class, ['column' => 'name']);

        $this->addEditAction(trans('motor-backend::backend/global.edit'), 'backend.option_groups.edit');
        $this->addDeleteAction(trans('motor-backend::backend/global.delete'), 'backend.option_groups.destroy');
    }
}
