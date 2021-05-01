<?php

namespace Partymeister\Competitions\Services\Component;

use Motor\CMS\Services\ComponentBaseService;
use Partymeister\Competitions\Models\Component\ComponentEntryUpload;

/**
 * Class ComponentEntryUploadService
 *
 * @package Partymeister\Competitions\Services\Component
 */
class ComponentEntryUploadService extends ComponentBaseService
{
    /**
     * @var string
     */
    protected $model = ComponentEntryUpload::class;

    /**
     * @var string
     */
    protected $name = 'entry-uploads';
}
