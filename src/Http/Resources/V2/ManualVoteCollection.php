<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class ManualVoteCollection extends BaseCollection
{
    public $collects = ManualVoteResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
