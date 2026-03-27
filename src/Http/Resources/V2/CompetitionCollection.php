<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class CompetitionCollection extends BaseCollection
{
    public $collects = CompetitionResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
