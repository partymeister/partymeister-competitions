<?php

namespace Partymeister\Competitions\Services\Component;

use Motor\CMS\Services\ComponentBaseService;
use Partymeister\Competitions\Models\Component\ComponentEntry;

/**
 * Class ComponentEntryService
 */
class ComponentEntryService extends ComponentBaseService
{
    /**
     * @var string
     */
    protected $model = ComponentEntry::class;

    /**
     * @var string
     */
    protected $name = 'entries';
}
