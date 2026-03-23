<?php

namespace Partymeister\Competitions\Forms\Fields;

use Motor\Admin\Forms\Fields\FileImageType;

class FrontendFileImageType extends FileImageType
{
    protected function getTemplate()
    {
        return 'partymeister-competitions::laravel-form-builder.frontend-file-image';
    }
}
