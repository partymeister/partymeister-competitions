<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class CompetitionTypeCollection extends BaseCollection
{
    public $collects = CompetitionTypeResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
