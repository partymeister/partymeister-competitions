<?php

namespace Partymeister\Competitions\Services;

use Motor\Admin\Services\BaseService;
use Partymeister\Competitions\Models\LiveVote;

class LiveVoteService extends BaseService
{
    protected string $model = LiveVote::class;

    protected array $loadColumns = ['competition', 'entry'];
}
