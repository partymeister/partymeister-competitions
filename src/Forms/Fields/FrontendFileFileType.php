<?php

namespace Partymeister\Competitions\Forms\Fields;

use Motor\Backend\Forms\Fields\FileFileType;

class FrontendFileFileType extends FileFileType
{
    protected function getTemplate()
    {
        return 'partymeister-competitions::laravel-form-builder.frontend-file-file';
    }
}
