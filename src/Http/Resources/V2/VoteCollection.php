<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class VoteCollection extends BaseCollection
{
    public $collects = VoteResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
