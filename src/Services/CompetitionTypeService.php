<?php

namespace Partymeister\Competitions\Services;

use Motor\Backend\Services\BaseService;
use Partymeister\Competitions\Models\CompetitionType;

/**
 * Class CompetitionTypeService
 *
 * @package Partymeister\Competitions\Services
 */
class CompetitionTypeService extends BaseService
{
    /**
     * @var string
     */
    protected $model = CompetitionType::class;
}
