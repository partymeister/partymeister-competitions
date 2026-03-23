<?php

namespace Partymeister\Competitions\Forms\Fields;

use Motor\Admin\Forms\Fields\FileVideoType;

class FrontendFileVideoType extends FileVideoType
{
    protected function getTemplate()
    {
        return 'partymeister-competitions::laravel-form-builder.frontend-file-video';
    }
}
