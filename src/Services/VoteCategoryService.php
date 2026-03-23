<?php

namespace Partymeister\Competitions\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\VoteCategory;

/**
 * Class VoteCategoryService
 */
class VoteCategoryService extends BaseService
{
    /**
     * @var string
     */
    protected $model = VoteCategory::class;
}
