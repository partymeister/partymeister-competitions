<?php

namespace Partymeister\Competitions\Http\Resources\V2;

use Motor\Core\Http\Resources\V2\BaseCollection;

class VoteCategoryCollection extends BaseCollection
{
    public $collects = VoteCategoryResource::class;

    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
