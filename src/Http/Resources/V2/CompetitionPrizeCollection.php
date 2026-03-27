<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class CompetitionPrizeCollection extends BaseCollection
{
    public $collects = CompetitionPrizeResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
