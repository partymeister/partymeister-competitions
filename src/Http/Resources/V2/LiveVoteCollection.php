<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class LiveVoteCollection extends BaseCollection
{
    public $collects = LiveVoteResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
