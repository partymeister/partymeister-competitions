<?php

namespace Partymeister\Competitions\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\CompetitionType;

/**
 * Class CompetitionTypeService
 */
class CompetitionTypeService extends BaseService
{
    /**
     * @var string
     */
    protected $model = CompetitionType::class;
}
