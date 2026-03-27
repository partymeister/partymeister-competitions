<?php

namespace Partymeister\Competitions\Services\Component;

use Motor\CMS\Services\ComponentBaseService;
use Partymeister\Competitions\Models\Component\ComponentEntryUpload;

/**
 * Class ComponentEntryUploadService
 */
class ComponentEntryUploadService extends ComponentBaseService
{
    protected string $model = ComponentEntryUpload::class;

    /**
     * @var string
     */
    protected $name = 'entry-uploads';
}
