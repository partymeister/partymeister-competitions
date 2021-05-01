<?php

namespace Partymeister\Competitions\Services\Component;

use Motor\CMS\Services\ComponentBaseService;
use Partymeister\Competitions\Models\Component\ComponentEntryScreenshot;

/**
 * Class ComponentEntryScreenshotService
 *
 * @package Partymeister\Competitions\Services\Component
 */
class ComponentEntryScreenshotService extends ComponentBaseService
{
    /**
     * @var string
     */
    protected $model = ComponentEntryScreenshot::class;

    /**
     * @var string
     */
    protected $name = 'entry-screenshots';
}
