<?php

namespace Partymeister\Competitions\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\ManualVote;

class ManualVoteService extends BaseService
{
    protected string $model = ManualVote::class;
}
