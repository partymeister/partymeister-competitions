<?php

namespace Partymeister\Competitions\Services\Component;

use Motor\CMS\Services\ComponentBaseService;
use Partymeister\Competitions\Models\Component\ComponentVoting;

/**
 * Class ComponentVotingService
 *
 * @package Partymeister\Competitions\Services\Component
 */
class ComponentVotingService extends ComponentBaseService
{
    /**
     * @var string
     */
    protected $model = ComponentVoting::class;

    /**
     * @var string
     */
    protected $name = 'voting';
}
